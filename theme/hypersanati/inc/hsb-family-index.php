<?php

defined('ABSPATH') || exit;

/**
 * HSB Persistent Family Index V1
 *
 * Manual rebuild only.
 * Existing family rules remain unchanged.
 */

function hsb_family_index_get_active() {
    $index = get_option(
        'hsb_family_index_active_v1',
        array()
    );

    return is_array($index) ? $index : array();
}

function hsb_family_index_build_global() {

    $started = microtime(true);

    $ids = get_posts(array(
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'orderby'        => 'title',
        'order'          => 'ASC',
        'no_found_rows'  => true,
    ));

    $ids = array_map('absint', $ids);

    if (!function_exists(
        'hsb_group_product_ids_by_part_number'
    )) {
        return new WP_Error(
            'family_function_missing',
            'Existing family engine unavailable.'
        );
    }

    if ($ids) {
        update_meta_cache('post', $ids);
        update_object_term_cache($ids, 'product');
    }

    // Use the existing family rules for the entire catalog.
    $groups = hsb_group_product_ids_by_part_number($ids);

    $member_map = array();
    $families = array();

    foreach ($groups as $group) {

        $key = $group['family_key'];

        $families[$key] = $group;

        foreach ($group['ids'] as $id) {
            $member_map[absint($id)] = $key;
        }
    }

    if (count($member_map) !== count($ids)) {
        return new WP_Error(
            'family_index_incomplete',
            'Some products are missing from the index.'
        );
    }

    return array(
        'schema_version' => 1,
        'built_at'       => current_time('mysql'),
        'total_products' => count($ids),
        'total_families' => count($families),
        'families'       => $families,
        'member_map'     => $member_map,
        'build_seconds'  => round(
            microtime(true) - $started,
            3
        ),
    );
}


/**
 * Build permanent category queues using existing family rules.
 */
function hsb_family_index_build_queues($index) {

    $started = microtime(true);

    if (
        empty($index['schema_version']) ||
        !isset($index['member_map'], $index['families'])
    ) {
        return new WP_Error(
            'invalid_family_index',
            'Invalid global family index.'
        );
    }

    $terms = get_terms(array(
        'taxonomy'   => 'product_cat',
        'hide_empty' => true,
    ));

    if (is_wp_error($terms)) {
        return $terms;
    }

    $sale_id = function_exists('hsb_online_sale_category_id')
        ? hsb_online_sale_category_id(false)
        : 0;

    $queues = array();

    foreach ($terms as $term) {

        $term_id = absint($term->term_id);

        if ($term_id === $sale_id) {
            continue;
        }

        $ids = get_posts(array(
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'fields'         => 'ids',
            'orderby'        => 'title',
            'order'          => 'ASC',
            'no_found_rows'  => true,
            'tax_query'      => array(array(
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => $term_id,
            )),
        ));

        $groups = hsb_group_product_ids_by_part_number($ids);

        $groups = hsb_shop_prioritize_families_v3($groups);

        foreach ($groups as $group) {

            $representative = absint(
                $group['representative_id']
            );

            if (
                !isset($index['member_map'][$representative]) ||
                $index['member_map'][$representative]
                    !== $group['family_key']
            ) {
                return new WP_Error(
                    'family_index_mismatch',
                    'Category and global index mismatch.'
                );
            }
        }

        $queues[$term_id] = array_values($groups);
    }

    $index['category_queues'] = $queues;

    $index['queue_build_seconds'] = round(
        microtime(true) - $started,
        3
    );

    return $index;
}


/**
 * Calculate actual Shop card statistics.
 */
function hsb_family_index_build_stats($index) {

    if (!isset($index['category_queues'])) {
        return new WP_Error(
            'missing_queues',
            'Category queues unavailable.'
        );
    }

    $sale_id = absint(
        hsb_online_sale_category_id(false)
    );

    $excluded = array();

    if ($sale_id) {
        $excluded[] = $sale_id;
    }

    $uncategorized = get_term_by(
        'slug',
        'uncategorized',
        'product_cat'
    );

    if ($uncategorized) {
        $excluded[] = absint($uncategorized->term_id);
    }

    $categories = get_terms(array(
        'taxonomy'   => 'product_cat',
        'hide_empty' => true,
        'parent'     => 0,
        'exclude'    => $excluded,
        'orderby'    => 'name',
        'order'      => 'ASC',
    ));

    if (is_wp_error($categories)) {
        return $categories;
    }

    $seen = array();

    foreach ($categories as $category) {

        $subcategories = get_terms(array(
            'taxonomy'   => 'product_cat',
            'hide_empty' => true,
            'parent'     => $category->term_id,
        ));

        if (is_wp_error($subcategories)) {
            return $subcategories;
        }

        if (!$subcategories) {
            $subcategories = array($category);
        }

        foreach ($subcategories as $subcategory) {

            $term_id = absint($subcategory->term_id);

            $groups = $index['category_queues'][$term_id]
                ?? array();

            foreach ($groups as $group) {

                $key = $group['family_key'] ?? '';

                if ($key !== '') {
                    $seen[$key] = true;
                }
            }
        }
    }

    $online_count = 0;

    if ($sale_id) {

        $online_ids = get_posts(array(
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'fields'         => 'ids',
            'no_found_rows'  => true,
            'tax_query'      => array(array(
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => $sale_id,
            )),
        ));

        foreach ($online_ids as $id) {

            $product = wc_get_product($id);

            if ($product && hsb_dual_is_direct($product)) {
                $online_count++;
            }
        }
    }

    $normal_count = count($seen);
    $total_cards = $normal_count + $online_count;
    $total_products = absint($index['total_products']);

    $index['stats'] = array(
        'online_cards' => $online_count,
        'normal_cards' => $normal_count,
        'total_cards'  => $total_cards,
        'reduction'    => $total_products > 0
            ? round(
                (1 - $total_cards / $total_products) * 100,
                2
            )
            : 0,
    );

    return $index;
}


/**
 * Publish a complete validated index in one operation.
 */
function hsb_family_index_publish($index) {

    if (
        !is_array($index) ||
        !isset(
            $index['families'],
            $index['member_map'],
            $index['category_queues'],
            $index['stats']
        ) ||
        count($index['member_map'])
            !== (int) $index['total_products'] ||
        count($index['families'])
            !== (int) $index['total_families']
    ) {
        return new WP_Error(
            'incomplete_index',
            'Incomplete index cannot be published.'
        );
    }

    $index['built_at'] = current_time('mysql');

    $key = 'hsb_family_index_active_v1';

    $saved = update_option($key, $index, false);

    if (!$saved && get_option($key) !== $index) {
        return new WP_Error(
            'index_save_failed',
            'Index could not be saved.'
        );
    }

    return $index;
}


/**
 * Store rebuild progress independently of the active index.
 */
function hsb_family_index_set_progress(
    $status,
    $percent,
    $message
) {
    update_option(
        'hsb_family_index_progress_v1',
        array(
            'status'     => $status,
            'percent'    => max(0, min(100, (int) $percent)),
            'message'    => $message,
            'updated_at' => current_time('mysql'),
        ),
        false
    );
}

function hsb_family_index_get_progress() {
    return get_option(
        'hsb_family_index_progress_v1',
        array(
            'status'  => 'idle',
            'percent' => 0,
            'message' => '',
        )
    );
}


/**
 * Execute a complete manual rebuild.
 * Keep the previous active index until successful publication.
 */
function hsb_family_index_rebuild() {

    global $wpdb;

    $lock_key = 'hsb_family_' . md5(
        DB_NAME . ':' . $wpdb->prefix
    );

    $acquired = $wpdb->get_var(
        $wpdb->prepare(
            'SELECT GET_LOCK(%s, 0)',
            $lock_key
        )
    );

    if ('1' !== (string) $acquired) {
        return new WP_Error(
            'rebuild_locked',
            'بازسازی دیگری در حال اجراست.'
        );
    }

    try {

        hsb_family_index_set_progress(
            'running',
            5,
            'در حال بررسی تمام محصولات فروشگاه...'
        );

        $index = hsb_family_index_build_global();

        if (is_wp_error($index)) {
            throw new RuntimeException(
                $index->get_error_message()
            );
        }

        hsb_family_index_set_progress(
            'running',
            40,
            'خانواده‌ها تشکیل شدند؛ در حال ساخت صف دسته‌بندی‌ها...'
        );

        $index = hsb_family_index_build_queues($index);

        if (is_wp_error($index)) {
            throw new RuntimeException(
                $index->get_error_message()
            );
        }

        hsb_family_index_set_progress(
            'running',
            80,
            'در حال محاسبه آمار کارت‌های فروشگاه...'
        );

        $index = hsb_family_index_build_stats($index);

        if (is_wp_error($index)) {
            throw new RuntimeException(
                $index->get_error_message()
            );
        }

        hsb_family_index_set_progress(
            'running',
            95,
            'در حال ذخیره و انتشار صف جدید...'
        );

        $index = hsb_family_index_publish($index);

        if (is_wp_error($index)) {
            throw new RuntimeException(
                $index->get_error_message()
            );
        }

        hsb_family_index_set_progress(
            'completed',
            100,
            'بازسازی خانواده محصولات با موفقیت انجام شد.'
        );

        return $index;

    } catch (Throwable $e) {

        hsb_family_index_set_progress(
            'failed',
            0,
            $e->getMessage()
        );

        return new WP_Error(
            'rebuild_failed',
            $e->getMessage()
        );

    } finally {

        $wpdb->get_var(
            $wpdb->prepare(
                'SELECT RELEASE_LOCK(%s)',
                $lock_key
            )
        );
    }
}


/**
 * Read the persistent Shop queue.
 * Preserve the existing search and initial fallback behavior.
 */
function hsb_family_index_get_shop_queue(
    $subcategory_id,
    $search_keyword = ''
) {

    $subcategory_id = absint($subcategory_id);

    if (trim((string) $search_keyword) !== '') {
        return hsb_get_shop_subcategory_family_groups(
            $subcategory_id,
            $search_keyword
        );
    }

    $index = hsb_family_index_get_active();

    if (
        empty($index) ||
        !isset($index['category_queues']) ||
        !is_array($index['category_queues'])
    ) {
        return hsb_get_shop_subcategory_family_groups(
            $subcategory_id,
            $search_keyword
        );
    }

    return $index['category_queues'][$subcategory_id]
        ?? array();
}

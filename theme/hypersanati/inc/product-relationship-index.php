<?php
/**
 * HyperSanati Product Relationship Index
 *
 * Relationships are discovered outside the storefront request
 * and stored as product meta.
 *
 * Family:
 *   exact normalized Part Number.
 *
 * Equivalent:
 *   direct Equivalent Codes -> existing Part Numbers,
 *   plus reverse direct declarations.
 *
 * Important:
 *   Equivalent relations are NOT recursively expanded.
 */

defined('ABSPATH') || exit;


if (!defined('HSB_RELATIONSHIP_INDEX_VERSION')) {
    define(
        'HSB_RELATIONSHIP_INDEX_VERSION',
        2
    );
}

if (!defined('HSB_RELATIONSHIP_INDEX_SETTLE_SECONDS')) {
    /*
     * Wait until bulk imports / repeated product edits
     * have been quiet for two minutes before rebuilding.
     */
    define(
        'HSB_RELATIONSHIP_INDEX_SETTLE_SECONDS',
        120
    );
}


/**
 * Normalize an industrial product code for exact comparison.
 */
function hsb_relation_normalize_code($value) {
    $value = trim((string) $value);

    if ('' === $value) {
        return '';
    }

    $value = preg_replace(
        '/\s+/u',
        '',
        $value
    );

    if (function_exists('mb_strtoupper')) {
        return mb_strtoupper(
            $value,
            'UTF-8'
        );
    }

    return strtoupper($value);
}


/**
 * Parse the stored pipe/comma separated Equivalent Codes.
 */
function hsb_relation_parse_equivalent_codes($value) {
    $value = trim((string) $value);

    if ('' === $value) {
        return array();
    }

    $codes = preg_split(
        '/\s*[|,]\s*/u',
        $value,
        -1,
        PREG_SPLIT_NO_EMPTY
    );

    $clean = array();

    foreach ((array) $codes as $code) {
        $code = hsb_relation_normalize_code(
            $code
        );

        if (
            '' === $code ||
            isset($clean[$code])
        ) {
            continue;
        }

        $clean[$code] = true;
    }

    return array_keys($clean);
}


/**
 * Normalize a product-ID array before storage.
 */
function hsb_relation_clean_product_ids($ids) {
    $ids = array_values(
        array_unique(
            array_filter(
                array_map(
                    'absint',
                    (array) $ids
                )
            )
        )
    );

    sort($ids, SORT_NUMERIC);

    return $ids;
}


/**
 * Parse WooCommerce/custom relationship ID meta.
 *
 * Supports:
 * - native PHP arrays
 * - serialized arrays
 * - pipe/comma/space separated IDs
 */
function hsb_relation_parse_product_ids($value) {
    if (is_string($value)) {
        $value = maybe_unserialize($value);
    }

    if (is_array($value)) {
        return hsb_relation_clean_product_ids(
            $value
        );
    }

    $value = trim((string) $value);

    if ('' === $value) {
        return array();
    }

    return hsb_relation_clean_product_ids(
        preg_split(
            '/[\s,|]+/',
            $value,
            -1,
            PREG_SPLIT_NO_EMPTY
        )
    );
}


/**
 * Read stored family IDs.
 *
 * If the relationship index has not been generated yet,
 * return only the current product instead of running a
 * catalog-wide fallback query.
 */
function hsb_get_indexed_family_product_ids(
    $product_id,
    $include_self = true
) {
    $product_id = absint($product_id);

    if (!$product_id) {
        return array();
    }

    $ids = get_post_meta(
        $product_id,
        '_hsb_family_product_ids',
        true
    );

    if (!is_array($ids)) {
        $ids = array();
    }

    $ids = hsb_relation_clean_product_ids(
        $ids
    );

    if (
        $include_self &&
        !in_array($product_id, $ids, true)
    ) {
        $ids[] = $product_id;
        sort($ids, SORT_NUMERIC);
    }

    if (!$include_self) {
        $ids = array_values(
            array_filter(
                $ids,
                static function ($id) use ($product_id) {
                    return $id !== $product_id;
                }
            )
        );
    }

    return $ids;
}


/**
 * Read stored direct equivalent-product IDs.
 */
function hsb_get_indexed_equivalent_product_ids(
    $product_id
) {
    $product_id = absint($product_id);

    if (!$product_id) {
        return array();
    }

    $ids = get_post_meta(
        $product_id,
        '_hsb_equivalent_product_ids',
        true
    );

    if (!is_array($ids)) {
        return array();
    }

    return hsb_relation_clean_product_ids(
        array_filter(
            $ids,
            static function ($id) use ($product_id) {
                return absint($id) !== $product_id;
            }
        )
    );
}


/**
 * Read stored complementary / related product IDs.
 *
 * These relations come only from explicit product data:
 * Required Products, WooCommerce Cross-sells and Upsells.
 */
function hsb_get_indexed_related_product_ids(
    $product_id
) {
    $product_id = absint($product_id);

    if (!$product_id) {
        return array();
    }

    $ids = get_post_meta(
        $product_id,
        '_hsb_related_product_ids',
        true
    );

    if (!is_array($ids)) {
        return array();
    }

    return hsb_relation_clean_product_ids(
        array_filter(
            $ids,
            static function ($id) use ($product_id) {
                return absint($id) !== $product_id;
            }
        )
    );
}


/**
 * Schedule one background rebuild.
 *
 * Repeated product saves never create thousands of cron jobs.
 */
function hsb_relationship_index_schedule_rebuild(
    $delay = HSB_RELATIONSHIP_INDEX_SETTLE_SECONDS
) {
    $delay = max(
        30,
        absint($delay)
    );

    if (
        wp_next_scheduled(
            'hsb_relationship_index_cron_rebuild'
        )
    ) {
        return;
    }

    wp_schedule_single_event(
        time() + $delay,
        'hsb_relationship_index_cron_rebuild'
    );
}


/**
 * Mark the stored relationship index as stale.
 */
function hsb_relationship_index_mark_dirty(
    $product_id = 0
) {
    $product_id = absint($product_id);

    if (
        $product_id &&
        'product' !== get_post_type($product_id)
    ) {
        return;
    }

    update_option(
        'hsb_relationship_index_dirty',
        1,
        false
    );

    update_option(
        'hsb_relationship_index_last_catalog_change',
        time(),
        false
    );

    hsb_relationship_index_schedule_rebuild();
}


/**
 * Product saves/imports only mark the index dirty.
 *
 * The expensive rebuild does NOT happen inside the save request.
 */
add_action(
    'save_post_product',
    'hsb_relationship_index_product_saved',
    50,
    3
);

function hsb_relationship_index_product_saved(
    $post_id,
    $post,
    $update
) {
    if (
        wp_is_post_revision($post_id) ||
        wp_is_post_autosave($post_id)
    ) {
        return;
    }

    if (
        defined('DOING_AUTOSAVE') &&
        DOING_AUTOSAVE
    ) {
        return;
    }

    hsb_relationship_index_mark_dirty(
        $post_id
    );
}


/**
 * After deployment, an old/missing index is automatically
 * scheduled for background creation.
 *
 * Nothing heavy runs during this normal page request.
 */
add_action(
    'init',
    'hsb_relationship_index_bootstrap_schedule',
    30
);

function hsb_relationship_index_bootstrap_schedule() {
    $current_version = absint(
        get_option(
            'hsb_relationship_index_version',
            0
        )
    );

    $dirty = absint(
        get_option(
            'hsb_relationship_index_dirty',
            0
        )
    );

    if (
        $current_version <
            HSB_RELATIONSHIP_INDEX_VERSION ||
        $dirty
    ) {
        hsb_relationship_index_schedule_rebuild();
    }
}


/**
 * Perform the rebuild outside the storefront request.
 */
add_action(
    'hsb_relationship_index_cron_rebuild',
    'hsb_relationship_index_cron_rebuild'
);

function hsb_relationship_index_cron_rebuild() {
    /*
     * Prevent two rebuilds from running together.
     */
    if (
        get_transient(
            'hsb_relationship_index_rebuild_lock'
        )
    ) {
        hsb_relationship_index_schedule_rebuild(
            60
        );

        return;
    }

    /*
     * If an import/edit happened recently, wait until
     * the catalog becomes quiet.
     */
    $last_change = absint(
        get_option(
            'hsb_relationship_index_last_catalog_change',
            0
        )
    );

    if (
        $last_change &&
        (
            time() - $last_change
        ) <
        HSB_RELATIONSHIP_INDEX_SETTLE_SECONDS
    ) {
        hsb_relationship_index_schedule_rebuild(
            HSB_RELATIONSHIP_INDEX_SETTLE_SECONDS
        );

        return;
    }

    set_transient(
        'hsb_relationship_index_rebuild_lock',
        1,
        15 * MINUTE_IN_SECONDS
    );

    try {
        $result =
            hsb_rebuild_product_relationship_index();

        update_option(
            'hsb_relationship_index_dirty',
            0,
            false
        );

        update_option(
            'hsb_relationship_index_last_result',
            $result,
            false
        );
    } finally {
        delete_transient(
            'hsb_relationship_index_rebuild_lock'
        );
    }
}


/**
 * Rebuild the complete relationship index in one catalog pass.
 *
 * This function must never run automatically during a normal
 * storefront page request.
 */
function hsb_rebuild_product_relationship_index() {
    global $wpdb;

    $started_at = microtime(true);

    /*
     * Fetch the whole published catalog in one database query.
     *
     * Only Part Number and Equivalent Codes are required for
     * relationship discovery.
     */
    $rows = $wpdb->get_results(
        "
        SELECT
            p.ID,
            MAX(
                CASE
                    WHEN pm.meta_key = '_mpn_part_number'
                    THEN pm.meta_value
                    ELSE NULL
                END
            ) AS part_number,
            MAX(
                CASE
                    WHEN pm.meta_key = '_equivalent_codes'
                    THEN pm.meta_value
                    ELSE NULL
                END
            ) AS equivalent_codes,
            MAX(
                CASE
                    WHEN pm.meta_key = '_hsb_required_product_ids'
                    THEN pm.meta_value
                    ELSE NULL
                END
            ) AS required_product_ids,
            MAX(
                CASE
                    WHEN pm.meta_key = '_crosssell_ids'
                    THEN pm.meta_value
                    ELSE NULL
                END
            ) AS crosssell_ids,
            MAX(
                CASE
                    WHEN pm.meta_key = '_upsell_ids'
                    THEN pm.meta_value
                    ELSE NULL
                END
            ) AS upsell_ids
        FROM {$wpdb->posts} p
        LEFT JOIN {$wpdb->postmeta} pm
            ON pm.post_id = p.ID
           AND pm.meta_key IN (
               '_mpn_part_number',
               '_equivalent_codes',
               '_hsb_required_product_ids',
               '_crosssell_ids',
               '_upsell_ids'
           )
        WHERE p.post_type = 'product'
          AND p.post_status = 'publish'
        GROUP BY p.ID
        ORDER BY p.ID ASC
        "
    );

    if (!is_array($rows)) {
        $rows = array();
    }

    /*
     * Product data keyed by product ID.
     */
    $products = array();

    /*
     * Normalized Part Number => product IDs.
     */
    $part_index = array();

    /*
     * Normalized Equivalent Code =>
     * products that explicitly declared that code.
     */
    $reverse_equivalent_index = array();

    foreach ($rows as $row) {
        $product_id = absint($row->ID);

        if (!$product_id) {
            continue;
        }

        $part_number =
            hsb_relation_normalize_code(
                $row->part_number
            );

        $equivalent_codes =
            hsb_relation_parse_equivalent_codes(
                $row->equivalent_codes
            );

        $required_product_ids =
            hsb_relation_parse_product_ids(
                $row->required_product_ids
            );

        $crosssell_ids =
            hsb_relation_parse_product_ids(
                $row->crosssell_ids
            );

        $upsell_ids =
            hsb_relation_parse_product_ids(
                $row->upsell_ids
            );

        $products[$product_id] = array(
            'part_number'         => $part_number,
            'equivalent_codes'    => $equivalent_codes,
            'required_product_ids'=> $required_product_ids,
            'crosssell_ids'       => $crosssell_ids,
            'upsell_ids'          => $upsell_ids,
        );

        if ('' !== $part_number) {
            if (!isset($part_index[$part_number])) {
                $part_index[$part_number] = array();
            }

            $part_index[$part_number][] =
                $product_id;
        }

        foreach ($equivalent_codes as $code) {
            if (
                !isset(
                    $reverse_equivalent_index[$code]
                )
            ) {
                $reverse_equivalent_index[$code] =
                    array();
            }

            $reverse_equivalent_index[$code][] =
                $product_id;
        }
    }

    $changed_family     = 0;
    $changed_equivalent = 0;
    $changed_related    = 0;
    $family_links       = 0;
    $equivalent_links   = 0;
    $related_links      = 0;

    foreach ($products as $product_id => $record) {
        $part_number =
            $record['part_number'];

        /*
         * FAMILY
         *
         * Every published SKU having the exact same
         * normalized Part Number belongs to one family.
         *
         * The current product is intentionally included.
         */
        $family_ids = array($product_id);

        if (
            '' !== $part_number &&
            isset($part_index[$part_number])
        ) {
            $family_ids =
                $part_index[$part_number];
        }

        $family_ids =
            hsb_relation_clean_product_ids(
                $family_ids
            );

        /*
         * EQUIVALENTS - direct direction.
         *
         * For every code explicitly listed by this product,
         * connect products whose actual Part Number equals
         * that code.
         */
        $equivalent_ids = array();

        foreach (
            $record['equivalent_codes']
            as $equivalent_code
        ) {
            if (
                isset(
                    $part_index[$equivalent_code]
                )
            ) {
                $equivalent_ids = array_merge(
                    $equivalent_ids,
                    $part_index[$equivalent_code]
                );
            }
        }

        /*
         * EQUIVALENTS - reverse direct direction.
         *
         * If another product explicitly lists the current
         * product's Part Number, treat that declaration as
         * a direct relationship too.
         *
         * No second-level or recursive expansion occurs.
         */
        if (
            '' !== $part_number &&
            isset(
                $reverse_equivalent_index[
                    $part_number
                ]
            )
        ) {
            $equivalent_ids = array_merge(
                $equivalent_ids,
                $reverse_equivalent_index[
                    $part_number
                ]
            );
        }

        /*
         * Same-Part-Number products belong to Family,
         * not to the Equivalent list.
         */
        $family_lookup =
            array_fill_keys(
                $family_ids,
                true
            );

        $equivalent_ids =
            array_values(
                array_filter(
                    $equivalent_ids,
                    static function ($id) use (
                        $product_id,
                        $family_lookup
                    ) {
                        $id = absint($id);

                        return
                            $id &&
                            $id !== $product_id &&
                            !isset(
                                $family_lookup[$id]
                            );
                    }
                )
            );

        $equivalent_ids =
            hsb_relation_clean_product_ids(
                $equivalent_ids
            );

        /*
         * RELATED / COMPLEMENTARY
         *
         * No category guessing and no random products.
         *
         * Sources:
         * 1. HyperSanati Required Products
         * 2. WooCommerce Cross-sells
         * 3. WooCommerce Upsells
         *
         * Only currently published catalog products survive.
         */
        $related_ids = array_merge(
            $record['required_product_ids'],
            $record['crosssell_ids'],
            $record['upsell_ids']
        );

        $related_ids = array_values(
            array_filter(
                hsb_relation_clean_product_ids(
                    $related_ids
                ),
                static function ($id) use (
                    $product_id,
                    $products
                ) {
                    return
                        $id !== $product_id &&
                        isset($products[$id]);
                }
            )
        );

        $old_family = get_post_meta(
            $product_id,
            '_hsb_family_product_ids',
            true
        );

        $old_equivalent = get_post_meta(
            $product_id,
            '_hsb_equivalent_product_ids',
            true
        );

        $old_related = get_post_meta(
            $product_id,
            '_hsb_related_product_ids',
            true
        );

        $old_family =
            hsb_relation_clean_product_ids(
                is_array($old_family)
                    ? $old_family
                    : array()
            );

        $old_equivalent =
            hsb_relation_clean_product_ids(
                is_array($old_equivalent)
                    ? $old_equivalent
                    : array()
            );

        $old_related =
            hsb_relation_clean_product_ids(
                is_array($old_related)
                    ? $old_related
                    : array()
            );

        if ($old_family !== $family_ids) {
            update_post_meta(
                $product_id,
                '_hsb_family_product_ids',
                $family_ids
            );

            $changed_family++;
        }

        if (
            $old_equivalent !==
            $equivalent_ids
        ) {
            update_post_meta(
                $product_id,
                '_hsb_equivalent_product_ids',
                $equivalent_ids
            );

            $changed_equivalent++;
        }

        if ($old_related !== $related_ids) {
            update_post_meta(
                $product_id,
                '_hsb_related_product_ids',
                $related_ids
            );

            $changed_related++;
        }

        update_post_meta(
            $product_id,
            '_hsb_relationship_index_version',
            HSB_RELATIONSHIP_INDEX_VERSION
        );

        $family_links += max(
            0,
            count($family_ids) - 1
        );

        $equivalent_links +=
            count($equivalent_ids);

        $related_links +=
            count($related_ids);
    }

    update_option(
        'hsb_relationship_index_version',
        HSB_RELATIONSHIP_INDEX_VERSION,
        false
    );

    update_option(
        'hsb_relationship_index_built_at',
        time(),
        false
    );

    update_option(
        'hsb_relationship_index_catalog_size',
        count($products),
        false
    );

    update_option(
        'hsb_relationship_index_dirty',
        0,
        false
    );

    return array(
        'catalog_size'       => count($products),
        'part_keys'          => count($part_index),
        'family_links'       => $family_links,
        'equivalent_links'   => $equivalent_links,
        'related_links'      => $related_links,
        'changed_family'     => $changed_family,
        'changed_equivalent' => $changed_equivalent,
        'changed_related'    => $changed_related,
        'elapsed_seconds'    => round(
            microtime(true) - $started_at,
            4
        ),
    );
}

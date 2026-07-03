<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<section class="single-article-hero">
  <div class="container">
    <div class="single-article-hero__inner">
      
      <!-- TITLE -->
      <h1 class="single-article-title">
        <?php the_title(); ?>
      </h1>

      <!-- BREADCRUMB -->
      <nav class="single-article-breadcrumb" aria-label="breadcrumb">
        <a href="<?php echo home_url(); ?>">خانه</a>
        <span>/</span>
        <a href="<?php echo get_permalink(get_option('page_for_posts')); ?>">مجله</a>
        <span>/</span>

        <?php
        $categories = get_the_category();
        if (!empty($categories)) :
            $cat = $categories[0];
        ?>
            <a href="<?php echo get_category_link($cat->term_id); ?>">
                <?php echo esc_html($cat->name); ?>
            </a>
            <span>/</span>
        <?php endif; ?>

        <span class="current">
          <?php the_title(); ?>
        </span>
      </nav>

      <!-- META -->
      <div class="single-article-meta">
        <span class="single-article-author">
          نویسنده: <?php the_author(); ?>
        </span>

        <span class="single-article-separator">|</span>

        <span class="single-article-date">
          تاریخ انتشار: <?php echo get_the_date('Y/m/d'); ?>
        </span>
      </div>

      <!-- FEATURED IMAGE -->
      <div class="single-article-featured-image">
        <?php if (has_post_thumbnail()) : ?>
          <?php the_post_thumbnail('large'); ?>
        <?php endif; ?>
      </div>

      <!-- EXCERPT -->
        <?php
          $seo_summary = get_post_meta(get_the_ID(), '_seo_summary', true);

          if (!empty($seo_summary)) : ?>
              <p class="single-article-excerpt">
                  <?php echo esc_html($seo_summary); ?>
              </p>
          <?php endif; ?>


    </div>
  </div>
</section>

<?php endwhile; endif; ?>


<section class="single-article-content-section">
  <div class="container">
    <div class="single-article-layout">

      <!-- Sidebar Product Card -->
<?php
$discount_products = new WP_Query([
    'post_type' => 'product',
    'posts_per_page' => 5,
    'meta_key' => '_is_discount_featured',
    'meta_value' => 'yes'
]);
?>

<aside class="single-article-sidebar">
        <div class="discount-slider" id="discountSlider">

        <?php if ($discount_products->have_posts()) : ?>
            <?php while ($discount_products->have_posts()) : $discount_products->the_post(); 
                $product = wc_get_product(get_the_ID());
            ?>

                <a href="<?php the_permalink(); ?>" class="discount-item">

                    <div class="discount-product-card">

                        <div class="discount-product-label">
                            تخفیف ویژه هفته (تبلیغ)
                        </div>

                        <div class="discount-product-frame">
                            <?php the_post_thumbnail('medium'); ?>
                        </div>

                        <p class="discount-product-desc">
                            <?php the_title(); ?>
                        </p>

                        <div class="discount-product-old-price-row">

                            <span class="discount-product-old-price">
                                <?php echo wc_price($product->get_regular_price()); ?>
                            </span>

                            <span class="discount-product-badge">
                                <?php
                                if ($product->get_regular_price() && $product->get_sale_price()) {
                                    echo round((($product->get_regular_price() - $product->get_sale_price()) / $product->get_regular_price()) * 100) . '%';
                                }
                                ?>
                            </span>

                        </div>

                        <div class="discount-product-new-price">
                            <?php echo wc_price($product->get_sale_price()); ?>
                        </div>

                    </div>

                </a>

            <?php endwhile; ?>
        <?php endif; wp_reset_postdata(); ?>

        </div>
      </aside>

      <!-- Main Content -->
      <main class="single-article-main">

<?php

// ❌ مهم: RAW content بگیر، نه filtered
$content = get_post_field('post_content', get_the_ID());

// DEBUG: Show raw content length
echo '<!-- DEBUG: Content length: ' . strlen($content) . ' -->';

$toc = [];
$index = 1;

/* =========================
   ساخت TOC + اضافه کردن ID
========================= */
$content = preg_replace_callback(
    '/<h([2-6])([^>]*)>(.*?)<\/h\1>/is',
    function ($matches) use (&$toc, &$index) {

        $title = strip_tags($matches[3]);
        $id = 'section-' . $index;

        $toc[] = [
            'id' => $id,
            'title' => $title,
            'level' => $matches[1]
        ];

        $index++;

        return '<h' . $matches[1] . ' id="' . $id . '"' . $matches[2] . '>' . $matches[3] . '</h' . $matches[1] . '>';
    },
    $content
);

// DEBUG: Show TOC count
echo '<!-- DEBUG: TOC items found: ' . count($toc) . ' -->';
if (!empty($toc)) {
    echo '<!-- DEBUG: First TOC item: ' . htmlspecialchars($toc[0]['title']) . ' -->';
}

?>

<!-- =========================
     TOC (حتماً بالا باشد)
========================= -->
<?php if (!empty($toc)) : ?>
    <div class="article-toc-box">
        <h2 class="article-toc-title">فهرست مطالب مقاله</h2>

        <ul class="article-toc-list">
            <?php foreach ($toc as $item) : ?>
                <li>
                    <a href="#<?php echo $item['id']; ?>">
                        <?php echo $item['title']; ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>


<!-- =========================
     CONTENT
========================= -->
<article class="single-article-body">

    <?php
    // حالا بعد از تغییر h2 ها، فیلتر وردپرس رو اعمال کن
    echo apply_filters('the_content', $content);
    ?>

</article>

      </main>

    </div>
  </div>
</section>
<?php get_footer(); ?>
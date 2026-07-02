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
      <p class="single-article-excerpt">
        <?php echo get_the_excerpt(); ?>
      </p>

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

      <!-- Main Content -->
      <?php
$post_content = apply_filters('the_content', get_post_field('post_content', get_the_ID()));

libxml_use_internal_errors(true);

$dom = new DOMDocument();
$dom->loadHTML(mb_convert_encoding($post_content, 'HTML-ENTITIES', 'UTF-8'));

$headings = $dom->getElementsByTagName('h2');

$toc = [];
$index = 1;

// برای ذخیره HTML جدید
$new_content = $post_content;

if ($headings->length > 0) {

    foreach ($headings as $heading) {

        $text = $heading->nodeValue;

        $id = 'section-' . $index;

        // اضافه کردن ID به heading
        $heading->setAttribute('id', $id);

        $toc[] = [
            'id' => $id,
            'title' => $text
        ];

        $index++;
    }

    // ذخیره HTML اصلاح شده
    $new_content = $dom->saveHTML();
}
?>

<!-- TOC -->
<?php if (!empty($toc)) : ?>
<div class="article-toc-box">
  <h2 class="article-toc-title">فهرست مطالب مقاله حاضر:</h2>

  <ul class="article-toc-list">
    <?php foreach ($toc as $item) : ?>
      <li>
        <a href="#<?php echo esc_attr($item['id']); ?>">
          <?php echo esc_html($item['title']); ?>
        </a>
      </li>
    <?php endforeach; ?>
  </ul>
</div>
<?php endif; ?>

<!-- CONTENT -->
<article class="single-article-body">
  <?php echo $new_content; ?>
  
</article>
    </div>
  </div>
</section>




<?php get_footer(); ?>
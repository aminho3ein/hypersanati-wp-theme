<?php get_header(); ?>

<section class="hs404-page">
  <div class="hs404-bg"></div>

  <div class="hs404-stage">
    <div class="hs404-bearings" aria-hidden="true">
      <div class="hs404-bearing hs404-bearing--one">
        <div class="hs404-bearing-track"></div>
        <?php for ( $i = 0; $i < 12; $i++ ) : ?>
          <span class="hs404-ball"></span>
        <?php endfor; ?>
      </div>

      <div class="hs404-bearing hs404-bearing--two">
        <div class="hs404-bearing-track"></div>
        <?php for ( $i = 0; $i < 12; $i++ ) : ?>
          <span class="hs404-ball"></span>
        <?php endfor; ?>
      </div>

      <div class="hs404-core">
        <div class="hs404-core-inner">
          <span class="hs404-number">404</span>
        </div>
      </div>
    </div>

    <div class="hs404-content">
      <h1 class="hs404-title">صفحه موردنظر پیدا نشد</h1>
      <p class="hs404-text">
        به نظر می‌رسد این مسیر از مسیرهای اصلی سایت خارج شده است.
        می‌توانید به صفحه اصلی برگردید یا جست‌وجوی دوباره انجام دهید.
      </p>

      <div class="hs404-actions">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary hs404-btn">بازگشت به صفحه اصلی</a>
        <a href="javascript:history.back()" class="btn hs404-btn hs404-btn-secondary">صفحه قبل</a>
      </div>
    </div>
  </div>
</section>
<script src="./js/404.js"></script>
<?php get_footer(); ?>
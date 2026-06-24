document.addEventListener("DOMContentLoaded", function () {
  const productCard = document.getElementById("featuredProductCard");
  const articleSection = document.querySelector(".single-article-content-section");
  const progressRight = document.querySelector(".featured-product-progress--right");
  const progressLeft = document.querySelector(".featured-product-progress--left");

  if (!productCard || !articleSection || !progressRight || !progressLeft) return;

  function isDesktopLike() {
    return window.innerWidth >= 768;
  }

  let stickyActivationTimeout;

  function updateStickyProgress() {
    if (!isDesktopLike()) {
      productCard.classList.remove("is-sticky", "is-sticky-active", "is-scroll-complete");
      progressRight.style.height = "0";
      progressLeft.style.height = "0";
      return;
    }

    const cardRect = productCard.getBoundingClientRect();
    const sectionRect = articleSection.getBoundingClientRect();

    const stickyTop = 32;
    const stickyStarted = cardRect.top <= stickyTop && sectionRect.bottom > cardRect.height + stickyTop;
    const sectionVisible = sectionRect.top < window.innerHeight && sectionRect.bottom > 0;

    if (stickyStarted && sectionVisible) {
      if (!productCard.classList.contains("is-sticky")) {
        productCard.classList.add("is-sticky");
        productCard.classList.add("is-sticky-active");

        clearTimeout(stickyActivationTimeout);
        stickyActivationTimeout = setTimeout(() => {
          productCard.classList.remove("is-sticky-active");
        }, 1000);
      }
    } else {
      productCard.classList.remove("is-sticky");
      productCard.classList.remove("is-sticky-active");
    }

    const scrollStart = articleSection.offsetTop;
    const scrollEnd = articleSection.offsetTop + articleSection.offsetHeight - window.innerHeight;
    const currentScroll = window.scrollY;

    let progress = 0;

    if (scrollEnd > scrollStart) {
      progress = ((currentScroll - scrollStart) / (scrollEnd - scrollStart)) * 100;
    }

    progress = Math.max(0, Math.min(progress, 100));

    progressRight.style.height = progress + "%";
    progressLeft.style.height = progress + "%";

    if (progress >= 99.5) {
      productCard.classList.add("is-scroll-complete");
    } else {
      productCard.classList.remove("is-scroll-complete");
    }
  }

  updateStickyProgress();
  window.addEventListener("scroll", updateStickyProgress, { passive: true });
  window.addEventListener("resize", updateStickyProgress);
});
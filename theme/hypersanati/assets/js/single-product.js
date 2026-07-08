document.addEventListener('DOMContentLoaded', function () {
    // 1. Tab Navigation
    const tabButtons = document.querySelectorAll('.product-meta-nav button');
    const tabPanels = document.querySelectorAll('.product-meta-content .tab-panel');

    tabButtons.forEach(button => {
        button.addEventListener('click', function () {
            const targetTab = this.getAttribute('data-tab');

            // Remove active class from all buttons and panels
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabPanels.forEach(panel => panel.classList.remove('active'));

            // Add active class to clicked button and corresponding panel
            this.classList.add('active');
            document.getElementById(targetTab).classList.add('active');
        });
    });

    // 2. Image Gallery
    const galleryFrames = document.querySelectorAll('.product-image-gallery-frame');
    const mainImage = document.querySelector('.single-image-frame img');
    const prevBtn = document.getElementById('prev-image');
    const nextBtn = document.getElementById('next-image');
    let currentImageIndex = 0;

    function updateMainImage() {
        galleryFrames.forEach((frame, index) => {
            frame.classList.toggle('active', index === currentImageIndex);
        });

        const activeFrame = galleryFrames[currentImageIndex];
        const img = activeFrame?.querySelector('img');
        if (img && mainImage) {
            mainImage.src = img.src;
            mainImage.srcset = img.srcset;
            mainImage.alt = img.alt;
        }
    }

    galleryFrames.forEach((frame, index) => {
        frame.addEventListener('click', function () {
            currentImageIndex = index;
            updateMainImage();
        });
    });

    if (prevBtn) {
        prevBtn.addEventListener('click', function () {
            currentImageIndex = (currentImageIndex - 1 + galleryFrames.length) % galleryFrames.length;
            updateMainImage();
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', function () {
            currentImageIndex = (currentImageIndex + 1) % galleryFrames.length;
            updateMainImage();
        });
    }

    // 3. Star Rating for Reviews
    const ratingContainers = document.querySelectorAll('.fontawesome-5-star-rate');

    ratingContainers.forEach(container => {
        const labels = container.querySelectorAll('label');
        const inputs = container.querySelectorAll('input');

        labels.forEach((label, index) => {
            label.addEventListener('mouseenter', function () {
                labels.forEach((l, i) => {
                    const icon = l.querySelector('i');
                    if (icon) {
                        if (i >= index) {
                            icon.classList.remove('fa-regular');
                            icon.classList.add('fa-solid');
                            icon.style.color = '#fbbf24'; // gold
                        } else {
                            icon.classList.remove('fa-solid');
                            icon.classList.add('fa-regular');
                            icon.style.color = '#d1d5db'; // gray
                        }
                    }
                });
            });

            label.addEventListener('mouseleave', function () {
                const checkedInput = container.querySelector('input:checked');
                let checkedIdx = -1;

                if (checkedInput) {
                    checkedIdx = Array.from(inputs).indexOf(checkedInput);
                }

                labels.forEach((l, i) => {
                    const icon = l.querySelector('i');
                    if (icon) {
                        if (i >= checkedIdx && checkedIdx !== -1) {
                            icon.classList.remove('fa-regular');
                            icon.classList.add('fa-solid');
                            icon.style.color = '#fbbf24';
                        } else {
                            icon.classList.remove('fa-solid');
                            icon.classList.add('fa-regular');
                            icon.style.color = '#d1d5db';
                        }
                    }
                });
            });
        });
    });

    // 4. Related & Similar Products Scroll
    const scrollRightBtns = document.querySelectorAll('.scrollRightBtn');
    const scrollLeftBtns = document.querySelectorAll('.scrollLeftBtn');

    scrollRightBtns.forEach((btn, index) => {
        const container = btn.closest('.relevent-sec')?.querySelector('.products-scroolbar');
        if (container) {
            btn.addEventListener('click', () => {
                container.scrollBy({
                    left: 350,
                    behavior: 'smooth'
                });
            });
        }
    });

    scrollLeftBtns.forEach((btn, index) => {
        const container = btn.closest('.relevent-sec')?.querySelector('.products-scroolbar');
        if (container) {
            btn.addEventListener('click', () => {
                container.scrollBy({
                    left: -350,
                    behavior: 'smooth'
                });
            });
        }
    });

    // 5. Review Like/Dislike (placeholder - requires backend AJAX for persistence)
    const likeButtons = document.querySelectorAll('.review-like, .same-question');
    const dislikeButtons = document.querySelectorAll('.review-dislike');

    likeButtons.forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const countEl = this.querySelector('span');
            if (countEl) {
                let currentCount = parseInt(countEl.textContent) || 0;
                countEl.textContent = currentCount + 1;
                this.disabled = true;
            }
        });
    });

    dislikeButtons.forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const countEl = this.querySelector('span');
            if (countEl) {
                let currentCount = parseInt(countEl.textContent) || 0;
                countEl.textContent = currentCount + 1;
                this.disabled = true;
            }
        });
    });
});



document.addEventListener("DOMContentLoaded", function () {
  const descContents = document.querySelectorAll("#desc .desc-content");

  descContents.forEach(function (content) {
    const button = content.parentElement.querySelector(".desc-read-more");

    if (!button) return;

    if (content.scrollHeight <= 300) {
      button.style.display = "none";
      content.classList.remove("is-collapsed");
      return;
    }

    button.addEventListener("click", function () {
      const isExpanded = content.classList.toggle("is-expanded");

      content.classList.toggle("is-collapsed", !isExpanded);
      button.setAttribute("aria-expanded", isExpanded ? "true" : "false");
      button.textContent = isExpanded ? "نمایش کمتر" : "مشاهده بیشتر";
    });
  });
});


document.addEventListener("DOMContentLoaded", function () {
  document.addEventListener("click", function (event) {
    const button = event.target.closest("[data-sp-scroll]");

    if (!button) return;

    const section = button.closest(".sp-products-section");
    if (!section) return;

    const row = section.querySelector(".sp-products-row");
    if (!row) return;

    const action = button.getAttribute("data-sp-scroll");
    const amount = row.clientWidth * 0.85;
    const isRtl = window.getComputedStyle(row).direction === "rtl";

    let left = action === "next" ? amount : -amount;

    if (isRtl) {
      left = action === "next" ? -amount : amount;
    }

    row.scrollBy({
      left: left,
      behavior: "smooth",
    });
  });
});
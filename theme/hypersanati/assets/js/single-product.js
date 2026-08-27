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
    let currentImageIndex = 0;

    function getGalleryFrames() {
        return Array.from(
            document.querySelectorAll(
                '.product-image-gallery-frame'
            )
        );
    }

    function updateMainImage(index) {
        const galleryFrames = getGalleryFrames();
        const mainImage = document.querySelector(
            '.single-image-frame img'
        );

        if (!galleryFrames.length || !mainImage) {
            return;
        }

        if (index < 0) {
            index = galleryFrames.length - 1;
        }

        if (index >= galleryFrames.length) {
            index = 0;
        }

        currentImageIndex = index;

        galleryFrames.forEach(function (frame, frameIndex) {
            frame.classList.toggle(
                'active',
                frameIndex === currentImageIndex
            );
        });

        const activeFrame =
            galleryFrames[currentImageIndex];

        const thumb =
            activeFrame.querySelector('img');

        if (!thumb) {
            return;
        }

        const fullSrc =
            activeFrame.dataset.fullSrc ||
            thumb.currentSrc ||
            thumb.src;

        const fullSrcset =
            activeFrame.dataset.fullSrcset ||
            '';

        if (fullSrc) {
            mainImage.src = fullSrc;
        }

        if (fullSrcset) {
            mainImage.srcset = fullSrcset;
        } else {
            mainImage.removeAttribute('srcset');
        }

        mainImage.alt =
            activeFrame.dataset.fullAlt ||
            thumb.alt ||
            '';
    }

    document.addEventListener(
        'click',
        function (event) {

            const frame = event.target.closest(
                '.product-image-gallery-frame'
            );

            if (frame) {
                const frames = getGalleryFrames();
                const index = frames.indexOf(frame);

                if (index >= 0) {
                    updateMainImage(index);
                }

                return;
            }

            const prevButton =
                event.target.closest('#prev-image');

            if (prevButton) {
                const frames = getGalleryFrames();

                if (frames.length) {
                    updateMainImage(
                        currentImageIndex - 1
                    );
                }

                return;
            }

            const nextButton =
                event.target.closest('#next-image');

            if (nextButton) {
                const frames = getGalleryFrames();

                if (frames.length) {
                    updateMainImage(
                        currentImageIndex + 1
                    );
                }
            }
        }
    );

    window.hsbProductGalleryReset = function () {
        currentImageIndex = 0;

        const frames = getGalleryFrames();

        if (frames.length) {
            updateMainImage(0);
        }
    };


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

/* HS PREINVOICE PRODUCT FORM */
document.addEventListener("DOMContentLoaded", function () {

  /*
   * Quantity stepper
   */
  document.addEventListener("click", function (event) {
    const button = event.target.closest(".product-quantity-btn");

    if (!button) return;

    /*
     * Mobile floating quantity has its own handler.
     * Prevent double +/- processing.
     */
    if (
      button.closest(
        "[data-hsb-mobile-preinvoice]"
      )
    ) {
      return;
    }

    const wrapper = button.closest(".product-quantity-control");
    if (!wrapper) return;

    const input = wrapper.querySelector(".product-quantity-input");
    if (!input) return;

    const min = parseInt(input.min || "1", 10);
    const step = parseInt(input.step || "1", 10);

    let value = parseInt(input.value || min, 10);

    if (Number.isNaN(value)) {
      value = min;
    }

    if (button.classList.contains("qty-plus")) {
      value += step;
    }

    if (button.classList.contains("qty-minus")) {
      value -= step;
    }

    if (value < min) {
      value = min;
    }

    input.value = value;
  });


  /*
   * Sanitize manual quantity input.
   */
  document.addEventListener("change", function (event) {
    if (!event.target.classList.contains("product-quantity-input")) {
      return;
    }

    const input = event.target;
    const min = parseInt(input.min || "1", 10);

    let value = parseInt(input.value || min, 10);

    if (Number.isNaN(value) || value < min) {
      value = min;
    }

    input.value = value;
  });


  /*
   * Add to preinvoice.
   * This is intentionally independent from:
   * - WooCommerce product price
   * - WooCommerce stock status
   * - WooCommerce cart purchasability
   */
  document.addEventListener("submit", function (event) {
    const form = event.target.closest(".product-preinvoice-form");

    if (!form) return;

    event.preventDefault();

    const button = form.querySelector(".preinvoice-add-button");
    const message = form.querySelector(".preinvoice-add-message");

    if (!button || button.disabled) {
      return;
    }

    const productId = form.querySelector('[name="product_id"]')?.value;
    const quantity =
      form.querySelector('[name="quantity"]')?.value || "1";

    const ajaxUrl = form.dataset.ajaxUrl;
    const nonce = form.dataset.nonce;

    if (!productId || !ajaxUrl || !nonce) {
      if (message) {
        message.textContent = "اطلاعات پیش‌فاکتور کامل نیست.";
        message.className = "preinvoice-add-message is-error";
      }
      return;
    }

    const body = new FormData();

    body.append("action", "hsb_preinvoice_add_item");
    body.append("nonce", nonce);
    body.append("product_id", productId);
    body.append("quantity", quantity);

    const oldText = button.textContent;

    button.disabled = true;
    button.classList.add("loading");
    button.textContent = "در حال افزودن...";

    if (message) {
      message.textContent = "";
      message.className = "preinvoice-add-message";
    }

    fetch(ajaxUrl, {
      method: "POST",
      credentials: "same-origin",
      body: body,
    })
      .then(function (response) {
        return response.json();
      })
      .then(function (response) {
        if (!response.success) {
          throw new Error(
            response.data && response.data.message
              ? response.data.message
              : "امکان افزودن محصول به پیش‌فاکتور وجود ندارد."
          );
        }

        if (message) {
          message.textContent =
            response.data.message ||
            "محصول به پیش‌فاکتور اضافه شد.";

          message.className =
            "preinvoice-add-message is-success";
        }

        document.dispatchEvent(
          new CustomEvent("hsb_preinvoice_updated", {
            detail: response.data,
          })
        );

        /*
         * Update header preinvoice badge immediately.
         */
        if (
          response.data &&
          typeof response.data.total_quantity !== "undefined"
        ) {
          const totalQuantity =
            parseInt(response.data.total_quantity, 10) || 0;

          document
            .querySelectorAll(".preinvoice-count-badge")
            .forEach(function (badge) {

              badge.textContent = totalQuantity;

              badge.classList.toggle(
                "is-empty",
                totalQuantity < 1
              );

              badge.setAttribute(
                "aria-label",
                totalQuantity + " قلم در پیش‌فاکتور"
              );
            });
        }
      })
      .catch(function (error) {
        if (message) {
          message.textContent = error.message;
          message.className =
            "preinvoice-add-message is-error";
        }
      })
      .finally(function () {
        button.disabled = false;
        button.classList.remove("loading");
        button.textContent = oldText;
      });
  });
});

/* HSB MOBILE FLOATING PREINVOICE */
document.addEventListener(
  "DOMContentLoaded",
  function () {

    const bar =
      document.querySelector(
        "[data-hsb-mobile-preinvoice]"
      );

    const form =
      document.querySelector(
        ".hsb-sp-preinvoice-form"
      );

    if (!bar || !form) {
      return;
    }

    const sourceButton =
      form.querySelector(
        ".preinvoice-add-button"
      );

    const sourceQuantity =
      form.querySelector(
        ".product-quantity-input"
      );

    const sourceMessage =
      form.querySelector(
        ".preinvoice-add-message"
      );

    const mobileQuantity =
      bar.querySelector(
        ".hsb-sp-mobile-quantity"
      );

    const mobileButton =
      bar.querySelector(
        ".hsb-sp-mobile-add"
      );

    const mobileMessage =
      bar.querySelector(
        ".hsb-sp-mobile-preinvoice__message"
      );

    const footer =
      document.querySelector(
        ".site-footer"
      );

    const mobileMedia =
      window.matchMedia(
        "(max-width: 820px)"
      );

    if (
      !sourceButton ||
      !sourceQuantity ||
      !mobileQuantity ||
      !mobileButton
    ) {
      return;
    }


    /*
     * Initial HTML uses hidden to prevent a flash.
     * From this point visibility is animated by CSS.
     */
    bar.hidden = false;


    function cleanQuantity(value) {

      const min =
        parseInt(
          sourceQuantity.min || "1",
          10
        ) || 1;

      let quantity =
        parseInt(value || min, 10);

      if (
        Number.isNaN(quantity) ||
        quantity < min
      ) {
        quantity = min;
      }

      return quantity;
    }


    function syncMobileQuantity() {

      mobileQuantity.value =
        cleanQuantity(
          sourceQuantity.value
        );
    }


    function syncSourceQuantity() {

      sourceQuantity.value =
        cleanQuantity(
          mobileQuantity.value
        );
    }


    /*
     * Floating +/- buttons own one shared quantity value.
     * No delayed copy and no second stepper handler.
     */
    bar.addEventListener(
      "click",
      function (event) {

        const quantityButton =
          event.target.closest(
            ".product-quantity-btn"
          );

        if (!quantityButton) {
          return;
        }

        const min =
          parseInt(
            sourceQuantity.min || "1",
            10
          ) || 1;

        const step =
          parseInt(
            sourceQuantity.step || "1",
            10
          ) || 1;

        let value =
          cleanQuantity(
            sourceQuantity.value
          );

        if (
          quantityButton.classList.contains(
            "qty-plus"
          )
        ) {
          value += step;
        }

        if (
          quantityButton.classList.contains(
            "qty-minus"
          )
        ) {
          value -= step;
        }

        if (value < min) {
          value = min;
        }

        sourceQuantity.value =
          value;

        mobileQuantity.value =
          value;
      }
    );


    /*
     * Original +/- buttons are still handled by
     * the existing product stepper. Copy their
     * final value after that handler completes.
     */
    document.addEventListener(
      "click",
      function (event) {

        const sourceQuantityButton =
          event.target.closest(
            ".hsb-sp-preinvoice-form " +
            ".product-quantity-btn"
          );

        if (!sourceQuantityButton) {
          return;
        }

        window.setTimeout(
          syncMobileQuantity,
          0
        );
      }
    );


    function syncQuantityInput(event) {

      if (
        event.target !== sourceQuantity &&
        event.target !== mobileQuantity
      ) {
        return;
      }

      const value =
        cleanQuantity(
          event.target.value
        );

      sourceQuantity.value =
        value;

      mobileQuantity.value =
        value;
    }


    document.addEventListener(
      "input",
      syncQuantityInput
    );

    document.addEventListener(
      "change",
      syncQuantityInput
    );


    mobileButton.addEventListener(
      "click",
      function () {

        syncSourceQuantity();

        if (sourceButton.disabled) {
          return;
        }

        if (
          typeof form.requestSubmit ===
          "function"
        ) {
          form.requestSubmit(
            sourceButton
          );
        } else {
          sourceButton.click();
        }
      }
    );


    function syncButtonState() {

      const loading =
        sourceButton.disabled ||
        sourceButton.classList.contains(
          "loading"
        );

      mobileButton.disabled =
        loading;

      const label =
        mobileButton.querySelector(
          "span"
        );

      if (label) {
        label.textContent =
          loading
            ? "در حال افزودن..."
            : "افزودن به پیش‌فاکتور";
      }
    }


    new MutationObserver(
      syncButtonState
    ).observe(
      sourceButton,
      {
        attributes: true,
        attributeFilter: [
          "disabled",
          "class"
        ]
      }
    );


    if (
      sourceMessage &&
      mobileMessage
    ) {

      function syncMessage() {

        mobileMessage.textContent =
          sourceMessage.textContent || "";

        mobileMessage.classList.toggle(
          "is-success",
          sourceMessage.classList.contains(
            "is-success"
          )
        );

        mobileMessage.classList.toggle(
          "is-error",
          sourceMessage.classList.contains(
            "is-error"
          )
        );
      }


      new MutationObserver(
        syncMessage
      ).observe(
        sourceMessage,
        {
          childList: true,
          subtree: true,
          attributes: true,
          attributeFilter: [
            "class"
          ]
        }
      );
    }


    function updateMobileBar() {

      if (!mobileMedia.matches) {

        bar.classList.remove(
          "is-visible"
        );

        bar.style.removeProperty(
          "--hsb-mobile-preinvoice-bottom"
        );

        return;
      }


      const sourceRect =
        sourceButton.getBoundingClientRect();


      /*
       * Show as soon as the original CTA starts
       * leaving the top of the viewport.
       *
       * This prevents a visual gap between the
       * original CTA and the floating CTA.
       */
      /*
       * Reveal when the original CTA reaches
       * roughly the middle of the viewport.
       * This is about half a screen earlier.
       */
      const revealLine =
        window.innerHeight * 0.5;

      if (
        sourceRect.top >
        revealLine
      ) {

        bar.classList.remove(
          "is-visible"
        );

        bar.style.removeProperty(
          "--hsb-mobile-preinvoice-bottom"
        );

        return;
      }


      /*
       * Refresh the floating value from the
       * original form every time it appears.
       */
      syncMobileQuantity();

      bar.classList.add(
        "is-visible"
      );

      let bottom = 10;


      /*
       * Never overlap the footer.
       */
      if (footer) {

        const footerRect =
          footer.getBoundingClientRect();

        if (
          footerRect.top <
          window.innerHeight
        ) {

          bottom =
            Math.max(
              10,
              window.innerHeight -
                footerRect.top +
                10
            );
        }
      }


      bar.style.setProperty(
        "--hsb-mobile-preinvoice-bottom",
        bottom + "px"
      );
    }


    syncMobileQuantity();
    syncButtonState();
    updateMobileBar();


    window.addEventListener(
      "scroll",
      updateMobileBar,
      {
        passive: true
      }
    );

    window.addEventListener(
      "resize",
      updateMobileBar
    );

    if (
      typeof mobileMedia.addEventListener ===
      "function"
    ) {
      mobileMedia.addEventListener(
        "change",
        updateMobileBar
      );
    }

  }
);


/* HSB QUANTITY PERSIAN MIRROR REFRESH */
document.addEventListener("click", function (event) {

  if (!event.target.closest(
    ".product-quantity-btn"
  )) {
    return;
  }

  /*
   * Quantity values are already synchronized.
   * Persian Digits uses separate visual mirrors,
   * so refresh those mirrors after click handlers
   * and delayed quantity sync have completed.
   */
  window.setTimeout(function () {

    document
      .querySelectorAll(".product-quantity-input")
      .forEach(function (input) {

        input.dispatchEvent(
          new Event("input", {
            bubbles: true
          })
        );

      });

    if (
      window.WPPersianDigits &&
      typeof window.WPPersianDigits.refresh ===
        "function"
    ) {
      window.WPPersianDigits.refresh();
    }

  }, 0);

});

/* HSB PRODUCT FAMILY SWITCHER */
document.addEventListener(
  "DOMContentLoaded",
  function () {

    document
      .querySelectorAll(
        ".hsb-sp-family-switcher"
      )
      .forEach(function (switcher) {

        const jsonElement =
          switcher.querySelector(
            ".hsb-sp-family-live-data"
          );

        if (!jsonElement) {
          return;
        }

        let products = [];

        try {
          products = JSON.parse(
            jsonElement.textContent || "[]"
          );
        } catch (error) {
          console.error(
            "HSB family data error:",
            error
          );
          return;
        }

        if (!Array.isArray(products)) {
          return;
        }

        const productMap = new Map();

        products.forEach(function (item) {
          productMap.set(
            String(item.product_id),
            item
          );
        });

        const brandButtons =
          Array.from(
            switcher.querySelectorAll(
              ".hsb-sp-family-brand"
            )
          );

        const countryButtons =
          Array.from(
            switcher.querySelectorAll(
              ".hsb-sp-family-country"
            )
          );


        function normalizeBrandKey(item) {
          return String(
            item.brand_slug || ""
          );
        }


        /*
         * Keep the selector in exactly the same visual
         * position even if the selected product has
         * slightly taller/shorter specs.
         */
        function preserveViewport(callback) {

          const oldTop =
            switcher.getBoundingClientRect().top;

          callback();

          requestAnimationFrame(function () {

            const newTop =
              switcher.getBoundingClientRect().top;

            const delta = newTop - oldTop;

            if (Math.abs(delta) > 0.5) {
              window.scrollBy({
                top: delta,
                left: 0,
                behavior: "auto"
              });
            }
          });
        }


        function setBadge(
          selector,
          className,
          imageUrl,
          altText
        ) {

          const kicker =
            document.querySelector(
              ".hsb-sp-kicker"
            );

          if (!kicker) {
            return;
          }

          let badge =
            document.querySelector(selector);

          if (!badge && imageUrl) {
            badge =
              document.createElement("span");

            badge.className = className;

            kicker.appendChild(badge);
          }

          if (!badge) {
            return;
          }

          if (!imageUrl) {
            badge.hidden = true;
            return;
          }

          badge.hidden = false;
          badge.replaceChildren();

          const image =
            document.createElement("img");

          image.src = imageUrl;
          image.alt = altText || "";

          badge.title = altText || "";
          badge.appendChild(image);
        }

        function setCode(label, value) {

          const container =
            document.querySelector(
              ".hsb-sp-product-codes"
            );

          if (!container) {
            return;
          }

          let box =
            Array.from(
              container.children
            ).find(function (candidate) {

              const labelElement =
                candidate.querySelector("span");

              return (
                labelElement &&
                labelElement.textContent
                  .trim() === label
              );
            });

          if (!box && value) {
            box =
              document.createElement("div");

            box.className =
              "hsb-sp-code-box";

            const labelElement =
              document.createElement("span");

            labelElement.textContent = label;

            const strong =
              document.createElement("strong");

            strong.dir = "ltr";

            box.append(
              labelElement,
              strong
            );

            container.appendChild(box);
          }

          if (!box) {
            return;
          }

          if (!value) {
            box.hidden = true;
            return;
          }

          box.hidden = false;

          const strong =
            box.querySelector("strong");

          if (strong) {
            strong.textContent = value;
          }
        }


        function renderRating(item) {

          const stars =
            document.querySelector(
              ".hsb-sp-stars"
            );

          const ratingText =
            document.querySelector(
              ".hsb-sp-rating > span"
            );

          const rating =
            Number(item.rating || 0);

          if (stars) {
            stars.replaceChildren();

            for (let index = 1; index <= 5; index++) {

              const icon =
                document.createElement("i");

              if (rating >= index) {
                icon.className =
                  "fa-solid fa-star is-active";
              } else if (
                rating >= index - 0.5
              ) {
                icon.className =
                  "fa-solid fa-star-half-stroke is-active";
              } else {
                icon.className =
                  "fa-regular fa-star";
              }

              stars.appendChild(icon);
            }

            stars.setAttribute(
              "aria-label",
              rating.toFixed(1) + " از ۵"
            );
          }

          if (ratingText) {
            const count =
              Number(
                item.rating_count || 0
              );

            ratingText.textContent =
              count > 0
                ? count.toLocaleString("fa-IR") +
                  " رأی"
                : "هنوز امتیازی ثبت نشده";
          }
        }


        function renderKeySpecs(item) {

          const container =
            document.querySelector(
              ".hsb-sp-key-specs"
            );

          if (!container) {
            return;
          }

          container.replaceChildren();

          const specs =
            Array.isArray(item.key_specs)
              ? item.key_specs
              : [];

          specs.forEach(function (spec) {

            const card =
              document.createElement("div");

            card.className =
              "hsb-sp-key-spec" +
              (spec.detail
                ? " is-detail-spec"
                : "");

            const iconWrapper =
              document.createElement("span");

            iconWrapper.className =
              "hsb-sp-key-spec__icon";

            const icon =
              document.createElement("i");

            icon.className =
              "fa-solid " +
              String(spec.icon || "");

            iconWrapper.appendChild(icon);

            const details =
              document.createElement("div");

            const small =
              document.createElement("small");

            small.textContent =
              String(spec.label || "");

            const strong =
              document.createElement("strong");

            strong.textContent =
              String(spec.value || "");

            details.append(
              small,
              strong
            );

            card.append(
              iconWrapper,
              details
            );

            container.appendChild(card);
          });

          container.hidden =
            specs.length === 0;
        }


        function renderFullSpecs(item) {

          const panel =
            document.querySelector(
              ".hsb-sp-spec-panel"
            );

          const container =
            document.querySelector(
              ".hsb-sp-full-specs"
            );

          if (!panel || !container) {
            return;
          }

          const specs =
            Array.isArray(item.full_specs)
              ? item.full_specs
              : [];

          container.replaceChildren();

          specs.forEach(function (spec) {

            const row =
              document.createElement("div");

            row.className =
              "hsb-sp-full-spec";

            const label =
              document.createElement("span");

            label.textContent =
              String(spec.label || "");

            const value =
              document.createElement("strong");

            value.textContent =
              String(spec.value || "");

            row.append(label, value);
            container.appendChild(row);
          });

          panel.hidden =
            specs.length === 0;

          const code =
            panel.querySelector(
              ".hsb-sp-spec-panel__code"
            );

          if (code) {
            code.replaceChildren();

            if (item.part_number) {

              const small =
                document.createElement("small");

              small.textContent =
                "Part Number";

              const strong =
                document.createElement("strong");

              strong.dir = "ltr";

              strong.textContent =
                item.part_number;

              code.append(
                small,
                strong
              );
            }
          }
        }


        function ensureGalleryArrow(
          id,
          classes,
          iconClass,
          label
        ) {

          const main =
            document.querySelector(
              ".hsb-sp-main-image"
            );

          if (!main) {
            return null;
          }

          let button =
            document.getElementById(id);

          if (!button) {

            button =
              document.createElement("button");

            button.type = "button";
            button.id = id;
            button.className = classes;
            button.setAttribute(
              "aria-label",
              label
            );

            const icon =
              document.createElement("i");

            icon.className = iconClass;

            button.appendChild(icon);
            main.appendChild(button);
          }

          return button;
        }


        function renderImages(item) {

          const media =
            document.querySelector(
              ".hsb-sp-media"
            );

          const mainFrame =
            document.querySelector(
              ".hsb-sp-main-image__frame"
            );

          if (!media || !mainFrame) {
            return;
          }

          const images =
            Array.isArray(item.images)
              ? item.images
              : [];

          if (!images.length) {
            return;
          }

          let mainImage =
            mainFrame.querySelector("img");

          if (!mainImage) {
            mainImage =
              document.createElement("img");

            mainImage.className =
              "main-product-image";

            mainFrame.appendChild(
              mainImage
            );
          }

          const first = images[0];

          mainImage.src =
            first.full || first.thumb || "";

          if (first.srcset) {
            mainImage.srcset =
              first.srcset;
          } else {
            mainImage.removeAttribute(
              "srcset"
            );
          }

          mainImage.alt =
            first.alt ||
            item.name ||
            "";


          let gallery =
            media.querySelector(
              ".hsb-sp-gallery"
            );

          if (!gallery) {

            gallery =
              document.createElement("div");

            gallery.className =
              "hsb-sp-gallery product-image-gallery";

            const main =
              media.querySelector(
                ".hsb-sp-main-image"
              );

            if (main) {
              main.insertAdjacentElement(
                "afterend",
                gallery
              );
            }
          }

          gallery.replaceChildren();

          images.forEach(
            function (image, index) {

              const button =
                document.createElement("button");

              button.type = "button";

              button.className =
                "hsb-sp-gallery-thumb " +
                "product-image-gallery-frame" +
                (index === 0
                  ? " active"
                  : "");

              button.dataset.index =
                String(index);

              button.dataset.fullSrc =
                image.full || "";

              button.dataset.fullSrcset =
                image.srcset || "";

              button.dataset.fullAlt =
                image.alt || "";

              button.setAttribute(
                "aria-label",
                "نمایش تصویر " +
                  String(index + 1)
              );

              const imageElement =
                document.createElement("img");

              imageElement.src =
                image.thumb ||
                image.full ||
                "";

              imageElement.alt =
                image.alt || "";

              button.appendChild(
                imageElement
              );

              gallery.appendChild(button);
            }
          );

          gallery.hidden =
            images.length <= 1;


          const prev =
            ensureGalleryArrow(
              "prev-image",
              "hsb-sp-gallery-arrow " +
              "hsb-sp-gallery-arrow--right " +
              "carousel-control-prev custom-btn",
              "fa-solid fa-chevron-right",
              "تصویر قبلی"
            );

          const next =
            ensureGalleryArrow(
              "next-image",
              "hsb-sp-gallery-arrow " +
              "hsb-sp-gallery-arrow--left " +
              "carousel-control-next custom-btn",
              "fa-solid fa-chevron-left",
              "تصویر بعدی"
            );

          if (prev) {
            prev.hidden =
              images.length <= 1;
          }

          if (next) {
            next.hidden =
              images.length <= 1;
          }


          let note =
            media.querySelector(
              ".hsb-sp-media-note"
            );

          if (
            !item.has_real_image &&
            !note
          ) {

            note =
              document.createElement("span");

            note.className =
              "hsb-sp-media-note " +
              "is-placeholder-note";

            const icon =
              document.createElement("i");

            icon.className =
              "fa-regular fa-image";

            const text =
              document.createTextNode(
                " تصویر محصول هنوز ثبت نشده است"
              );

            note.append(icon, text);
            media.appendChild(note);
          }

          if (note) {
            note.hidden =
              Boolean(
                item.has_real_image
              );
          }

          if (
            typeof window
              .hsbProductGalleryReset ===
            "function"
          ) {
            window
              .hsbProductGalleryReset();
          }
        }


        function renderRequiredNote(item) {

          const inquiry =
            document.querySelector(
              ".hsb-sp-inquiry"
            );

          const form =
            document.querySelector(
              ".hsb-sp-preinvoice-form"
            );

          if (!inquiry || !form) {
            return;
          }

          let note =
            inquiry.querySelector(
              ".hsb-sp-required-note"
            );

          const count =
            Number(
              item.required_count || 0
            );

          if (count < 1) {
            if (note) {
              note.hidden = true;
            }
            return;
          }

          if (!note) {

            note =
              document.createElement("div");

            note.className =
              "hsb-sp-required-note";

            const icon =
              document.createElement("i");

            icon.className =
              "fa-solid fa-link";

            const body =
              document.createElement("div");

            const strong =
              document.createElement("strong");

            strong.textContent =
              "دارای کالای همراه اجباری";

            const text =
              document.createElement("span");

            body.append(
              strong,
              text
            );

            note.append(
              icon,
              body
            );

            inquiry.insertBefore(
              note,
              form
            );
          }

          note.hidden = false;

          const text =
            note.querySelector("span");

          if (text) {
            text.textContent =
              "هنگام افزودن، " +
              count.toLocaleString("fa-IR") +
              " قلم همراه نیز خودکار به " +
              "پیش‌فاکتور اضافه می‌شود.";
          }
        }


        function showExactSelection(item) {

          const brandKey =
            normalizeBrandKey(item);

          brandButtons.forEach(
            function (button) {

              button.classList.toggle(
                "is-active",
                button.dataset.familyBrand ===
                  brandKey
              );
            }
          );

          countryButtons.forEach(
            function (button) {

              const sameBrand =
                button.dataset.familyBrand ===
                brandKey;

              button.hidden =
                !sameBrand;

              button.classList.toggle(
                "is-active",
                String(
                  button.dataset.productId
                ) ===
                  String(item.product_id)
              );
            }
          );
        }


        function updateProduct(
          productId,
          pushHistory
        ) {

          const item =
            productMap.get(
              String(productId)
            );

          if (!item) {
            return;
          }

          preserveViewport(function () {

            switcher.dataset.currentProduct =
              String(item.product_id);

            showExactSelection(item);

            const title =
              document.querySelector(
                ".hsb-sp-title"
              );

            if (title) {
              title.textContent =
                item.name || "";
            }

            setBadge(
              ".hsb-sp-brand-badge",
              "hsb-sp-brand-badge",
              item.brand_image_url || "",
              item.brand || ""
            );

            setBadge(
              ".hsb-sp-country-badge",
              "hsb-sp-country-badge",
              item.country_flag_url || "",
              item.country || ""
            );

            setCode(
              "شماره محصول",
              item.part_number || ""
            );

            setCode(
              "شناسه یکتای محصول",
              item.sku || ""
            );

            renderRating(item);
            renderKeySpecs(item);
            renderFullSpecs(item);
            renderImages(item);
            renderRequiredNote(item);

            const productIdInput =
              document.querySelector(
                '.product-preinvoice-form ' +
                'input[name="product_id"]'
              );

            if (productIdInput) {
              productIdInput.value =
                String(item.product_id);
            }

            if (
              pushHistory &&
              item.url
            ) {

              const currentUrl =
                new URL(
                  window.location.href
                ).href;

              const targetUrl =
                new URL(
                  item.url,
                  window.location.href
                ).href;

              if (
                currentUrl !==
                targetUrl
              ) {
                window.history.pushState(
                  {
                    hsbFamilyProduct:
                      item.product_id
                  },
                  "",
                  targetUrl
                );
              }
            }

            document.dispatchEvent(
              new CustomEvent(
                "hsb_family_product_changed",
                {
                  detail: item
                }
              )
            );
          });
        }


        function getBrandProducts(brandKey) {

          return countryButtons.filter(
            function (button) {
              return (
                button.dataset.familyBrand ===
                brandKey
              );
            }
          );
        }


        function preloadMainImage(productId) {

          const item =
            productMap.get(
              String(productId)
            );

          if (
            !item ||
            !Array.isArray(item.images) ||
            !item.images.length
          ) {
            return;
          }

          const src =
            item.images[0].full;

          if (!src) {
            return;
          }

          const image = new Image();
          image.src = src;
        }


        /*
         * Hover/focus prefetch:
         * fast switching without downloading every sibling
         * image during initial page load.
         */
        countryButtons.forEach(
          function (button) {

            const prefetch =
              function () {
                preloadMainImage(
                  button.dataset.productId
                );
              };

            button.addEventListener(
              "mouseenter",
              prefetch,
              { once: true }
            );

            button.addEventListener(
              "focus",
              prefetch,
              { once: true }
            );


            button.addEventListener(
              "click",
              function () {

                updateProduct(
                  button.dataset.productId,
                  true
                );
              }
            );
          }
        );


        /*
         * Clicking a brand instantly selects its first exact
         * version. If it has several countries, all remain
         * visible so the customer can choose another one.
         */
        brandButtons.forEach(
          function (button) {

            button.addEventListener(
              "click",
              function () {

                const brandKey =
                  button.dataset.familyBrand ||
                  "";

                const candidates =
                  getBrandProducts(
                    brandKey
                  );

                if (!candidates.length) {
                  return;
                }

                const currentId =
                  String(
                    switcher.dataset
                      .currentProduct || ""
                  );

                const currentCandidate =
                  candidates.find(
                    function (candidate) {
                      return (
                        String(
                          candidate.dataset
                            .productId
                        ) === currentId
                      );
                    }
                  );

                const target =
                  currentCandidate ||
                  candidates[0];

                preloadMainImage(
                  target.dataset.productId
                );

                updateProduct(
                  target.dataset.productId,
                  true
                );
              }
            );
          }
        );


        /*
         * Browser Back/Forward also changes the selected
         * version without reloading and without losing
         * the user's vertical position.
         */
        window.addEventListener(
          "popstate",
          function () {

            const current =
              new URL(
                window.location.href
              );

            const matched =
              products.find(
                function (item) {

                  if (!item.url) {
                    return false;
                  }

                  const url =
                    new URL(
                      item.url,
                      window.location.href
                    );

                  return (
                    url.pathname ===
                      current.pathname &&
                    url.search ===
                      current.search
                  );
                }
              );

            if (matched) {
              updateProduct(
                matched.product_id,
                false
              );
            }
          }
        );

      });
  }
);

/* HSB Description Question Link -> QA Tab */
document.addEventListener("click", function (event) {
  const questionLink = event.target.closest(
    ".pd-quick-question-link"
  );

  if (!questionLink) {
    return;
  }

  const qaTabButton = document.querySelector(
    '.product-meta-nav button[data-tab="qa"]'
  );

  if (!qaTabButton) {
    return;
  }

  event.preventDefault();

  qaTabButton.click();

  const metaTabs = document.querySelector(
    ".product-meta-tabs"
  );

  if (metaTabs) {
    metaTabs.scrollIntoView({
      behavior: "smooth",
      block: "start"
    });
  }
});


/* ============================================================
   HSB SINGLE PRODUCT LAZY RELATED / SIMILAR PRODUCTS
============================================================ */

document.addEventListener(
  "DOMContentLoaded",
  function () {

    const sections =
      document.querySelectorAll(
        "[data-hsb-lazy-section]"
      );

    if (!sections.length) {
      return;
    }

    if (
      typeof hsbSingleProduct === "undefined"
    ) {
      return;
    }


    const loaded = new Set();


    function loadSection(section) {

      const type =
        section.dataset.hsbLazySection;

      const productId =
        section.dataset.productId;


      if (
        loaded.has(type) ||
        !productId
      ) {
        return;
      }

      loaded.add(type);


      const body =
        new FormData();

      body.append(
        "action",
        "hsb_load_single_product_sections"
      );

      body.append(
        "nonce",
        hsbSingleProduct.nonce
      );

      body.append(
        "product_id",
        productId
      );


      fetch(
        hsbSingleProduct.ajax_url,
        {
          method: "POST",
          body: body
        }
      )
      .then(
        response => response.json()
      )
      .then(
        result => {

          if (
            !result.success
          ) {
            return;
          }


          const row =
            section.querySelector(
              ".sp-products-row"
            );

          if (!row) {
            return;
          }


          const html =
            type === "related"
              ? result.data.related_html
              : result.data.similar_html;

          if (!html || !html.trim()) {
            section.style.display = "none";
            return;
          }

          row.innerHTML = html;

        }
      )
      .catch(
        function () {
          loaded.delete(type);
        }
      );

    }


    const observer =
      new IntersectionObserver(
        function (entries) {

          entries.forEach(
            function (entry) {

              if (
                entry.isIntersecting
              ) {

                loadSection(
                  entry.target
                );

                observer.unobserve(
                  entry.target
                );

              }

            }
          );

        },
        {
          rootMargin: "300px"
        }
      );


    sections.forEach(
      function (section) {
        observer.observe(section);
      }
    );

  }
);

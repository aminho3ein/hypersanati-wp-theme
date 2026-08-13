document.addEventListener("DOMContentLoaded", function () {

    let index = 0;
    let loading = false;
    let finished = false;
    let currentSelectedCategory = 0;

    const urlParams = new URLSearchParams(window.location.search);
    let searchQuery = urlParams.get('s') ? urlParams.get('s').trim() : "";
    const dimensionSearch = urlParams.get('dimension_search');

    const dimensionKeys = [
        'dimension_search',
        'inner', 'outer', 'height',
        'inner_min', 'inner_max',
        'outer_min', 'outer_max',
        'height_min', 'height_max',
    ];

    let dimensionQuery = '';
    if (dimensionSearch) {
        dimensionKeys.forEach(function (key) {
            if (urlParams.has(key)) {
                dimensionQuery += '&' + key + '=' + encodeURIComponent(urlParams.get(key));
            }
        });
    }

    const container = document.getElementById("shop-container");
    const loader = document.querySelector(".shop-loader");
    const sidebarContainer = document.getElementById("sidebar-category");
    const mobileSidebarContainer = document.getElementById("mobile-sidebar-category");

    const searchInput = document.getElementById("search-term");
    const searchForm = document.getElementById("ajax-search-form");
    const resetSearchBtn = document.getElementById("reset-search");

    const indexSearchInput = document.getElementById("index-search-term");
    const indexResetBtn = document.getElementById("index-reset-search");

    const shopUrl = (typeof hypersanatiSearch !== 'undefined' && hypersanatiSearch.shopUrl)
        ? hypersanatiSearch.shopUrl
        : window.location.pathname;


    /* ========================================================
       Progressive product-family loading
       ======================================================== */

    let shopFamilyObserver = null;


    function disconnectShopFamilyObserver() {
        if (shopFamilyObserver) {
            shopFamilyObserver.disconnect();
            shopFamilyObserver = null;
        }
    }


    function getNextShopFamilySentinel() {
        if (!container) {
            return null;
        }

        return container.querySelector(
            '.hsb-shop-lazy-sentinel[data-complete="0"]'
        );
    }


    function activateNextShopFamilySentinel() {

        disconnectShopFamilyObserver();

        const sentinel =
            getNextShopFamilySentinel();

        if (!sentinel) {
            return;
        }

        shopFamilyObserver =
            new IntersectionObserver(
                function (entries) {

                    entries.forEach(
                        function (entry) {

                            if (!entry.isIntersecting) {
                                return;
                            }

                            shopFamilyObserver.unobserve(
                                sentinel
                            );

                            loadShopFamilyBatch(
                                sentinel
                            );
                        }
                    );
                },
                {
                    root: null,

                    /*
                     * Begin shortly before the loader enters
                     * the viewport, not several screens early.
                     */
                    rootMargin:
                        '180px 0px 180px 0px',

                    threshold: 0.01
                }
            );

        shopFamilyObserver.observe(
            sentinel
        );
    }


    function loadShopFamilyBatch(sentinel) {

        if (!sentinel) {
            return;
        }

        if (
            sentinel.dataset.loading === '1' ||
            sentinel.dataset.complete === '1'
        ) {
            return;
        }

        const subcategoryId =
            parseInt(
                sentinel.dataset.subcategoryId ||
                '0',
                10
            );

        const page =
            parseInt(
                sentinel.dataset.page ||
                '0',
                10
            );

        if (!subcategoryId) {
            sentinel.dataset.complete = '1';
            sentinel.remove();
            activateNextShopFamilySentinel();
            return;
        }

        sentinel.dataset.loading = '1';
        sentinel.classList.add(
            'is-loading'
        );

        const statusText =
            sentinel.querySelector(
                '.hsb-shop-lazy-status span'
            );

        if (statusText) {
            statusText.textContent =
                'در حال بارگذاری محصولات...';
        }

        const params =
            new URLSearchParams({
                action:
                    'hsb_load_shop_family_batch',

                subcategory_id:
                    String(subcategoryId),

                page:
                    String(page),

                search_keyword:
                    searchQuery
            });

        fetch(
            '/wp-admin/admin-ajax.php?' +
            params.toString(),
            {
                credentials: 'same-origin'
            }
        )
            .then(function (response) {
                if (!response.ok) {
                    throw new Error(
                        'خطا در دریافت محصولات'
                    );
                }

                return response.json();
            })
            .then(function (response) {

                if (
                    !response ||
                    !response.success ||
                    !response.data
                ) {
                    throw new Error(
                        'پاسخ نامعتبر سرور'
                    );
                }

                const section =
                    sentinel.closest(
                        '.hsb-shop-lazy-subcategory'
                    );

                const grid =
                    section
                        ? section.querySelector(
                            '.hsb-shop-lazy-grid'
                        )
                        : null;

                const html =
                    response.data.html || '';

                if (grid && html.trim()) {

                    /*
                     * Defensive client-side family dedupe.
                     * Useful if the same family ever crosses
                     * server-side result boundaries.
                     */
                    const temporary =
                        document.createElement(
                            'div'
                        );

                    temporary.innerHTML = html;

                    const existingKeys =
                        new Set(
                            Array.from(
                                container.querySelectorAll(
                                    '.hsb-family-product-card[data-family-key]'
                                )
                            ).map(
                                function (card) {
                                    return card.dataset.familyKey;
                                }
                            )
                        );

                    Array.from(
                        temporary.children
                    ).forEach(
                        function (card) {

                            const key =
                                card.dataset
                                    ? card.dataset.familyKey
                                    : '';

                            if (
                                key &&
                                existingKeys.has(key)
                            ) {
                                return;
                            }

                            if (key) {
                                existingKeys.add(key);
                            }

                            grid.appendChild(card);
                        }
                    );
                }


                const hasMore =
                    Boolean(
                        response.data.has_more
                    );

                const nextPage =
                    parseInt(
                        response.data.next_page,
                        10
                    );

                sentinel.dataset.loading = '0';
                sentinel.classList.remove(
                    'is-loading'
                );


                if (hasMore) {

                    sentinel.dataset.page =
                        String(
                            Number.isFinite(
                                nextPage
                            )
                                ? nextPage
                                : page + 1
                        );

                    if (statusText) {
                        statusText.textContent =
                            'برای مشاهده محصولات بیشتر، ادامه دهید';
                    }

                    /*
                     * New cards push the sentinel downward.
                     * Re-observe it so the next batch loads
                     * only when the user actually reaches it.
                     */
                    requestAnimationFrame(
                        function () {
                            activateNextShopFamilySentinel();
                        }
                    );

                    return;
                }


                sentinel.dataset.complete = '1';

                /*
                 * Empty result after title search:
                 * remove the whole empty subcategory.
                 */
                if (
                    section &&
                    grid &&
                    !grid.children.length
                ) {
                    section.remove();
                } else {
                    sentinel.remove();
                }

                requestAnimationFrame(
                    function () {
                        activateNextShopFamilySentinel();
                    }
                );
            })
            .catch(function (error) {

                console.error(
                    'HSB progressive Shop load:',
                    error
                );

                sentinel.dataset.loading = '0';

                sentinel.classList.remove(
                    'is-loading'
                );

                if (statusText) {
                    statusText.textContent =
                        'بارگذاری انجام نشد؛ با اسکرول دوباره تلاش می‌شود';
                }

                setTimeout(
                    function () {
                        activateNextShopFamilySentinel();
                    },
                    1200
                );
            });
    }

    function loadSidebarCategories() {
        if (!sidebarContainer) return;
        fetch('/wp-admin/admin-ajax.php?action=get_sidebar_categories')
            .then(res => res.text())
            .then(html => {
                sidebarContainer.innerHTML = html;

                if (mobileSidebarContainer) {
                    mobileSidebarContainer.innerHTML = html;
                }

                listenToSidebarChanges();
            })
            .catch(err => console.error("خطا در بارگذاری سایدبار:", err));
    }

    function loadCategory(reset = false) {
        if (!container) return;

        if (reset) {
            disconnectShopFamilyObserver();

            index = 0;
            finished = false;
            loading = false;
            container.innerHTML = "";
            if (loader) {
                loader.style.display = "block";
                loader.innerText = "در حال بارگذاری...";
            }
        }

        if (loading || finished) return;

        loading = true;
        if (loader) loader.style.display = "block";

        fetch(`/wp-admin/admin-ajax.php?action=load_shop_categories&index=${index}&category_id=${currentSelectedCategory}&search_keyword=${encodeURIComponent(searchQuery)}`)
            .then(res => res.text())
            .then(html => {
                if (!html || html.trim() === "") {
                    finished = true;
                    if (loader) loader.style.display = "none";
                    if (index === 0) {
                        container.innerHTML = "<p style='text-align:center; padding:20px;'>محصولی یافت نشد.</p>";
                    }
                    return;
                }

                container.insertAdjacentHTML("beforeend", html);

                index++;
                loading = false;

                if (loader) {
                    loader.style.display = "none";
                }

                /*
                 * Only one product-family sentinel is active
                 * at a time. This prevents an entire catalog
                 * from preloading below the fold.
                 */
                activateNextShopFamilySentinel();
            })
            .catch(err => {
                console.error(err);
                loading = false;
                if (loader) loader.style.display = "none";
            });
    }

    function loadDimensionSearch(reset = false) {
        if (!container) return;

        if (reset) {
            index = 0;
            finished = false;
            loading = false;
            container.innerHTML = "";
            if (loader) {
                loader.style.display = "block";
                loader.innerText = "در حال بارگذاری...";
            }
        }

        if (loading || finished) return;

        loading = true;
        if (loader) loader.style.display = "block";

        fetch(`/wp-admin/admin-ajax.php?action=search_products_by_dimensions&index=${index}${dimensionQuery}`)
            .then(res => res.text())
            .then(html => {
                if (!html || html.trim() === "") {
                    finished = true;
                    if (loader) loader.style.display = "none";
                    if (index === 0) {
                        container.innerHTML = "<p style='text-align:center; padding:20px;'>محصولی با این بازه ابعاد یافت نشد.</p>";
                    }
                    return;
                }

                container.insertAdjacentHTML("beforeend", html);
                index++;
                loading = false;
                if (loader) loader.style.display = "none";
            })
            .catch(err => {
                console.error(err);
                loading = false;
                if (loader) loader.style.display = "none";
            });
    }

    function loadProducts(reset = false) {
        if (dimensionSearch) {
            loadDimensionSearch(reset);
        } else {
            loadCategory(reset);
        }
    }

    function listenToSidebarChanges() {
        const radioButtons =
            document.querySelectorAll(
                'input[name="product_category"]'
            );

        radioButtons.forEach(radio => {

            if (radio.dataset.hsbBound === '1') {
                return;
            }

            radio.dataset.hsbBound = '1';

            radio.addEventListener(
                'change',
                function () {

                    if (!this.checked) {
                        return;
                    }

                    currentSelectedCategory =
                        parseInt(
                            this.value,
                            10
                        ) || 0;

                    const mobileFilter =
                        this.closest(
                            '.hsb-mobile-category-filter'
                        );

                    if (mobileFilter) {
                        mobileFilter.removeAttribute(
                            'open'
                        );
                    }

                    loadProducts(true);
                }
            );
        });
    }

    function handleSearchFiltering() {
        if (!searchForm || !searchInput || !resetSearchBtn) return;

        if (searchQuery !== "") {
            searchInput.value = searchQuery;
            resetSearchBtn.style.display = "block";
        }

        searchForm.addEventListener("submit", function (e) {
            e.preventDefault();
            searchQuery = searchInput.value.trim();

            if (searchQuery !== "") {
                resetSearchBtn.style.display = "block";
            } else {
                resetSearchBtn.style.display = "none";
            }
            loadProducts(true);
        });

        resetSearchBtn.addEventListener("click", function () {
            searchInput.value = "";
            searchQuery = "";
            resetSearchBtn.style.display = "none";

            const cleanUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
            window.history.pushState({ path: cleanUrl }, '', cleanUrl);

            loadProducts(true);
        });

        searchInput.addEventListener("input", function () {
            if (this.value.trim() !== "") {
                resetSearchBtn.style.display = "block";
            } else {
                resetSearchBtn.style.display = "none";
                if (searchQuery !== "") {
                    searchQuery = "";
                    loadProducts(true);
                }
            }
        });
    }

    function getShopRangeValues(cardId) {
        const card = document.getElementById(cardId);

        if (!card) {
            return { min: '', max: '' };
        }

        const inputs = card.querySelectorAll('.new-handle-input');

        return {
            min: inputs[0] ? inputs[0].value : '',
            max: inputs[1] ? inputs[1].value : ''
        };
    }

    function handleShopDimensionSearch() {
        const searchBtn = document.getElementById('approximate-search-btn');

        if (!searchBtn) return;

        searchBtn.addEventListener('click', function () {
            const inner = getShopRangeValues('shop-range-inner');
            const outer = getShopRangeValues('shop-range-outer');
            const height = getShopRangeValues('shop-range-height');

            const params = new URLSearchParams({
                dimension_search: 'approx',
                inner_min: inner.min,
                inner_max: inner.max,
                outer_min: outer.min,
                outer_max: outer.max,
                height_min: height.min,
                height_max: height.max
            });

            window.location.href =
                shopUrl +
                (shopUrl.includes('?') ? '&' : '?') +
                params.toString();
        });
    }

    function handleDimensionSearchReset() {
        const resetBtn = document.getElementById('reset-dimension-search');
        if (!resetBtn) return;

        resetBtn.addEventListener('click', function () {
            const url = new URL(window.location.href);

            /*
             * Disable dimension filtering without discarding the
             * range values currently selected by the customer.
             */
            url.searchParams.delete('dimension_search');

            window.location.href = url.toString();
        });
    }

    function handleIndexSearch() {
        if (!indexSearchInput || !indexResetBtn) return;

        indexSearchInput.addEventListener("input", function () {
            if (this.value.trim() !== "") {
                indexResetBtn.style.display = "block";
            } else {
                indexResetBtn.style.display = "none";
            }
        });

        indexResetBtn.addEventListener("click", function () {
            indexSearchInput.value = "";
            indexResetBtn.style.display = "none";
            indexSearchInput.focus();

            if (window.location.search.includes('s=')) {
                window.location.href = window.location.pathname;
            }
        });

        if (indexSearchInput.value.trim() !== "") {
            indexResetBtn.style.display = "block";
        }
    }

    loadSidebarCategories();
    loadProducts();
    handleSearchFiltering();
    handleIndexSearch();
    handleShopDimensionSearch();
    handleDimensionSearchReset();

    window.addEventListener("scroll", function () {

        if (
            window.innerHeight +
            window.scrollY <
            document.body.offsetHeight - 400
        ) {
            return;
        }

        /*
         * Finish progressive batches in the current category
         * before adding another top-level category below it.
         */
        const pendingFamilyBatch =
            container
                ? container.querySelector(
                    '.hsb-shop-lazy-sentinel[data-complete="0"]'
                )
                : null;

        if (pendingFamilyBatch) {
            return;
        }

        loadProducts();
    });

});

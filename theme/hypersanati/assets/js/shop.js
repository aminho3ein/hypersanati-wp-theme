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

    const searchInput = document.getElementById("search-term");
    const searchForm = document.getElementById("ajax-search-form");
    const resetSearchBtn = document.getElementById("reset-search");

    const indexSearchInput = document.getElementById("index-search-term");
    const indexResetBtn = document.getElementById("index-reset-search");

    const shopUrl = (typeof hypersanatiSearch !== 'undefined' && hypersanatiSearch.shopUrl)
        ? hypersanatiSearch.shopUrl
        : window.location.pathname;

    function loadSidebarCategories() {
        if (!sidebarContainer) return;
        fetch('/wp-admin/admin-ajax.php?action=get_sidebar_categories')
            .then(res => res.text())
            .then(html => {
                sidebarContainer.innerHTML = html;
                listenToSidebarChanges();
            })
            .catch(err => console.error("خطا در بارگذاری سایدبار:", err));
    }

    function loadCategory(reset = false) {
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
                if (loader) loader.style.display = "none";
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
        const radioButtons = document.querySelectorAll('input[name="product_category"]');
        radioButtons.forEach(radio => {
            radio.addEventListener('change', function () {
                if (this.checked) {
                    currentSelectedCategory = parseInt(this.value);
                    loadProducts(true);
                }
            });
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

    function handleDimensionSearchReset() {
        const resetBtn = document.getElementById('reset-dimension-search');
        if (!resetBtn) return;

        resetBtn.addEventListener('click', function () {
            window.location.href = shopUrl;
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
    handleDimensionSearchReset();

    window.addEventListener("scroll", function () {
        if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 400) {
            loadProducts();
        }
    });

});

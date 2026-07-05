document.addEventListener("DOMContentLoaded", function () {

    let index = 0;
    let loading = false;
    let finished = false;
    let currentSelectedCategory = 0; 
    
    // 🛠️ اصلاح کلیدی: خواندن خودکار عبارت جستجو از آدرس بار (URL) در بدو ورود به صفحه
    const urlParams = new URLSearchParams(window.location.search);
    let searchQuery = urlParams.get('s') ? urlParams.get('s').trim() : ""; 

    const container = document.getElementById("shop-container");
    const loader = document.querySelector(".shop-loader");
    const sidebarContainer = document.getElementById("sidebar-category");
    
    // المان‌های بخش جدید سرچ صفحه محصولات
    const searchInput = document.getElementById("search-term");
    const searchForm = document.getElementById("ajax-search-form");
    const resetSearchBtn = document.getElementById("reset-search");
    const triggerSearchBtn = document.getElementById("trigger-search");

    // المان‌های سرچ صفحه اصلی (ایندکس) جهت سازگاری و عدم بروز خطا
    const indexSearchInput = document.getElementById("index-search-term");
    const indexResetBtn = document.getElementById("index-reset-search");

    // ۱. بارگذاری ساختار دسته‌ها در سایدبار
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

    // ۲. بارگذاری محصولات وسط صفحه (شامل افزودن متغیر سرچ متنی)
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

        // ارسال کوئری به همراه پارامتر search_keyword
        fetch(`/wp-admin/admin-ajax.php?action=load_shop_categories&index=${index}&category_id=${currentSelectedCategory}&search_keyword=${encodeURIComponent(searchQuery)}`)
            .then(res => res.text())
            .then(html => {
                if (!html || html.trim() === "") {
                    finished = true;
                    if (loader) loader.style.display = "none"; 
                    if(index === 0) {
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

    // ۳. شنود تغییرات رادیو باتن‌های سایدبار
    function listenToSidebarChanges() {
        const radioButtons = document.querySelectorAll('input[name="product_category"]');
        radioButtons.forEach(radio => {
            radio.addEventListener('change', function () {
                if (this.checked) {
                    currentSelectedCategory = parseInt(this.value);
                    loadCategory(true); 
                }
            });
        });
    }

    // 🔍 مدیریت فرآیند جستجو و دکمه ضربدر فیلتر در صفحه فروشگاه
    function handleSearchFiltering() {
        if (!searchForm || !searchInput || !resetSearchBtn) return;

        // اگر از صفحه اصلی سرچ شده بود، فیلد متن و دکمه ضربدر ست شوند
        if (searchQuery !== "") {
            searchInput.value = searchQuery;
            resetSearchBtn.style.display = "block";
        }
        
        // زدن دکمه ذره‌بین یا اینتر روی فیلد سرچ فروشگاه
        searchForm.addEventListener("submit", function (e) {
            e.preventDefault();
            searchQuery = searchInput.value.trim();
            
            if (searchQuery !== "") {
                resetSearchBtn.style.display = "block";
            } else {
                resetSearchBtn.style.display = "none";
            }
            loadCategory(true); 
        });

        // کلیک روی دکمه ضربدر صفحه محصول برای انصراف از جستجو
        resetSearchBtn.addEventListener("click", function () {
            searchInput.value = ""; 
            searchQuery = ""; 
            resetSearchBtn.style.display = "none"; 
            
            // تمیز کردن URL بدون ریلود صفحه تا در سوابق مرورگر سرچ قبلی نماند
            const cleanUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
            window.history.pushState({path:cleanUrl}, '', cleanUrl);

            loadCategory(true); 
        });

        // مانیتور کردن فیلد برای کنترل دکمه ضربدر هنگام تایپ دستی کاربر
        searchInput.addEventListener("input", function() {
            if (this.value.trim() !== "") {
                resetSearchBtn.style.display = "block";
            } else {
                resetSearchBtn.style.display = "none";
                if(searchQuery !== "") {
                    searchQuery = "";
                    loadCategory(true);
                }
            }
        });
    }

    // 🏠 مدیریت فرم جستجوی صفحه اصلی (ایندکس)
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

    // اجرای توابع اولیه سیستم پس از لود صفحه
    loadSidebarCategories();
    loadCategory();
    handleSearchFiltering();
    handleIndexSearch();

    // اسکرول بی‌پایان
    window.addEventListener("scroll", function () {
        if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 400) {
            loadCategory();
        }
    });

});
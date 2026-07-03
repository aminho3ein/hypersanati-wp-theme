document.addEventListener("DOMContentLoaded", function () {

    let index = 0;
    let loading = false;
    let finished = false;
    let currentSelectedCategory = 0; 
    let searchQuery = ""; // متغیر نگه‌دارنده عبارت جستجو

    const container = document.getElementById("shop-container");
    const loader = document.querySelector(".shop-loader");
    const sidebarContainer = document.getElementById("sidebar-category");
    
    // المان‌های بخش جدید سرچ
    const searchInput = document.getElementById("search-term");
    const searchForm = document.getElementById("ajax-search-form");
    const resetSearchBtn = document.getElementById("reset-search");
    const triggerSearchBtn = document.getElementById("trigger-search");

    // ۱. بارگذاری ساختار دسته‌ها در سایدبار
    function loadSidebarCategories() {
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
        if (reset) {
            index = 0;
            finished = false;
            loading = false;
            container.innerHTML = "";
            loader.style.display = "block";
            loader.innerText = "در حال بارگذاری...";
        }

        if (loading || finished) return;

        loading = true;
        loader.style.display = "block";

        // ارسال کوئری به همراه پارامتر search_keyword
        fetch(`/wp-admin/admin-ajax.php?action=load_shop_categories&index=${index}&category_id=${currentSelectedCategory}&search_keyword=${encodeURIComponent(searchQuery)}`)
            .then(res => res.text())
            .then(html => {
                if (!html || html.trim() === "") {
                    finished = true;
                    loader.style.display = "none"; 
                    if(index === 0) {
                        container.innerHTML = "<p style='text-align:center; padding:20px;'>محصولی یافت نشد.</p>";
                    }
                    return;
                }

                container.insertAdjacentHTML("beforeend", html);
                index++;
                loading = false;
                loader.style.display = "none"; 
            })
            .catch(err => {
                console.error(err);
                loading = false;
                loader.style.display = "none";
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

    // 🔍 مدیریت فرآیند جستجو و دکمه ضربدر فیلترشکن
    function handleSearchFiltering() {
        
        // زدن دکمه ذره‌بین یا اینتر روی فیلد سرچ
        searchForm.addEventListener("submit", function (e) {
            e.preventDefault();
            searchQuery = searchInput.value.trim();
            
            if (searchQuery !== "") {
                resetSearchBtn.style.display = "block"; // نمایش دکمه ضربدر فیلتر
            } else {
                resetSearchBtn.style.display = "none";
            }
            loadCategory(true); // ریست کل محصولات و اجرای فیلتر متنی جدید
        });

        // کلیک روی دکمه ضربدر برای انصراف از جستجو و فیلتر متنی
        resetSearchBtn.addEventListener("click", function () {
            searchInput.value = ""; // خالی کردن فیلد
            searchQuery = ""; // ریست کردن کلمه کلیدی
            resetSearchBtn.style.display = "none"; // مخفی کردن خود ضربدر
            loadCategory(true); // بارگذاری مجدد تمام محصولات به حالت اول بدون فیلتر متنی
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

    // اجرای توابع اولیه سیستم پس از لود صفحه
    loadSidebarCategories();
    loadCategory();
    handleSearchFiltering();

    // اسکرول بی‌پایان
    window.addEventListener("scroll", function () {
        if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 400) {
            loadCategory();
        }
    });

});
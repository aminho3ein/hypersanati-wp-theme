document.querySelectorAll('.new-range-card').forEach(card => {
    const slider = card.querySelector('.new-dual-slider');
    const minHandle = card.querySelector('.min-handle');
    const maxHandle = card.querySelector('.max-handle');
    const minInput = minHandle.querySelector('.new-handle-input');
    const maxInput = maxHandle.querySelector('.new-handle-input');
    const rangeBar = card.querySelector('.new-slider-range-bar');

    const MIN_VAL = 10;
    const MAX_VAL = 100;

    function updateSlider() {
        let minVal = parseFloat(minInput.value) || MIN_VAL;
        let maxVal = parseFloat(maxInput.value) || MAX_VAL;

        if (minVal < MIN_VAL) minVal = MIN_VAL;
        if (maxVal > MAX_VAL) maxVal = MAX_VAL;
        
        // کنترل تداخل نداشتن از و تا
        if (minVal > maxVal) {
            minVal = maxVal;
        }

        minInput.value = Math.round(minVal);
        maxInput.value = Math.round(maxVal);

        const minPct = ((minVal - MIN_VAL) / (MAX_VAL - MIN_VAL)) * 100;
        const maxPct = ((maxVal - MIN_VAL) / (MAX_VAL - MIN_VAL)) * 100;

        // اعمال پوزیشن‌ها بر اساس ساختار راست‌به‌چپ (RTL)
        minHandle.style.right = `calc(${minPct}% - 8px)`;
        maxHandle.style.right = `calc(${maxPct}% - 8px)`;

        rangeBar.style.right = `${minPct}%`;
        rangeBar.style.width = `${maxPct - minPct}%`;
    }

    function enableDrag(handle, input, isMin) {
        let isDragging = false;

        handle.addEventListener('mousedown', (e) => {
            if (e.target === input) return; // اگر فوکوس روی تایپ است درگ نشود
            isDragging = true;
            e.preventDefault();
        });

        handle.addEventListener('touchstart', (e) => {
            if (e.target === input) return;
            isDragging = true;
        });

        window.addEventListener('mousemove', (e) => {
            if (!isDragging) return;
            const rect = slider.getBoundingClientRect();
            let offset = rect.right - e.clientX; 
            let pct = (offset / rect.width) * 100;
            pct = Math.max(0, Math.min(100, pct));

            const actualVal = MIN_VAL + (pct / 100) * (MAX_VAL - MIN_VAL);

            if (isMin) {
                if (actualVal <= parseFloat(maxInput.value)) {
                    input.value = Math.round(actualVal);
                }
            } else {
                if (actualVal >= parseFloat(minInput.value)) {
                    input.value = Math.round(actualVal);
                }
            }
            updateSlider();
        });

        window.addEventListener('touchmove', (e) => {
            if (!isDragging) return;
            const rect = slider.getBoundingClientRect();
            let offset = rect.right - e.touches[0].clientX;
            let pct = (offset / rect.width) * 100;
            pct = Math.max(0, Math.min(100, pct));

            const actualVal = MIN_VAL + (pct / 100) * (MAX_VAL - MIN_VAL);

            if (isMin) {
                if (actualVal <= parseFloat(maxInput.value)) {
                    input.value = Math.round(actualVal);
                }
            } else {
                if (actualVal >= parseFloat(minInput.value)) {
                    input.value = Math.round(actualVal);
                }
            }
            updateSlider();
        });

        window.addEventListener('mouseup', () => isDragging = false);
        window.addEventListener('touchend', () => isDragging = false);
    }

    enableDrag(minHandle, minInput, true);
    enableDrag(maxHandle, maxInput, false);

    // به‌روزرسانی زمان تایپ مستقیم مقادیر عددی داخل بالون‌ها
    minInput.addEventListener('change', updateSlider);
    maxInput.addEventListener('change', updateSlider);

    // رندر اولیه ابعاد
    updateSlider();
});



document.addEventListener('DOMContentLoaded', function() {
    const blogScroll = document.getElementById('blogScroll');
    const scrollLeftBtn = document.getElementById('scrollLeftBtn');
    const scrollRightBtn = document.getElementById('scrollRightBtn');
    const cardBoxes = document.querySelector('.card-boxes');

    if (!blogScroll || !scrollLeftBtn || !scrollRightBtn) return;

    // محاسبه عرض یک کارت به همراه فاصله (Gap/Margin)
    const getScrollAmount = () => {
        const card = blogScroll.querySelector('.blog-card');
        if (card) {
            const style = window.getComputedStyle(card);
            const margin = parseFloat(style.marginLeft) + parseFloat(style.marginRight);
            return card.offsetWidth + margin;
        }
        return 300; // مقدار پیش‌فرض در صورت لود نشدن المان
    };

    // تابع رفتن به کارت بعدی (اسکرول به سمت چپ در سایت‌های RTL)
    const scrollNext = () => {
        const scrollAmount = getScrollAmount();
        const maxScroll = blogScroll.scrollWidth - blogScroll.clientWidth;
        
        // در مرورگرها برای RTL، مقدار scrollLeft معمولا منفی است
        // اگر به انتهای اسکرول رسیدیم، به نقطه صفر (ابتدا) برگرد
        if (Math.abs(blogScroll.scrollLeft) >= maxScroll - 10) { 
            blogScroll.scrollTo({ left: 0, behavior: 'smooth' });
        } else {
            // اسکرول به سمت چپ (آیتم‌های بعدی در RTL)
            blogScroll.scrollBy({ left: -scrollAmount, behavior: 'smooth' }); 
        }
    };

    // تابع برگشت به کارت قبلی (اسکرول به سمت راست در سایت‌های RTL)
    const scrollPrev = () => {
        const scrollAmount = getScrollAmount();
        blogScroll.scrollBy({ left: scrollAmount, behavior: 'smooth' });
    };

    // اتصال دکمه‌ها به توابع
    scrollLeftBtn.addEventListener('click', scrollNext);
    scrollRightBtn.addEventListener('click', scrollPrev);

    // تنظیم اسکرول خودکار هر 7 ثانیه (7000 میلی‌ثانیه)
    let autoScrollTimer = setInterval(scrollNext, 7000);

    // توقف اسکرول خودکار زمانی که موس روی بخش مقالات می‌آید
    cardBoxes.addEventListener('mouseenter', () => {
        clearInterval(autoScrollTimer);
    });
    
    // اجرای مجدد اسکرول خودکار با برداشتن موس از روی مقالات
    cardBoxes.addEventListener('mouseleave', () => {
        autoScrollTimer = setInterval(scrollNext, 7000);
    });
});
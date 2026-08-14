document.querySelectorAll('.new-range-card').forEach(card => {
    const slider = card.querySelector('.new-dual-slider');
    const minHandle = card.querySelector('.min-handle');
    const maxHandle = card.querySelector('.max-handle');
    const minInput = minHandle.querySelector('.new-handle-input');
    const maxInput = maxHandle.querySelector('.new-handle-input');
    const rangeBar = card.querySelector('.new-slider-range-bar');

    const MIN_VAL = parseFloat(minInput.min);
    const MAX_VAL = parseFloat(maxInput.max);

    if (
        !Number.isFinite(MIN_VAL) ||
        !Number.isFinite(MAX_VAL) ||
        MAX_VAL <= MIN_VAL
    ) {
        return;
    }

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
                    input.dispatchEvent(
                        new Event('input', { bubbles: true })
                    );
                }
            } else {
                if (actualVal >= parseFloat(minInput.value)) {
                    input.value = Math.round(actualVal);
                    input.dispatchEvent(
                        new Event('input', { bubbles: true })
                    );
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
                    input.dispatchEvent(
                        new Event('input', { bubbles: true })
                    );
                }
            } else {
                if (actualVal >= parseFloat(minInput.value)) {
                    input.value = Math.round(actualVal);
                    input.dispatchEvent(
                        new Event('input', { bubbles: true })
                    );
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


// HSB Homepage Blog — isolated carousel
document.addEventListener('DOMContentLoaded', function() {
    const track =
        document.getElementById(
            'hsbHomeBlogTrack'
        );

    const nextButton =
        document.getElementById(
            'hsbHomeBlogNext'
        );

    const prevButton =
        document.getElementById(
            'hsbHomeBlogPrev'
        );

    if (
        !track ||
        !nextButton ||
        !prevButton
    ) {
        return;
    }

    let isPointerDown = false;
    let isDragging = false;
    let blockClickUntil = 0;

    let startX = 0;
    let startScrollLeft = 0;


    const isScrollable = () =>
        track.scrollWidth >
        track.clientWidth + 2;


    const updateState = () => {
        const scrollable =
            isScrollable();

        track.classList.toggle(
            'is-scrollable',
            scrollable
        );

        track.classList.toggle(
            'is-static',
            !scrollable
        );

        nextButton.disabled =
            !scrollable;

        prevButton.disabled =
            !scrollable;

        if (!scrollable) {
            track.scrollLeft = 0;
        }
    };


    const getStep = () => {
        const card =
            track.querySelector(
                '.hsb-home-blog__card'
            );

        if (!card) {
            return 280;
        }

        const style =
            window.getComputedStyle(
                track
            );

        const gap =
            parseFloat(
                style.columnGap
            ) ||
            parseFloat(
                style.gap
            ) ||
            0;

        return (
            card.getBoundingClientRect().width +
            gap
        );
    };


    nextButton.addEventListener(
        'click',
        function() {
            if (!isScrollable()) {
                return;
            }

            track.scrollBy({
                left: -getStep(),
                behavior: 'smooth'
            });
        }
    );


    prevButton.addEventListener(
        'click',
        function() {
            if (!isScrollable()) {
                return;
            }

            track.scrollBy({
                left: getStep(),
                behavior: 'smooth'
            });
        }
    );


    track.addEventListener(
        'pointerdown',
        function(event) {
            if (
                event.pointerType !== 'mouse' ||
                event.button !== 0 ||
                !isScrollable()
            ) {
                return;
            }

            /*
             * فقط آماده‌ی Drag می‌شویم.
             * کلیک ساده هنوز یک کلیک معمولی است.
             */
            isPointerDown = true;
            isDragging = false;

            startX =
                event.clientX;

            startScrollLeft =
                track.scrollLeft;
        }
    );


    track.addEventListener(
        'pointermove',
        function(event) {
            if (
                !isPointerDown ||
                event.pointerType !== 'mouse'
            ) {
                return;
            }

            const deltaX =
                event.clientX -
                startX;

            /*
             * تا وقتی موس حداقل 6px حرکت نکرده،
             * Drag اصلاً فعال نمی‌شود.
             */
            if (
                !isDragging &&
                Math.abs(deltaX) < 6
            ) {
                return;
            }

            if (!isDragging) {
                isDragging = true;

                track.classList.add(
                    'is-dragging'
                );

                track.setPointerCapture(
                    event.pointerId
                );
            }

            track.scrollLeft =
                startScrollLeft -
                deltaX;

            event.preventDefault();
        }
    );


    const stopDrag = (
        event
    ) => {
        if (!isPointerDown) {
            return;
        }

        const wasDragging =
            isDragging;

        isPointerDown = false;
        isDragging = false;

        track.classList.remove(
            'is-dragging'
        );

        if (
            event &&
            track.hasPointerCapture(
                event.pointerId
            )
        ) {
            track.releasePointerCapture(
                event.pointerId
            );
        }

        /*
         * فقط کلیکی که بلافاصله بعد از Drag واقعی
         * ساخته می‌شود را مسدود می‌کنیم.
         */
        if (wasDragging) {
            blockClickUntil =
                Date.now() + 250;
        }
    };


    track.addEventListener(
        'pointerup',
        stopDrag
    );

    track.addEventListener(
        'pointercancel',
        stopDrag
    );


    track.addEventListener(
        'click',
        function(event) {
            if (
                Date.now() >=
                blockClickUntil
            ) {
                return;
            }

            event.preventDefault();
            event.stopPropagation();
        },
        true
    );


    let resizeTimer = null;

    window.addEventListener(
        'resize',
        function() {
            clearTimeout(
                resizeTimer
            );

            resizeTimer =
                setTimeout(
                    updateState,
                    100
                );
        }
    );


    updateState();
});

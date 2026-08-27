document.querySelectorAll('.new-range-card').forEach(card => {
    const slider = card.querySelector('.new-dual-slider');
    const minHandle = card.querySelector('.min-handle');
    const maxHandle = card.querySelector('.max-handle');
    const minInput = minHandle.querySelector('.new-handle-input');
    const maxInput = maxHandle.querySelector('.new-handle-input');
    const rangeBar = card.querySelector('.new-slider-range-bar');
    const minBubble = minHandle.querySelector('.new-tooltip-bubble');
    const maxBubble = maxHandle.querySelector('.new-tooltip-bubble');

    const hasSmartTooltips =
        Boolean(
            (
                card.closest('#hsb-home-search') ||
                card.closest('.shop-dim-search')
            ) &&
            minBubble &&
            maxBubble
        );

    const MIN_VAL = parseFloat(minInput.min);
    const MAX_VAL = parseFloat(maxInput.max);

    if (
        !Number.isFinite(MIN_VAL) ||
        !Number.isFinite(MAX_VAL) ||
        MAX_VAL <= MIN_VAL
    ) {
        return;
    }

    function updateTooltipPositions() {
        if (!hasSmartTooltips) {
            return;
        }

        const cardRect =
            card.getBoundingClientRect();

        const minHandleRect =
            minHandle.getBoundingClientRect();

        const maxHandleRect =
            maxHandle.getBoundingClientRect();

        const minBubbleWidth =
            minBubble.offsetWidth;

        const maxBubbleWidth =
            maxBubble.offsetWidth;

        if (
            !cardRect.width ||
            !minBubbleWidth ||
            !maxBubbleWidth
        ) {
            return;
        }

        /*
         * Tooltip centres normally sit exactly above
         * their handles. They only move when:
         *
         * 1. approaching a card edge
         * 2. approaching the opposite tooltip
         *
         * Slider values / handles remain completely free.
         */
        const EDGE_GAP = 8;
        const TOOLTIP_GAP = 10;

        const minHandleX =
            minHandleRect.left +
            (minHandleRect.width / 2);

        const maxHandleX =
            maxHandleRect.left +
            (maxHandleRect.width / 2);

        const minLeftLimit =
            cardRect.left +
            EDGE_GAP +
            (minBubbleWidth / 2);

        const minRightLimit =
            cardRect.right -
            EDGE_GAP -
            (minBubbleWidth / 2);

        const maxLeftLimit =
            cardRect.left +
            EDGE_GAP +
            (maxBubbleWidth / 2);

        const maxRightLimit =
            cardRect.right -
            EDGE_GAP -
            (maxBubbleWidth / 2);

        const clamp = (
            value,
            lower,
            upper
        ) => Math.max(
            lower,
            Math.min(
                upper,
                value
            )
        );

        let minCenter =
            clamp(
                minHandleX,
                minLeftLimit,
                minRightLimit
            );

        let maxCenter =
            clamp(
                maxHandleX,
                maxLeftLimit,
                maxRightLimit
            );

        /*
         * RTL:
         * Min handle is on the RIGHT.
         * Max handle is on the LEFT.
         *
         * When their bubbles become too close,
         * push Min towards the right and
         * Max towards the left like magnets.
         */
        const requiredSeparation =
            (minBubbleWidth / 2) +
            (maxBubbleWidth / 2) +
            TOOLTIP_GAP;

        let currentSeparation =
            minCenter - maxCenter;

        if (
            currentSeparation <
            requiredSeparation
        ) {
            let deficit =
                requiredSeparation -
                currentSeparation;

            const minRightRoom =
                Math.max(
                    0,
                    minRightLimit -
                    minCenter
                );

            const maxLeftRoom =
                Math.max(
                    0,
                    maxCenter -
                    maxLeftLimit
                );

            /*
             * First distribute the movement equally.
             */
            let minMove =
                Math.min(
                    deficit / 2,
                    minRightRoom
                );

            let maxMove =
                Math.min(
                    deficit / 2,
                    maxLeftRoom
                );

            deficit -=
                minMove +
                maxMove;

            /*
             * If one side hits the card edge,
             * give the remaining movement
             * to the other tooltip.
             */
            if (deficit > 0) {
                const extraMin =
                    Math.min(
                        deficit,
                        minRightRoom -
                        minMove
                    );

                minMove += extraMin;
                deficit -= extraMin;
            }

            if (deficit > 0) {
                const extraMax =
                    Math.min(
                        deficit,
                        maxLeftRoom -
                        maxMove
                    );

                maxMove += extraMax;
            }

            minCenter += minMove;
            maxCenter -= maxMove;
        }

        const minShift =
            minCenter -
            minHandleX;

        const maxShift =
            maxCenter -
            maxHandleX;

        minBubble.style.setProperty(
            '--hsb-tooltip-shift',
            `${minShift}px`
        );

        maxBubble.style.setProperty(
            '--hsb-tooltip-shift',
            `${maxShift}px`
        );
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

        updateTooltipPositions();
    }

    function enableDrag(handle, input, isMin) {
        let isDragging = false;

        /*
         * Number input remains fully editable.
         * Every other part of the handle tooltip
         * — including "از" / "تا" — acts as a drag grip.
         */
        function isTypingTarget(target) {
            return (
                target instanceof Element &&
                Boolean(
                    target.closest(
                        '.new-handle-input'
                    )
                )
            );
        }

        function startDragging(e) {
            if (isTypingTarget(e.target)) {
                return;
            }

            isDragging = true;

            handle.classList.add(
                'is-dragging'
            );

            e.preventDefault();
        }

        function stopDragging() {
            isDragging = false;

            handle.classList.remove(
                'is-dragging'
            );
        }

        handle.addEventListener(
            'mousedown',
            startDragging
        );

        handle.addEventListener(
            'touchstart',
            startDragging,
            {
                passive: false
            }
        );

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

        window.addEventListener(
            'touchmove',
            (e) => {
            if (!isDragging) return;

            e.preventDefault();

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
            },
            {
                passive: false
            }
        );

        window.addEventListener(
            'mouseup',
            stopDragging
        );

        window.addEventListener(
            'touchend',
            stopDragging
        );

        window.addEventListener(
            'touchcancel',
            stopDragging
        );
    }

    enableDrag(minHandle, minInput, true);
    enableDrag(maxHandle, maxInput, false);

    // به‌روزرسانی زمان تایپ مستقیم مقادیر عددی داخل بالون‌ها
    minInput.addEventListener('change', updateSlider);
    maxInput.addEventListener('change', updateSlider);

    // رندر اولیه ابعاد
    updateSlider();

    /*
     * Recalculate magnetic tooltip positions
     * when responsive layout changes.
     */
    window.addEventListener(
        'resize',
        updateTooltipPositions
    );
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


/* ============================================================
   HSB REAL STICKY NAVBAR
   ============================================================ */

(function () {

    function initHsbStickyNavbar() {

        const navbar =
            document.querySelector(
                '.my-navbar'
            );

        const header =
            document.querySelector(
                '.header'
            );

        if (
            !navbar ||
            !header ||
            navbar.dataset.hsbStickyReady === '1'
        ) {
            return;
        }

        navbar.dataset.hsbStickyReady = '1';

        let stickyStart = 0;
        let ticking = false;


        function setNavbarHeight() {

            header.style.setProperty(
                '--hsb-sticky-navbar-height',
                navbar.offsetHeight + 'px'
            );
        }


        function updateStickyState() {

            const shouldStick =
                window.scrollY >= stickyStart;

            navbar.classList.toggle(
                'hsb-navbar-is-sticky',
                shouldStick
            );

            header.classList.toggle(
                'hsb-navbar-sticky-active',
                shouldStick
            );
        }


        function measureNavbar() {

            /*
             * Temporarily return navbar to normal flow so its
             * original document position can be measured.
             */
            navbar.classList.remove(
                'hsb-navbar-is-sticky'
            );

            header.classList.remove(
                'hsb-navbar-sticky-active'
            );

            stickyStart =
                navbar.getBoundingClientRect().top +
                window.scrollY;

            setNavbarHeight();

            updateStickyState();
        }


        function requestStickyUpdate() {

            if (ticking) {
                return;
            }

            ticking = true;

            window.requestAnimationFrame(
                function () {

                    updateStickyState();

                    ticking = false;
                }
            );
        }


        window.addEventListener(
            'scroll',
            requestStickyUpdate,
            {
                passive: true
            }
        );


        window.addEventListener(
            'resize',
            function () {

                window.requestAnimationFrame(
                    measureNavbar
                );
            }
        );


        measureNavbar();
    }


    if (
        document.readyState === 'loading'
    ) {

        document.addEventListener(
            'DOMContentLoaded',
            initHsbStickyNavbar
        );

    } else {

        initHsbStickyNavbar();
    }

})();


/* HSB SEARCH HELP POPOVER JS */

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const popover =
            document.getElementById(
                'hsb-search-help-popover'
            );

        const triggers =
            document.querySelectorAll(
                '.hsb-search-help-trigger'
            );

        if (
            !popover ||
            !triggers.length
        ) {
            return;
        }

        const closeButton =
            popover.querySelector(
                '.hsb-search-help-popover__close'
            );

        let activeTrigger = null;


        function positionPopover(trigger) {

            const triggerRect =
                trigger.getBoundingClientRect();

            const margin = 12;
            const viewportGap = 12;

            popover.style.left = '';
            popover.style.right = '';
            popover.style.top = '';
            popover.style.bottom = '';

            popover.classList.remove(
                'is-above'
            );

            const width =
                Math.min(
                    370,
                    window.innerWidth -
                    (viewportGap * 2)
                );

            popover.style.width =
                `${width}px`;

            const popoverHeight =
                popover.offsetHeight;

            const roomBelow =
                window.innerHeight -
                triggerRect.bottom;

            const showAbove =
                roomBelow <
                popoverHeight + 24 &&
                triggerRect.top >
                popoverHeight + 24;

            let top;

            if (showAbove) {

                top =
                    triggerRect.top -
                    popoverHeight -
                    margin;

                popover.classList.add(
                    'is-above'
                );

            } else {

                top =
                    triggerRect.bottom +
                    margin;
            }

            let left =
                triggerRect.left +
                (triggerRect.width / 2) -
                (width / 2);

            left =
                Math.max(
                    viewportGap,
                    Math.min(
                        window.innerWidth -
                        width -
                        viewportGap,
                        left
                    )
                );

            popover.style.left =
                `${Math.round(left)}px`;

            popover.style.top =
                `${Math.max(
                    viewportGap,
                    Math.round(top)
                )}px`;
        }


        function closeHelp() {

            popover.hidden = true;

            popover.setAttribute(
                'aria-hidden',
                'true'
            );

            triggers.forEach(
                trigger => {
                    trigger.classList.remove(
                        'is-active'
                    );
                }
            );

            activeTrigger = null;
        }


        function openHelp(trigger) {

            activeTrigger = trigger;

            triggers.forEach(
                item => {
                    item.classList.toggle(
                        'is-active',
                        item === trigger
                    );
                }
            );

            popover.hidden = false;

            popover.setAttribute(
                'aria-hidden',
                'false'
            );

            positionPopover(trigger);
        }


        triggers.forEach(
            function (trigger) {

                trigger.addEventListener(
                    'click',
                    function (event) {

                        event.preventDefault();
                        event.stopPropagation();

                        if (
                            activeTrigger === trigger &&
                            !popover.hidden
                        ) {
                            closeHelp();
                            return;
                        }

                        openHelp(trigger);
                    }
                );

            }
        );


        if (closeButton) {

            closeButton.addEventListener(
                'click',
                function (event) {

                    event.stopPropagation();
                    closeHelp();
                }
            );
        }


        document.addEventListener(
            'click',
            function (event) {

                if (popover.hidden) {
                    return;
                }

                if (
                    popover.contains(event.target)
                ) {
                    return;
                }

                closeHelp();
            }
        );


        document.addEventListener(
            'keydown',
            function (event) {

                if (
                    event.key === 'Escape' &&
                    !popover.hidden
                ) {
                    closeHelp();
                }
            }
        );


        window.addEventListener(
            'resize',
            function () {

                if (
                    !popover.hidden &&
                    activeTrigger
                ) {
                    positionPopover(
                        activeTrigger
                    );
                }
            }
        );

    }
);

/* END HSB SEARCH HELP POPOVER JS */

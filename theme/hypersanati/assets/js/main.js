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
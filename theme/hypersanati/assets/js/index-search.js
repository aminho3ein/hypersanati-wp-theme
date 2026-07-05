
document.addEventListener('DOMContentLoaded', function () {
    const shopUrl = (typeof hypersanatiSearch !== 'undefined' && hypersanatiSearch.shopUrl)
        ? hypersanatiSearch.shopUrl
        : '/shop/';

    const exactBtn = document.getElementById('exact-search-btn');
    const approxBtn = document.getElementById('approximate-search-btn');

    function getRangeValues(cardId) {
        const card = document.getElementById(cardId);
        if (!card) {
            return { min: '', max: '' };
        }

        const inputs = card.querySelectorAll('.new-handle-input');
        return {
            min: inputs[0] ? inputs[0].value : '',
            max: inputs[1] ? inputs[1].value : '',
        };
    }

    function redirectToShop(params) {
        const query = new URLSearchParams(params);
        window.location.href = shopUrl + (shopUrl.includes('?') ? '&' : '?') + query.toString();
    }

    if (exactBtn) {
        exactBtn.addEventListener('click', function () {
            const inner = document.getElementById('new-inner-dia')?.value.trim() || '';
            const outer = document.getElementById('new-outer-dia')?.value.trim() || '';
            const height = document.getElementById('new-height')?.value.trim() || '';

            if (!inner && !outer && !height) {
                alert('حداقل یکی از ابعاد را وارد کنید.');
                return;
            }

            const params = { dimension_search: 'exact' };
            if (inner) params.inner = inner;
            if (outer) params.outer = outer;
            if (height) params.height = height;

            redirectToShop(params);
        });
    }

    if (approxBtn) {
        approxBtn.addEventListener('click', function () {
            const inner = getRangeValues('range-inner');
            const outer = getRangeValues('range-outer');
            const height = getRangeValues('range-height');

            redirectToShop({
                dimension_search: 'approx',
                inner_min: inner.min,
                inner_max: inner.max,
                outer_min: outer.min,
                outer_max: outer.max,
                height_min: height.min,
                height_max: height.max,
            });
        });
    }
});

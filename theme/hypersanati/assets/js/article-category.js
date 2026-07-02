document.addEventListener("DOMContentLoaded", function () {

    const items = document.querySelectorAll(".discount-slider a");

    if (!items.length) return;

    // اگر فقط 1 محصول داریم → اسلایدر خاموش
    if (items.length === 1) {
        items[0].classList.add("active");
        return;
    }

    let index = 0;

    function showSlide() {

        items.forEach(item => item.classList.remove("active"));

        items[index].classList.add("active");

        index = (index + 1) % items.length;
    }

    showSlide();
    setInterval(showSlide, 6000);

});



document.addEventListener("DOMContentLoaded", function () {

    const container = document.querySelector(".article-category-main");

    document.querySelectorAll(".article-pagination-btn").forEach(btn => {

        btn.addEventListener("click", function (e) {
            e.preventDefault();

            const page = this.getAttribute("data-page");

            fetch(`/wp-admin/admin-ajax.php?action=load_posts&page=${page}`)
                .then(res => res.text())
                .then(html => {
                    container.innerHTML = html;
                });

        });

    });

});
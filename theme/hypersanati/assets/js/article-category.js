document.addEventListener("DOMContentLoaded", function () {

    const postsWrapper = document.querySelector(".posts-wrapper");
    const paginationBox = document.querySelector(".article-pagination");

    if (!postsWrapper) return;

    document.addEventListener("click", function (e) {

        const btn = e.target.closest(".article-pagination-btn");
        if (!btn) return;

        e.preventDefault();

        const page =
            btn.dataset.page ||
            btn.getAttribute("data-page") ||
            btn.textContent;

        const pageNumber = parseInt(page);

        if (!pageNumber) return;

        fetch(`/wp-admin/admin-ajax.php?action=load_posts&paged=${pageNumber}`)
            .then(res => res.json())
            .then(data => {

                if (!data.posts) return;

                // 🔥 POSTS
                postsWrapper.innerHTML = data.posts;

                // 🔥 PAGINATION
                if (paginationBox && data.pagination) {
                    paginationBox.innerHTML = data.pagination;
                }

                // smooth scroll
                postsWrapper.scrollIntoView({
                    behavior: "smooth",
                    block: "start"
                });

            });

    });

});
document.addEventListener("DOMContentLoaded", function () {
  const wrapper = document.querySelector(".posts-wrapper");
  const pagination = document.querySelector(".article-pagination");
  const section = document.querySelector(".article-category-section");

  if (!wrapper || !section) {
    return;
  }

  document.addEventListener("click", function (event) {
    const button = event.target.closest(".article-pagination-btn");

    if (!button) {
      return;
    }

    event.preventDefault();

    const page = parseInt(button.dataset.page || "0", 10);

    if (!page) {
      return;
    }

    const params = new URLSearchParams({
      action: "load_posts",
      paged: String(page),
      category_id: section.dataset.categoryId || "0"
    });

    const ajaxUrl =
      section.dataset.ajaxUrl ||
      "/wp-admin/admin-ajax.php";

    fetch(ajaxUrl + "?" + params.toString(), {
      credentials: "same-origin"
    })
      .then(function (response) {
        if (!response.ok) {
          throw new Error("HTTP " + response.status);
        }

        return response.json();
      })
      .then(function (data) {
        if (!data.posts) {
          return;
        }

        wrapper.innerHTML = data.posts;

        if (pagination && data.pagination) {
          pagination.innerHTML = data.pagination;
        }

        wrapper.scrollIntoView({
          behavior: "smooth",
          block: "start"
        });
      })
      .catch(function (error) {
        console.error("Article pagination failed:", error);
      });
  });
});

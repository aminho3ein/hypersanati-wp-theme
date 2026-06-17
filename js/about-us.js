const titleItems = document.querySelectorAll(".about-hero-title-item");
let activeIndex = 0;

if (titleItems.length > 0) {
titleItems[0].classList.add("is-active");

setInterval(() => {
  titleItems[activeIndex].classList.remove("is-active");
  activeIndex = (activeIndex + 1) % titleItems.length;
  titleItems[activeIndex].classList.add("is-active");
}, 2000);
}

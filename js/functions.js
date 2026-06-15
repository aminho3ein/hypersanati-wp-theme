// Hamburger menu toggle
const hamburgerBtn = document.getElementById("hamburgerBtn");
const mobileMenu = document.getElementById("mobileMenu");
const mobileOverlay = document.getElementById("mobileOverlay");

hamburgerBtn.addEventListener("click", () => {
  const isOpen = mobileMenu.classList.contains("open");
  if (isOpen) {
    closeMobileMenu();
  } else {
    openMobileMenu();
  }
});

function openMobileMenu() {
  mobileMenu.classList.add("open");
  mobileOverlay.classList.add("open");
  hamburgerBtn.classList.add("active");
  hamburgerBtn.setAttribute("aria-expanded", "true");
  document.body.style.overflow = "hidden";
}

function closeMobileMenu() {
  mobileMenu.classList.remove("open");
  mobileOverlay.classList.remove("open");
  hamburgerBtn.classList.remove("active");
  hamburgerBtn.setAttribute("aria-expanded", "false");
  document.body.style.overflow = "";
}



////////////////////////===================cards handle btns

document.addEventListener("DOMContentLoaded", function () {
  const scrollContainer = document.getElementById("blogScroll");
  const rightBtn = document.getElementById("scrollRightBtn");
  const leftBtn = document.getElementById("scrollLeftBtn");

  if (!scrollContainer || !rightBtn || !leftBtn) return;

  function getScrollAmount() {
    const card = scrollContainer.querySelector(".blog-card");

    if (!card) return 300;

    const cardStyle = window.getComputedStyle(card);
    const marginLeft = parseFloat(cardStyle.marginLeft) || 0;
    const marginRight = parseFloat(cardStyle.marginRight) || 0;

    return card.offsetWidth + marginLeft + marginRight;
  }

  rightBtn.addEventListener("click", function () {
    scrollContainer.scrollBy({
      left: getScrollAmount(),
      behavior: "smooth"
    });
  });

  leftBtn.addEventListener("click", function () {
    scrollContainer.scrollBy({
      left: -getScrollAmount(),
      behavior: "smooth"
    });
  });
});

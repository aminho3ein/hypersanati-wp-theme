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
      behavior: "smooth",
    });
  });

  leftBtn.addEventListener("click", function () {
    scrollContainer.scrollBy({
      left: -getScrollAmount(),
      behavior: "smooth",
    });
  });
});

////////////////////////////////  single-product-meta-tabs-feat
// ۱. منطق تب‌ها (بدون تغییر و کاملاً سالم)
document.querySelectorAll(".product-meta-nav button").forEach((btn) => {
  btn.addEventListener("click", function () {
    document
      .querySelectorAll(".tab-panel")
      .forEach((p) => p.classList.remove("active"));

    document
      .querySelectorAll(".product-meta-nav button")
      .forEach((b) => b.classList.remove("active"));

    document.getElementById(this.dataset.tab).classList.add("active");

    this.classList.add("active");
  });
});

// ۲. تفکیک دکمه‌های ثبت پرسش و ثبت نظر بر اساس Scope والد
// دکمه ثبت پرسش (موجود در تب پرسش و پاسخ)
const addQuestionBtn = document.querySelector("#qa .add-product-qestion");
if (addQuestionBtn) {
  addQuestionBtn.addEventListener("click", function () {
    // منطق یا باز شدن مودال مربوط به ثبت پرسش
    console.log("ثبت پرسش کلیک شد");
  });
}

// دکمه‌های ثبت نظر (موجود در تب نظرات - چون دو دکمه با این کلاس در این تب داری از QuerySelectorAll استفاده می‌کنیم)
const addReviewBtns = document.querySelectorAll(
  "#reviews .add-product-qestion",
);
addReviewBtns.forEach((btn) => {
  btn.addEventListener("click", function () {
    // منطق یا باز شدن مودال مربوط به ثبت نظر
    console.log("ثبت نظر کلیک شد");
  });
});

////////////////////////=================== related products
document.querySelectorAll(".relevent-sec").forEach((section) => {
  const slider = section.querySelector(".blogScroll");
  const rightBtn = section.querySelector(".scrollRightBtn");
  const leftBtn = section.querySelector(".scrollLeftBtn");

  rightBtn.addEventListener("click", () => {
    slider.scrollBy({
      left: -300,
      behavior: "smooth",
    });
  });

  leftBtn.addEventListener("click", () => {
    slider.scrollBy({
      left: 300,
      behavior: "smooth",
    });
  });
});
///////////////profile logics

document.addEventListener("DOMContentLoaded", () => {
  /* ================================
       Profile navigation section
       اتصال دکمه‌های سمت راست به محتوای مربوطه
    ================================ */

  const profileButtons = document.querySelectorAll(".profile-reqtangle button");

  const profileSectionsMap = [
    {
      buttonIndex: 0,
      sectionSelector:
        ".all-elements > .info-sec:not(.perchase-history):not(.wishlist):not(.support-section):not(.user-rank)",
    },
    {
      buttonIndex: 1,
      sectionSelector: ".perchase-history",
    },
    {
      buttonIndex: 2,
      sectionSelector: ".wishlist",
    },
    {
      buttonIndex: 3,
      sectionSelector: ".support-section",
    },
    {
      buttonIndex: 4,
      sectionSelector: ".user-rank",
    },
  ];

  const profileSections = profileSectionsMap
    .map((item) => ({
      ...item,
      button: profileButtons[item.buttonIndex],
      section: document.querySelector(item.sectionSelector),
    }))
    .filter((item) => item.button);

  const allProfileSections = profileSections
    .map((item) => item.section)
    .filter(Boolean);

  const showProfileSection = (targetItem) => {
    if (!targetItem.section) {
      console.warn(
        "برای این دکمه هنوز سکشن HTML فعال یا موجود نیست:",
        targetItem.button.textContent.trim(),
      );
      return;
    }

    profileButtons.forEach((button) => button.classList.remove("active"));
    targetItem.button.classList.add("active");

    allProfileSections.forEach((section) => {
      section.hidden = section !== targetItem.section;
    });
  };

  profileSections.forEach((item) => {
    item.button.addEventListener("click", () => {
      showProfileSection(item);
    });
  });

  const firstAvailableProfileSection =
    profileSections.find((item) => item.section) || profileSections[0];

  if (firstAvailableProfileSection) {
    showProfileSection(firstAvailableProfileSection);
  }

  /* ================================
       Logout button section
       فعلاً فقط برای جلوگیری از رفتار پیش‌فرض
    ================================ */

  const logoutButton = profileButtons[5];

  if (logoutButton) {
    logoutButton.addEventListener("click", () => {
      profileButtons.forEach((button) => button.classList.remove("active"));
      logoutButton.classList.add("active");

      console.info("دکمه خروج کلیک شد؛ منطق خروج باید به بک‌اند وصل شود.");
    });
  }

  /* ================================
       Support ticket tabs section
       کد قبلی پشتیبانی با بررسی امن‌تر
    ================================ */

  const ticketButtons = document.querySelectorAll(".ticket-btn");
  const ticketPanels = document.querySelectorAll(".ticket-panel");

  ticketButtons.forEach((button) => {
    button.addEventListener("click", () => {
      ticketButtons.forEach((item) => item.classList.remove("active"));
      button.classList.add("active");

      const tab = button.dataset.ticketTab;
      const targetPanel = document.getElementById("ticket-" + tab);

      ticketPanels.forEach((panel) => {
        panel.classList.remove("active");
      });

      if (targetPanel) {
        targetPanel.classList.add("active");
      } else {
        console.warn("پنل تیکت پیدا نشد:", "ticket-" + tab);
      }
    });
  });
});
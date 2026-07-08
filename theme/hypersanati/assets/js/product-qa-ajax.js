(function ($) {
  "use strict";

  function getQaPanel() {
    return $("#qa.product-qa-panel");
  }

  function getProductId() {
    return getQaPanel().data("product-id");
  }

  function getNonce() {
    return getQaPanel().data("nonce");
  }

  function showQaMessage(message, type) {
    const $box = $(".qa-ajax-message");

    if (!message) {
      $box.removeClass("is-success is-error").empty().hide();
      return;
    }

    $box
      .removeClass("is-success is-error")
      .addClass(type === "error" ? "is-error" : "is-success")
      .html(message)
      .fadeIn(150);
  }

  function setQaLoading(isLoading) {
    const $panel = getQaPanel();

    if (isLoading) {
      $panel.addClass("is-loading");
    } else {
      $panel.removeClass("is-loading");
    }
  }

  function updateQaList(data) {
    if (data.html !== undefined) {
      $("#qa .product-qa").html(data.html);
    }

    if (data.count_fa !== undefined) {
      $("#qa .qa-count-value").text(data.count_fa);
    }
  }

  function activateQaTab() {
    const $qaPanel = $("#qa");

    if (!$qaPanel.length) {
      return;
    }

    $(".tab-panel").removeClass("active");
    $qaPanel.addClass("active");

    $(".product-meta-nav button, .product-meta-nav a").removeClass("active");

    $(
      '.product-meta-nav button[data-tab="qa"], ' +
        '.product-meta-nav button[data-target="qa"], ' +
        '.product-meta-nav button[data-target="#qa"], ' +
        '.product-meta-nav a[href="#qa"]'
    ).addClass("active");
  }

  function scrollToQa() {
    const $qaPanel = $("#qa");

    if (!$qaPanel.length) {
      return;
    }

    $("html, body").animate(
      {
        scrollTop: $qaPanel.offset().top - 90,
      },
      300
    );
  }

  function loadQa(sort) {
    const productId = getProductId();
    const nonce = getNonce();

    setQaLoading(true);
    showQaMessage("", "");

    $.ajax({
      url: themeProductQa.ajaxUrl,
      method: "POST",
      dataType: "json",
      data: {
        action: "theme_load_product_qa",
        product_id: productId,
        nonce: nonce,
        qa_sort: sort,
      },
    })
      .done(function (response) {
        if (!response.success) {
          showQaMessage(response.data && response.data.message ? response.data.message : "خطایی رخ داد.", "error");
          return;
        }

        updateQaList(response.data);
        window.history.replaceState(null, "", "#qa");
        activateQaTab();
      })
      .fail(function () {
        showQaMessage("ارتباط با سرور برقرار نشد.", "error");
      })
      .always(function () {
        setQaLoading(false);
      });
  }

  $(document).on("click", "#qa .qa-sort-btn", function (e) {
    e.preventDefault();

    const $button = $(this);
    const sort = $button.data("sort") || "newest";

    $("#qa .qa-sort-btn").removeClass("active");
    $button.addClass("active");

    loadQa(sort);
  });

  $(document).on("submit", "#qa .product-question-form", function (e) {
    e.preventDefault();

    const $form = $(this);
    const productId = getProductId();
    const nonce = getNonce();

    const formData = new FormData(this);
    formData.append("action", "theme_submit_product_question");
    formData.append("product_id", productId);
    formData.append("nonce", nonce);

    setQaLoading(true);
    showQaMessage("", "");

    $.ajax({
      url: themeProductQa.ajaxUrl,
      method: "POST",
      dataType: "json",
      data: formData,
      processData: false,
      contentType: false,
    })
      .done(function (response) {
        if (!response.success) {
          showQaMessage(response.data && response.data.message ? response.data.message : "ثبت پرسش انجام نشد.", "error");
          return;
        }

        updateQaList(response.data);
        showQaMessage(response.data.message, "success");
        $form[0].reset();

        $("#qa .qa-sort-btn").removeClass("active");
        $('#qa .qa-sort-btn[data-sort="newest"]').addClass("active");

        window.history.replaceState(null, "", "#qa");
        activateQaTab();
      })
      .fail(function () {
        showQaMessage("ارتباط با سرور برقرار نشد.", "error");
      })
      .always(function () {
        setQaLoading(false);
      });
  });

  $(document).on("submit", "#qa .qa-answer-form", function (e) {
    e.preventDefault();

    const $form = $(this);
    const productId = getProductId();
    const nonce = getNonce();

    const formData = new FormData(this);
    formData.append("action", "theme_submit_product_answer");
    formData.append("product_id", productId);
    formData.append("nonce", nonce);

    setQaLoading(true);
    showQaMessage("", "");

    $.ajax({
      url: themeProductQa.ajaxUrl,
      method: "POST",
      dataType: "json",
      data: formData,
      processData: false,
      contentType: false,
    })
      .done(function (response) {
        if (!response.success) {
          showQaMessage(response.data && response.data.message ? response.data.message : "ثبت پاسخ انجام نشد.", "error");
          return;
        }

        updateQaList(response.data);
        showQaMessage(response.data.message, "success");

        $("#qa .qa-sort-btn").removeClass("active");
        $('#qa .qa-sort-btn[data-sort="newest"]').addClass("active");

        window.history.replaceState(null, "", "#qa");
        activateQaTab();
      })
      .fail(function () {
        showQaMessage("ارتباط با سرور برقرار نشد.", "error");
      })
      .always(function () {
        setQaLoading(false);
      });
  });

  $(document).on("submit", "#qa .same-question-form", function (e) {
    e.preventDefault();

    const $form = $(this);
    const productId = getProductId();
    const nonce = getNonce();

    $.ajax({
      url: themeProductQa.ajaxUrl,
      method: "POST",
      dataType: "json",
      data: {
        action: "theme_same_product_question",
        product_id: productId,
        question_id: $form.find('input[name="question_id"]').val(),
        nonce: nonce,
      },
    })
      .done(function (response) {
        if (!response.success) {
          showQaMessage(response.data && response.data.message ? response.data.message : "درخواست انجام نشد.", "error");
          return;
        }

        $form.find(".same-question-count").text(response.data.count_fa);
        showQaMessage(response.data.message, "success");
        window.history.replaceState(null, "", "#qa");
        activateQaTab();
      })
      .fail(function () {
        showQaMessage("ارتباط با سرور برقرار نشد.", "error");
      });
  });

  $(document).on("click", "#qa .add-product-question, #qa .add-product-qestion", function () {
    window.history.replaceState(null, "", "#qa");
    activateQaTab();
  });

  $(window).on("load", function () {
    if (window.location.hash === "#qa") {
      activateQaTab();
      scrollToQa();
    }
  });
})(jQuery);
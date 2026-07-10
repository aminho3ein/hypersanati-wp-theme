/**
 * Dashboard JavaScript - Hypersanati Theme
 * مدیریت تب‌ها و فرم‌های داشبورد
 */

(function() {
  'use strict';

  // ====================================================================
  // Tab Switching
  // ====================================================================
  function initTabSwitching() {
    const navButtons = document.querySelectorAll('.dashboard-nav-btn[data-tab]');
    const tabs = document.querySelectorAll('.dashboard-tab');

    navButtons.forEach(function(button) {
      button.addEventListener('click', function() {
        const targetTab = this.getAttribute('data-tab');

        // Remove active class from all buttons and tabs
        navButtons.forEach(function(btn) {
          btn.classList.remove('active');
        });

        tabs.forEach(function(tab) {
          tab.classList.remove('active');
        });

        // Add active class to clicked button
        this.classList.add('active');

        // Show target tab
        const targetTabElement = document.getElementById('tab-' + targetTab);
        if (targetTabElement) {
          targetTabElement.classList.add('active');
        }

        // Save active tab to localStorage
        try {
          localStorage.setItem('dashboard_active_tab', targetTab);
        } catch (e) {
          console.warn('localStorage not available');
        }

        // Scroll to top
        window.scrollTo({ top: 0, behavior: 'smooth' });
      });
    });

    // Restore last active tab from localStorage
    try {
      const savedTab = localStorage.getItem('dashboard_active_tab');
      if (savedTab) {
        const savedButton = document.querySelector('.dashboard-nav-btn[data-tab="' + savedTab + '"]');
        if (savedButton) {
          savedButton.click();
        }
      }
    } catch (e) {
      console.warn('localStorage not available');
    }
  }

  // ====================================================================
  // Form Handling
  // ====================================================================
  function initFormHandling() {
    // Profile Form
    const profileForm = document.querySelector('.profile-form');
    if (profileForm) {
      profileForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const submitButton = this.querySelector('button[type="submit"]');
        const originalText = submitButton.innerHTML;
        
        // Show loading state
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> در حال ذخیره...';

        // Submit form via AJAX
        const formData = new FormData(this);
        formData.append('action', 'update_user_profile');
        formData.append('nonce', window.dashboardData ? window.dashboardData.nonce : '');

        fetch(window.dashboardData ? window.dashboardData.ajaxurl : '/wp-admin/admin-ajax.php', {
          method: 'POST',
          body: formData
        })
        .then(function(response) {
          return response.json();
        })
        .then(function(data) {
          if (data.success) {
            showNotification('اطلاعات شما با موفقیت ذخیره شد', 'success');
            
            // Update user name in sidebar if changed
            if (data.data && data.data.user) {
              const userNameElement = document.querySelector('.dashboard-user-welcome h3');
              if (userNameElement) {
                userNameElement.textContent = data.data.user.first_name + ' ' + data.data.user.last_name;
              }
            }
          } else {
            showNotification(data.data ? data.data.message : 'خطا در ذخیره اطلاعات', 'error');
          }
        })
        .catch(function(error) {
          showNotification('خطا در ارتباط با سرور', 'error');
          console.error('Error:', error);
        })
        .finally(function() {
          // Restore button state
          submitButton.disabled = false;
          submitButton.innerHTML = originalText;
        });
      });
    }

    // Password Form
    const passwordForm = document.querySelector('.password-form');
    if (passwordForm) {
      passwordForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const newPassword = this.querySelector('input[name="new_password"]').value;
        const confirmPassword = this.querySelector('input[name="confirm_password"]').value;

        // Validate passwords match
        if (newPassword !== confirmPassword) {
          showNotification('رمز عبور و تکرار آن یکسان نیستند', 'error');
          return;
        }

        // Validate password strength
        if (newPassword.length < 6) {
          showNotification('رمز عبور باید حداقل ۶ کاراکتر باشد', 'error');
          return;
        }

        const submitButton = this.querySelector('button[type="submit"]');
        const originalText = submitButton.innerHTML;

        // Show loading state
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> در حال تغییر...';

        // Submit form via AJAX
        const formData = new FormData(this);
        formData.append('action', 'change_user_password');
        formData.append('nonce', window.dashboardData ? window.dashboardData.nonce : '');

        fetch(window.dashboardData ? window.dashboardData.ajaxurl : '/wp-admin/admin-ajax.php', {
          method: 'POST',
          body: formData
        })
        .then(function(response) {
          return response.json();
        })
        .then(function(data) {
          if (data.success) {
            showNotification('رمز عبور با موفقیت تغییر یافت', 'success');
            passwordForm.reset();
          } else {
            showNotification(data.data ? data.data.message : 'خطا در تغییر رمز عبور', 'error');
          }
        })
        .catch(function(error) {
          showNotification('خطا در ارتباط با سرور', 'error');
          console.error('Error:', error);
        })
        .finally(function() {
          // Restore button state
          submitButton.disabled = false;
          submitButton.innerHTML = originalText;
        });
      });
    }
  }

  // ====================================================================
  // Notification System
  // ====================================================================
  function showNotification(message, type) {
    type = type || 'info';

    // Remove existing notification
    const existingNotification = document.querySelector('.dashboard-notification');
    if (existingNotification) {
      existingNotification.remove();
    }

    // Create notification element
    const notification = document.createElement('div');
    notification.className = 'dashboard-notification dashboard-notification--' + type;
    
    const icon = type === 'success' ? 'check-circle' : 
                 type === 'error' ? 'exclamation-circle' : 
                 'info-circle';

    notification.innerHTML = '<i class="fa-solid fa-' + icon + '"></i>' +
                            '<span>' + message + '</span>' +
                            '<button class="notification-close"><i class="fa-solid fa-times"></i></button>';

    // Add to page
    document.body.appendChild(notification);

    // Close button
    notification.querySelector('.notification-close').addEventListener('click', function() {
      notification.remove();
    });

    // Auto remove after 5 seconds
    setTimeout(function() {
      if (notification.parentElement) {
        notification.classList.add('dashboard-notification--fade-out');
        setTimeout(function() {
          notification.remove();
        }, 300);
      }
    }, 5000);
  }

  // Add notification styles dynamically
  function injectNotificationStyles() {
    const style = document.createElement('style');
    style.textContent = `
      .dashboard-notification {
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        background: white;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        z-index: 10000;
        animation: slideDown 0.3s ease-out;
        max-width: 90%;
        direction: rtl;
      }

      @keyframes slideDown {
        from {
          opacity: 0;
          transform: translateX(-50%) translateY(-20px);
        }
        to {
          opacity: 1;
          transform: translateX(-50%) translateY(0);
        }
      }

      .dashboard-notification--fade-out {
        animation: slideUp 0.3s ease-out;
      }

      @keyframes slideUp {
        from {
          opacity: 1;
          transform: translateX(-50%) translateY(0);
        }
        to {
          opacity: 0;
          transform: translateX(-50%) translateY(-20px);
        }
      }

      .dashboard-notification--success {
        border-right: 4px solid #28a745;
      }

      .dashboard-notification--error {
        border-right: 4px solid #dc3545;
      }

      .dashboard-notification--info {
        border-right: 4px solid #17a2b8;
      }

      .dashboard-notification i:first-child {
        font-size: 1.3rem;
      }

      .dashboard-notification--success i:first-child {
        color: #28a745;
      }

      .dashboard-notification--error i:first-child {
        color: #dc3545;
      }

      .dashboard-notification--info i:first-child {
        color: #17a2b8;
      }

      .dashboard-notification span {
        flex: 1;
        font-size: 0.95rem;
        font-weight: 500;
        color: #333;
      }

      .notification-close {
        background: none;
        border: none;
        cursor: pointer;
        padding: 0.25rem;
        color: #999;
        font-size: 1.1rem;
        transition: color 0.2s;
      }

      .notification-close:hover {
        color: #333;
      }
    `;
    document.head.appendChild(style);
  }

  // ====================================================================
  // Edit Address Buttons
  // ====================================================================
  function initAddressButtons() {
    const editButtons = document.querySelectorAll('.edit-address-btn');
    
    editButtons.forEach(function(button) {
      button.addEventListener('click', function() {
        showNotification('برای ویرایش آدرس، لطفاً در صفحه تسویه حساب اقدام کنید', 'info');
      });
    });
  }

  // ====================================================================
  // Order Items Toggle (Mobile)
  // ====================================================================
  function initOrderItemsToggle() {
    const orderCards = document.querySelectorAll('.order-card');

    orderCards.forEach(function(card) {
      const orderHeader = card.querySelector('.order-header');
      const orderItems = card.querySelector('.order-items');

      if (orderHeader && orderItems && window.innerWidth < 768) {
        orderHeader.style.cursor = 'pointer';
        orderItems.style.display = 'none';

        orderHeader.addEventListener('click', function() {
          const isVisible = orderItems.style.display !== 'none';
          orderItems.style.display = isVisible ? 'none' : 'flex';
        });
      }
    });
  }

  // ====================================================================
  // Initialize on DOM Ready
  // ====================================================================
  document.addEventListener('DOMContentLoaded', function() {
    initTabSwitching();
    initFormHandling();
    initAddressButtons();
    initOrderItemsToggle();
    injectNotificationStyles();
  });

  // ====================================================================
  // Handle Window Resize
  // ====================================================================
  let resizeTimer;
  window.addEventListener('resize', function() {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(function() {
      initOrderItemsToggle();
    }, 250);
  });

})();

// ====================================================================
// TICKET SYSTEM FUNCTIONS
// ====================================================================

/**
 * Initialize Ticket System
 */
function initTicketSystem() {
  initTicketReplyToggle();
  initTicketForms();
}

/**
 * Toggle Reply Form
 */
function initTicketReplyToggle() {
  const replyButtons = document.querySelectorAll('.btn-reply');
  
  replyButtons.forEach(function(button) {
    button.addEventListener('click', function() {
      const ticketId = this.getAttribute('data-ticket-id');
      const ticketCard = document.querySelector('.ticket-card[data-ticket-id="' + ticketId + '"]');
      
      if (ticketCard) {
        const replyForm = ticketCard.querySelector('.ticket-reply-form');
        
        if (replyForm) {
          const isVisible = replyForm.style.display !== 'none';
          replyForm.style.display = isVisible ? 'none' : 'block';
          
          if (!isVisible) {
            // Scroll to form and focus textarea
            setTimeout(function() {
              replyForm.scrollIntoView({ behavior: 'smooth', block: 'center' });
              const textarea = replyForm.querySelector('textarea');
              if (textarea) {
                textarea.focus();
              }
            }, 100);
          }
        }
      }
    });
  });

  // Cancel reply buttons
  const cancelButtons = document.querySelectorAll('.btn-cancel-reply');
  
  cancelButtons.forEach(function(button) {
    button.addEventListener('click', function() {
      const replyForm = this.closest('.ticket-reply-form');
      if (replyForm) {
        replyForm.style.display = 'none';
        replyForm.querySelector('textarea').value = '';
      }
    });
  });
}

/**
 * Handle Ticket Forms
 */
function initTicketForms() {
  // New Ticket Form
  const newTicketForm = document.getElementById('newTicketForm');
  
  if (newTicketForm) {
    newTicketForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      const submitButton = this.querySelector('button[type="submit"]');
      const originalText = submitButton.innerHTML;
      
      // Validate form
      const category = this.querySelector('[name="ticket_category"]').value;
      const subject = this.querySelector('[name="ticket_subject"]').value;
      const message = this.querySelector('[name="ticket_message"]').value;
      
      if (!category || !subject || !message) {
        showNotification('لطفاً تمام فیلدهای الزامی را پر کنید', 'error');
        return;
      }
      
      // Show loading state
      submitButton.disabled = true;
      submitButton.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> در حال ارسال...';
      
      // Submit form
      this.submit();
      
      // Show success message (will be reloaded by server)
      setTimeout(function() {
        showNotification('تیکت شما ارسال شد و به زودی پاسخ داده خواهد شد', 'success');
      }, 500);
    });
  }

  // Reply Forms
  const replyForms = document.querySelectorAll('.reply-ticket-form');
  
  replyForms.forEach(function(form) {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      
      const submitButton = this.querySelector('button[type="submit"]');
      const originalText = submitButton.innerHTML;
      const textarea = this.querySelector('textarea[name="reply_message"]');
      
      if (!textarea.value.trim()) {
        showNotification('لطفاً متن پاسخ را وارد کنید', 'error');
        return;
      }
      
      // Show loading state
      submitButton.disabled = true;
      submitButton.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> در حال ارسال...';
      
      // Submit form
      this.submit();
      
      // Show success message (will be reloaded by server)
      setTimeout(function() {
        showNotification('پاسخ شما ارسال شد', 'success');
      }, 500);
    });
  });

  // Close Ticket Forms
  const closeTicketForms = document.querySelectorAll('.close-ticket-form');
  
  closeTicketForms.forEach(function(form) {
    form.addEventListener('submit', function(e) {
      if (!confirm('آیا از بستن این تیکت اطمینان دارید؟')) {
        e.preventDefault();
        return false;
      }
      
      const submitButton = this.querySelector('button[type="submit"]');
      submitButton.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> در حال بستن...';
      submitButton.disabled = true;
    });
  });
}

// Initialize ticket system when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
  initTicketSystem();
});





document.addEventListener("DOMContentLoaded", function () {
  const params = new URLSearchParams(window.location.search);
  const shouldOpenSupport =
    params.get("dashboard_tab") === "support" ||
    window.location.hash === "#tab-support";

  if (!shouldOpenSupport) {
    return;
  }

  const supportButton = document.querySelector('.dashboard-nav-btn[data-tab="support"]');
  const allButtons = document.querySelectorAll(".dashboard-nav-btn[data-tab]");
  const allTabs = document.querySelectorAll(".dashboard-tab");

  allButtons.forEach(function (button) {
    button.classList.remove("active");
  });

  allTabs.forEach(function (tab) {
    tab.classList.remove("active");
  });

  if (supportButton) {
    supportButton.classList.add("active");
  }

  const supportTab = document.getElementById("tab-support");

  if (supportTab) {
    supportTab.classList.add("active");
  }
});
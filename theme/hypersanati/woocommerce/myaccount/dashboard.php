<?php 
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// نمایش فرم OTP در صورت نیاز (بدون ساختار کامل هدر)
get_template_part('/form-otp-login'); 
?>

   
      <!-- profile-btn-navigation -->
      <div class="my-profie-sec w-100 w-lg-auto">
        <div
          class="profile-reqtangle w-100 flex-row flex-wrap flex-lg-column h-auto py-3 px-2"
        >
          <button class="profile-btn w-100 w-sm-auto fs-14">حساب کاربری من</button>
          <button class="profile-btn w-100 w-sm-auto fs-14">تاریخچه خرید</button>
          <button class="profile-btn w-100 w-sm-auto fs-14">لیست احتمالا بخرم</button>
          <button class="profile-btn w-100 w-sm-auto fs-14">پشتیبانی</button>
          <button class="profile-btn w-100 w-sm-auto fs-14">رتبه من</button>
          <button class="profile-btn w-100 w-sm-auto fs-14">خروج از حساب کاربری</button>
        </div>
      </div>

      <!-- profile-sectoins -->

            <h5>حساب کاربری من</h5>
            <h5>شماره مشتری: 270612</h5>
          </div>
          <hr />
          <div class="profile-info-section-description w-100">
            <h6>بخش حساب من</h6>
            <p>
              به بخش حساب من خوش آمدید. در اینجا می‌توانید اطلاعات شخصی خود را
              انتخاب کنید و اطلاعات و سفارشات خود را مدیریت کنید.
            </p>
          </div>
        </div>
        <div class="address-info-section w-100">
          <div class="profile-info-section-title w-100">
            <h5>آدرس من</h5>
          </div>
          <hr />
          <div class="profile-info-section-description w-100">
            <p class="warning-text">
              هیچ آدرسی موجود نیست. آدرس جدیدی اضافه کنید
            </p>
            <div class="btn-of-edit-info-frame">
              <a href="#" class="btn-of-edit-info"><p>+ ویرایش اطلاعات</p></a>
            </div>
          </div>
        </div>
        <div class="user-ifo-section">
          <div class="profile-info-section-title w-100">
            <h5>اطلاعات شخصی من</h5>
          </div>
          <hr />
          <div class="profile-info-section-description w-100">
            <p>عنوان اجتماعی</p>
            <form action="">
              <div class="ceckbox-form-section">
                <div class="male">
                  <input type="checkbox" id="male" name="male" value="male" />
                  <label for="vehicle1">آقا</label><br />
                </div>
                <div class="female">
                  <input
                    type="checkbox"
                    id="female"
                    name="female"
                    value="female"
                  />
                  <label for="vehicle1">خانم</label><br />
                </div>
              </div>
              <div class="input-frame flex-column flex-md-row gap-3">
                <input type="text" placeholder="نام*" /><input
                  type="text"
                  placeholder="نام خانوادگی*"
                />
              </div>
              <div class="input-frame flex-column flex-md-row gap-3">
                <input type="text" placeholder="نام فروشگاه" /><input
                  type="text"
                  placeholder="شماره ملی*"
                />
              </div>
              <div class="input-frame flex-column flex-md-row gap-3">
                <input type="text" placeholder="شماره موبایل*" /><input
                  type="text"
                  placeholder="رمز عبور*"
                />
              </div>
              <div class="input-frame flex-column flex-md-row gap-3">
                <input type="text" placeholder="تکرار رمز عبور" /><input
                  type="text"
                  placeholder="تاریخ تولد"
                />
              </div>
            </form>
            <div class="save-btn-frame">
              <button class="save-btn">ذخیره</button>
            </div>
          </div>
        </div>
      </div> 

      <!-- wishlist -->

              <h5>تاریخچه خرید / لیست کالاهای مرجوعی</h5>
            </div>
            <div class="profile-info-section-title w-100">
              <button class="see-merchandise-list">ثبت درخواست مرجوعی</button>
            </div>
          </div>
          <hr />
          <div class="profile-info-section-description w-100">
            <p class="warning-text">شما هنوز سفارشی ثبت نکرده اید.</p>
          </div>
          <div class="search-history-frame flex-column flex-md-row align-items-stretch align-items-md-center">
            <p>جستجو در میان خرید های قبلی</p>
            <input type="text" placeholder="کد سفارش / کالا" />
            <input type="text" placeholder="روز/ماه/سال" />
            <button class="search-history-btn">
              <i class="fa-solid fa-magnifying-glass"></i>
            </button>
          </div>
          <div class="perchse-history-table-list">
            <table>
              <tr>
                <th>N</th>
                <th>کالای خریداری شده</th>
                <th>کد کالا</th>
                <th>کد سفارش</th>
                <th>تاریخ</th>
              </tr>
              <tr>
                <td>۱</td>
                <td>مهره چاکنت یا KM Lock Nut</td>
                <td>123456</td>
                <td>123456</td>
                <td>12/01/1405</td>
              </tr>
              <tr>
                <td>۲</td>
                <td>بلبرینگ ساچمه ای</td>
                <td>123456</td>
                <td>123456</td>
                <td>12/01/1405</td>
              </tr>
            </table>
          </div>
        </div>

              <h5>این لیست خرید احتمالی شماست</h5>
            </div>
          </div>
          <hr />
          <div class="search-history-frame flex-column flex-md-row align-items-stretch align-items-md-center"></div>
          <div class="perchse-history-table-list overflow-auto">
            <table>
              <tr>
                <th>N</th>
                <th>لیست تمایلات خرید بعدی شما</th>
                <th>تاریخ افزودن به سبد</th>
              </tr>
              <tr>
                <td>۱</td>
                <td>مهره چاکنت یا KM Lock Nut</td>
                <td>12/01/1405</td>
              </tr>
              <tr>
                <td>۲</td>
                <td>بلبرینگ ساچمه ای</td>

                <td>12/01/1405</td>
              </tr>
            </table>
          </div>
        </div>

              <h5>پشتیبانی</h5>
            </div>
          </div>

          <hr />

          <div class="ticket-layout flex-column flex-lg-row">
            <div class="ticket-sidebar ticket-sidebar w-100 w-md-auto flex-row flex-lg-column flex-wrap">
              <button class="ticket-btn active" data-ticket-tab="new">
                ساخت تیکت جدید
              </button>
              <button class="ticket-btn" data-ticket-tab="active">
                تیکت های فعال
              </button>
              <button class="ticket-btn" data-ticket-tab="pending">
                در انتظار پاسخ
              </button>
              <button class="ticket-btn" data-ticket-tab="closed">
                تیکت های بسته شده
              </button>
            </div>

            <div class="ticket-content">
              <div class="ticket-panel active" id="ticket-new">
                <div class="ticket-type">
                  <select class="ticket-select">
                    <option value="">مسیر تیکت را انتخاب کنید</option>
                    <option value="buy">سوال درباره خرید محصول</option>
                    <option value="product">
                      سوال درباره محصول خریداری شده
                    </option>
                  </select>
                </div>

                <div class="ticket-product-input">
                  <input type="text" placeholder="نام محصول یا کد سفارش" />
                </div>

                <div class="ticket-message">
                  <textarea placeholder="متن تیکت خود را بنویسید"></textarea>
                </div>

                <div class="ticket-send-frame">
                  <button class="save-btn">ارسال تیکت</button>
                </div>
              </div>

              <div class="ticket-panel" id="ticket-active">
                <div class="ticket-list">
                  <div class="ticket-item">
                    <div class="ticket-title">سوال درباره بلبرینگ</div>

                    <div class="ticket-chat">
                      <div class="chat-user">
                        <p>سلام درباره این محصول سوال داشتم</p>
                      </div>

                      <div class="chat-support">
                        <p>سلام لطفا شماره سفارش را ارسال کنید</p>
                      </div>
                    </div>

                    <div class="ticket-reply">
                      <input type="text" placeholder="پاسخ خود را بنویسید" />
                      <button class="save-btn">ارسال</button>
                    </div>
                  </div>
                </div>
              </div>

              <div class="ticket-panel" id="ticket-pending">
                <div class="ticket-list">
                  <div class="ticket-item pending">
                    <div class="ticket-title">درخواست بررسی سفارش</div>
                    <button class="delete-ticket">حذف تیکت</button>
                  </div>
                </div>
              </div>

              <div class="ticket-panel" id="ticket-closed">
                <div class="ticket-item closed">
                  <div class="ticket-title">سوال درباره اورینگ</div>

                  <div class="ticket-chat">
                    <div class="chat-user">
                      <p>آیا این محصول موجود است؟</p>
                    </div>

                    <div class="chat-support">
                      <p>بله موجود است.</p>
                    </div>
                  </div>
                  <div class="ticket-title">سوال درباره اورینگ</div>

                  <div class="ticket-chat">
                    <div class="chat-user">
                      <p>آیا این محصول موجود است؟</p>
                    </div>

                    <div class="chat-support">
                      <p>بله موجود است.</p>
                    </div>
                  </div>
                  <div class="ticket-title">سوال درباره اورینگ</div>

                  <div class="ticket-chat">
                    <div class="chat-user">
                      <p>آیا این محصول موجود است؟</p>
                    </div>

                    <div class="chat-support">
                      <p>بله موجود است.</p>
                    </div>
                  </div>
                  <div class="ticket-title">سوال درباره اورینگ</div>

                  <div class="ticket-chat">
                    <div class="chat-user">
                      <p>آیا این محصول موجود است؟</p>
                    </div>

                    <div class="chat-support">
                      <p>بله موجود است.</p>
                    </div>
                  </div>
                  <div class="ticket-title">سوال درباره اورینگ</div>

                  <div class="ticket-chat">
                    <div class="chat-user">
                      <p>آیا این محصول موجود است؟</p>
                    </div>

                    <div class="chat-support">
                      <p>بله موجود است.</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div> 

      <!-- user-rank -->
      <div class="info-sec user-rank w-100">
        <div class="history-title-section w-100">
          <div class="profile-info-section-title w-100">
            <h5>رتبه من</h5>
          </div>
        </div>
        <hr />
        <div class="user-rank-frame w-100 h-auto">
          <div class="rank-and-cta-text flex-column flex-md-row align-items-center">
            <div class="cta-txt">
              <p>
                شما بدون استثنا، ۱٪ تخفیف روی تمام اقلام موجود در انبار دریافت
                خواهید کرد!
              </p>
              <p>رتبه شما روزانه بر اساس خریدهای ۱۲ ماه گذشته محاسبه می‌شود.</p>
              <p>
                رتبه ۱ به مشتریانی تعلق می‌گیرد که در ۱۲ ماه گذشته خرید خالص بین
                ۲۵۰ تا ۴۹۹.۹۹ میلیون تومان داشته‌اند.
              </p>
            </div>
            <div class="rank-box">
              <p>۰</p>
              <h4>رتبه من</h4>
            </div>
          </div>
          <div class="rank-boxes flex-wrap">
            <div>
              <p>۱</p>
            </div>
            <div>
              <p>۲</p>
            </div>
            <div>
              <p>۳</p>
            </div>
            <div>
              <p>۴</p>
            </div>
            <div>
              <p>۵</p>
            </div>
            <div>
              <p>۶</p>
            </div>
            <div>
              <p>۷</p>
            </div>
            <div>
              <p>۸</p>
            </div>
          </div>
          <div class="situation">
            <div class="progress-frame">
              <p>۰</p>
              <div class="progress-container">
                <div class="progress-bar-fill" style="width: 70%"></div>
              </div>
              <p>۱</p>
            </div>
            <div class="situation-text">
              <p>
                شما تاکنون ۷۰ میلیون تومان از مجموع ۱۰۰ میلیون تومان خرید انجام
                داده‌اید.
              </p>
              <p>۱۰۰ میلیون تومان خرید تا رتبه بعدی</p>
            </div>
          </div>
        </div>
      </div>
    </div>

       <?php get_footer(); ?>
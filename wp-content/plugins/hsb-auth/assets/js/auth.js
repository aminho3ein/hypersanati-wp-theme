(function($){

$(function(){

    $(".hsb-auth-mobile").show();
    $(".hsb-auth-password").hide();

    $(".hsb-register-company").show();
    $(".hsb-register-personal").hide();

});


$(document).on(
    "click",
    ".hsb-account-switch-btn",
    function(){

        let target = $(this).data("target");

        $(".hsb-account-switch-btn")
            .removeClass("is-active");

        $(this)
            .addClass("is-active");

        $(".hsb-account-panel")
            .removeClass("is-active");

        $('.hsb-account-panel[data-panel="' + target + '"]')
            .addClass("is-active");

    }
);


$(document).on(
    "click",
    ".hsb-open-register",
    function(e){

        e.preventDefault();

        $('.hsb-account-switch-btn[data-target="register"]')
            .trigger("click");

    }
);


$(document).on(
    "click",
    ".hsb-auth-tabs button",
    function(){

        let index = $(this).index();

        $(".hsb-auth-tabs button")
            .removeClass("active");

        $(this)
            .addClass("active");


        if(index === 0){

            $(".hsb-auth-mobile").show();
            $(".hsb-auth-password").hide();

        } else {

            $(".hsb-auth-mobile").hide();
            $(".hsb-auth-password").show();

        }

    }
);


$(document).on(
    "click",
    ".hsb-register-type-btn",
    function(e){

        e.preventDefault();

        let type = $(this).attr("data-type");


        $(".hsb-register-type-btn")
            .removeClass("active");


        $(this)
            .addClass("active");


        if(type === "company"){

            $(".hsb-register-company")
                .show();

            $(".hsb-register-personal")
                .hide();

        } else {

            $(".hsb-register-company")
                .hide();

            $(".hsb-register-personal")
                .show();

        }

    }
);


$(document).on(
    "click",
    ".hsb-send-otp",
    function(){

        let box = $(this).closest(
            ".hsb-auth-mobile,.hsb-register-mobile"
        );


        let mobile = box.find(
            "input[name=hsb_mobile],input[name=register_mobile]"
        ).val();


        if(!mobile){
            return;
        }


        let isLogin = box.closest(".hsb-auth-box").length > 0;


        $.ajax({

            url: HSB_AUTH.rest_url + "check-mobile",

            method:"POST",

            headers:{
                "X-WP-Nonce": HSB_AUTH.nonce
            },

            data:{
                mobile:mobile
            },


            success:function(response){


                if(isLogin && !response.exists){

                    $(".hsb-auth-message")
                        .removeClass("success")
                        .addClass("error")
                        .text("این شماره در سیستم ثبت نشده است. لطفاً ثبت نام کنید.");

                    return;

                }


                if(!isLogin && response.exists){

                    $(".hsb-auth-message")
                        .removeClass("error")
                        .addClass("success")
                        .text("این شماره قبلاً ثبت شده است. وارد حساب خود شوید.");

                    return;

                }


                $.ajax({

                    url: HSB_AUTH.rest_url + "request-otp",

                    method:"POST",

                    headers:{
                        "X-WP-Nonce": HSB_AUTH.nonce
                    },

                    data:{
                        mobile:mobile
                    },


                    success:function(){

                        box.find(".hsb-mobile-input-step")
                            .hide();

                        box.find(".hsb-otp-container")
                            .addClass("active");

                        box.find(".hsb-mobile-number")
                            .text(mobile);


                        if (box.data("otpTimer")) {

                            clearInterval(
                                box.data("otpTimer")
                            );

                        }


                        let time = 120;


                        box.find(".hsb-timer")
                            .first()
                            .text(time);


                        let timer = setInterval(function(){

                            time--;


                            box.find(".hsb-timer")
                                .first()
                                .text(time);


                            if(time <= 0){

                                clearInterval(timer);

                                box.find(".hsb-edit-mobile")
                                    .prop("disabled", false);

                            }

                        },1000);


                        box.data(
                            "otpTimer",
                            timer
                        );


                        box.find(".hsb-otp-boxes input")
                            .first()
                            .focus();

                    }

                });


            }

        });


    }
);


})(jQuery);


jQuery(function($){


    $(document).on(
        "input",
        ".hsb-otp-boxes input",
        function(){

            if(this.value.length === 1){

                $(this)
                    .next("input")
                    .focus();

            }

        }
    );


    $(document).on(
        "keydown",
        ".hsb-otp-boxes input",
        function(e){

            if(
                e.key === "Backspace" &&
                this.value === ""
            ){

                $(this)
                    .prev("input")
                    .focus();

            }

        }
    );


});



jQuery(function($){

    $(document).on(
        "click",
        ".hsb-confirm-mobile",
        function(){

            let box = $(this).closest(
                ".hsb-auth-mobile,.hsb-register-mobile"
            );


            let mobile = box.find(
                "input[name=hsb_mobile],input[name=register_mobile]"
            ).val();


            let code = "";

            box.find(".hsb-otp-boxes input")
                .each(function(){

                    code += $(this).val();

                });


            if(code.length !== 6){
                return;
            }


            $.ajax({

                url: HSB_AUTH.rest_url + "verify-otp",

                method:"POST",

                headers:{
                    "X-WP-Nonce": HSB_AUTH.nonce
                },

                data:{
                    mobile:mobile,
                    code:code
                },


                success:function(response){

                    if(response.status === "success"){

                        box.find(".hsb-mobile-status")
                            .removeClass("waiting")
                            .addClass("success")
                            .text("شماره موبایل تأیید شد");


                        box.data(
                            "mobileVerified",
                            true
                        );


                        box.find(".hsb-register-company, .hsb-register-personal")
                            .show();


                    } else {


                        $(".hsb-auth-message")
                            .removeClass("success")
                            .addClass("error")
                            .text("کد وارد شده صحیح نیست.");


                    }

                }

            });

        }
    );

});



jQuery(function($){


    $(document).on(
        "click",
        ".hsb-create-account",
        function(){


            let box = $(".hsb-register-mobile");


            if(!box.data("mobileVerified")){

                $(".hsb-auth-message")
                    .removeClass("success")
                    .addClass("error")
                    .text("ابتدا شماره موبایل را تأیید کنید.");

                return;

            }


            let mobile = box.find(
                "input[name=register_mobile]"
            ).val();


            let data = {

                username: mobile,

                password: $("input[name=password]").val(),

                email: $("input[name=email]").val(),

                mobile: mobile,

                company_name: $("input[name=company_name]").val(),

                first_name: $("input[name=first_name]").val(),

                last_name: $("input[name=last_name]").val()

            };


            $.ajax({

                url: HSB_AUTH.rest_url + "register",

                method:"POST",

                headers:{
                    "X-WP-Nonce": HSB_AUTH.nonce
                },


                data:data,


                success:function(response){

                    if(response.status === "success"){

                        $(".hsb-auth-message")
                            .removeClass("error")
                            .addClass("success")
                            .text("ثبت نام با موفقیت انجام شد.");


                        location.reload();

                    } else {


                        $(".hsb-auth-message")
                            .removeClass("success")
                            .addClass("error")
                            .text(response.message || "ثبت نام انجام نشد.");

                    }

                },


                error:function(){

                    $(".hsb-auth-message")
                        .removeClass("success")
                        .addClass("error")
                        .text("خطا در ثبت نام.");

                }


            });


        }
    );


});




jQuery(function($){

    $(document).on(
        "click",
        ".hsb-edit-mobile",
        function(){

            let box = $(this).closest(
                ".hsb-auth-mobile,.hsb-register-mobile"
            );


            box.find(".hsb-mobile-input-step")
                .show();


            box.find(".hsb-otp-container")
                .removeClass("active");


            box.find(".hsb-otp-boxes input")
                .val("");


            box.removeData("mobileVerified");


            box.find(".hsb-mobile-status")
                .removeClass("success")
                .addClass("waiting")
                .text("منتظر تأیید شماره");


            if(box.data("otpTimer")){

                clearInterval(
                    box.data("otpTimer")
                );

            }


        }
    );

});




jQuery(function($){

    $(document).on(
        "click",
        ".hsb-edit-mobile",
        function(){

            let box = $(this).closest(
                ".hsb-auth-mobile,.hsb-register-mobile"
            );


            box.find(".hsb-mobile-input-step")
                .show();


            box.find(".hsb-otp-container")
                .removeClass("active");


            box.find(".hsb-otp-boxes input")
                .val("");


            box.removeData("mobileVerified");


            box.find(".hsb-mobile-status")
                .removeClass("success")
                .addClass("waiting")
                .text("منتظر تأیید شماره");


            if(box.data("otpTimer")){

                clearInterval(
                    box.data("otpTimer")
                );

            }


        }
    );

});

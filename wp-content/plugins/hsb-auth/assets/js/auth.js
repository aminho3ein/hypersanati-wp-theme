(function($){


    $(document).on(
        "click",
        ".hsb-auth-mobile button",
        function(){


            let mobile = $(
                "input[name=hsb_mobile]"
            ).val();


            if (!mobile) {

                $(".hsb-auth-message").text(
                    "شماره موبایل را وارد کنید"
                );

                return;

            }


            $.ajax({

                url: HSB_AUTH.rest_url + "request-otp",

                method: "POST",

                headers: {
                    "X-WP-Nonce": HSB_AUTH.nonce
                },

                data: {
                    mobile: mobile
                },

                success: function(response){

                    console.log(
                        response
                    );

                    $(".hsb-auth-message").text(
                        "کد تایید ارسال شد"
                    );

                },

                error: function(){

                    $(".hsb-auth-message").text(
                        "خطا در ارسال کد"
                    );

                }

            });


        }
    );


})(jQuery);


$(document).on(
    "click",
    ".hsb-auth-otp button",
    function(){


        let mobile = $(
            "input[name=hsb_mobile]"
        ).val();


        let code = $(
            "input[name=hsb_otp]"
        ).val();



        if (!code) {

            $(".hsb-auth-message").text(
                "کد تایید را وارد کنید"
            );

            return;

        }


        $.ajax({

            url: HSB_AUTH.rest_url + "verify-otp",

            method: "POST",

            headers: {
                "X-WP-Nonce": HSB_AUTH.nonce
            },

            data: {

                mobile: mobile,

                code: code

            },


            success: function(response){


                console.log(
                    response
                );


                if(response.status === "success") {


                    $(".hsb-auth-message").text(
                        "ورود موفق"
                    );


                    location.reload();


                } else {


                    $(".hsb-auth-message").text(
                        "کد تایید اشتباه است"
                    );


                }


            },


            error: function(){

                $(".hsb-auth-message").text(
                    "خطا در ورود"
                );

            }


        });


    }
);

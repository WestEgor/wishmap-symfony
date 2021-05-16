$(document).ready(function () {
    $('button#next-page').click(function () {
        let error = 0;
        $('input#user_username').blur(function () {
            if ($(this).val().length === 0) {
                error++;
                alert('username need to be filled')
            }
        });

        $('input#user_password_first').blur(function () {
            if (!$(this).val()) {
                error++;
                alert('username need to be filled')
            }
        });

        $('input#user_password_second').blur(function () {
            if (!$(this).val()) {
                error++;
                alert('username need to be filled')
            }
        });

        if (error === 0) {
            $("div#secondStage").removeAttr("style");
            $("div#firstStage").attr("style", "display: none;");

        }
    })


})
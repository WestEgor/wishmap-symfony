$(document).ready(function () {
    $('input#user-search').keyup(delay(function () {
        let nickname = $(this).val();
        let result = $('div#search-result');
        $.ajax({
            type: 'GET',
            url: '/wishmap/user',
            data: {
                user: nickname
            },
            dataType: 'json',
            success: function (value) {
                if (value.length === 0) {
                    result.html('');
                } else if (nickname.length > 2) {
                    let arrayRes = value.map(item => Object.keys(item)
                        .map(i => item[i]))
                    result.html('');
                    for (let i = 0; i < arrayRes.length; i++) {
                        result.append('<a class="btn-success search-form search-font" href="/wishmap?user=' + arrayRes[i] + '">' + arrayRes[i] +
                            '</a>')
                    }
                }
            }
        });
        if (nickname.length === 0) {
            result.html('');
        }
    }, 100));
});

function delay(callback, ms) {
    let timer = 0;
    return function () {
        let context = this, args = arguments;
        clearTimeout(timer);
        timer = setTimeout(function () {
            callback.apply(context, args);
        }, ms || 0);
    };
}
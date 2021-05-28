//script that provides user search
$(document).ready(function () {
    //while we typing suggestions we sent data
    $('input#user-search').keyup(delay(function () {
        let nickname = $(this).val(); //our suggestions about user nickname
        let result = $('div#search-result'); //added result of ajax request
        $.ajax({
            type: 'GET',
            url: '/wishmap/user',
            data: {
                user: nickname
            },
            dataType: 'json',
            success: function (value) {
                //if no value -> no date output
                if (value.length === 0) {
                    result.html('');
                    //if length of suggestion of nickname more than 2
                } else if (nickname.length > 2) {
                    let arrayRes = value.map(item => Object.keys(item)
                        .map(i => item[i])) //make array from js objects
                    result.html(''); //after new suggestion clear previous result
                    for (let i = 0; i < arrayRes.length; i++) {
                        result.append('<a class="btn-success search-form search-font" href="/wishmap?user=' + arrayRes[i] + '">' + arrayRes[i] +
                            '</a>') //append to result all find user nicknames with link on their profiles
                    }
                }
            }
        });
        //clear result div
        if (nickname.length === 0) {
            result.html('');
        }
        //delay
    }, 100));
});

//delay for typing
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
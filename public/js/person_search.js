/*$("button").click(function(){
    $.ajax({url: "demo_test.txt", success: function(result){
            $("#div1").html(result);
        }});
});*/

$(document).ready(function () {
    $('#search').keyup(function () {
        let searchPerson = $(this).val();
        if (searchPerson !== '') {
            $.ajax({
                url: 'wishmap?person=' + searchPerson,
                method: 'get',
                success: function (response) {
                    $('#show-value').html(response);
                }
            })
        } else {
            $('#show-list').html("");
        }
    });

    /*$(document).on('click', 'a', function () {
        $('#search').val($(this).text());
        $('#show-list').html('');
    })*/


});






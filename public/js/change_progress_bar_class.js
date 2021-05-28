//script for making progress bar different colours
$(document).ready(function () {
    $('div#progress-bar').each(function () {
        let val = $(this).attr('aria-valuenow'); //get value of progress
        if (val <= 25) {
            $(this).addClass('progress-bar progress-bar-striped bg-danger');
        }
        if (val >= 25 && val <= 50) {
            $(this).addClass('progress-bar progress-bar-striped bg-warning');
        }
        if (val >= 50 && val <= 99) {
            $(this).addClass('progress-bar progress-bar-striped bg-success');
        } else {
            $(this).addClass('progress-bar');
        }
    })
});
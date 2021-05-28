//script for delete wish map card
$(document).ready(function () {
    $(".btn.btn-outline-danger.wishmap-body-font").click(function () {
        const deletedId = $(this).attr('data-id'); //wish map id
        if (confirm('Do you want to delete this wish map card?')) {
            $.ajax({
                url: '/wishmap/delete/' + deletedId,
                type: 'delete',
                success: function () {
                    window.location.href = '/wishmap';
                }
            });
        }
    });
});
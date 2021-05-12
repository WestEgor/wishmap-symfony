$(document).ready(function () {
    $(".btn.btn-danger.margin").click(function () {
        var el = this;
        const deletedId = $(this).attr('data-id');
        if (confirm('Do you want to delete this wish map card?')) {
            $.ajax({
                url: '/wishmap/delete/' + deletedId,
                type: 'delete',
                success: function () {
                    location.reload();
                }

            });
        }
    });
});
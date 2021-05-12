$(document).ready(function () {
    $(".btn.btn-danger.margin").click(function () {
        const deletedId = $(this).attr('data-id');
        if (confirm('Do you want to delete this wish map card?')) {
            $.ajax({
                url: '/wishmap/delete/' + deletedId,
                type: 'delete',
                success: function () {
                    window.location.href = '/wishmap';
                    alert('Deleted successfully');
                }
            });
        }
    });
});
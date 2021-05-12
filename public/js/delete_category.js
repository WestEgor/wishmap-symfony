$(document).ready(function () {
    $(".btn.btn-danger.delete-category").click(function () {
        const deletedId = $(this).attr('data-id');
        if (confirm('Do you want to delete this wish map category?')) {
            $.ajax({
                url: '/wishmap/category/delete/' + deletedId,
                type: 'delete',
                success: function () {
                    location.reload();
                },
                error: function () {
                    alert('Cannot delete! Probably, this category is used in your wish map card.')
                }
            });
        }
    });
});
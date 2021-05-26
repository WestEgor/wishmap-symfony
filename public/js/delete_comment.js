$(document).ready(function () {
    $("a#delete-comment").click(function () {
        const deletedId = $(this).attr('data-id');
        const wishMapId = $('button#post.btn.btn-outline-warning').attr('data-id');
        $.ajax({
            url: '/wishmap/' + wishMapId + '/comment/delete/' + deletedId,
            type: 'delete',
            success: function () {
                window.location.href = '/wishmap/' + wishMapId + '/comments';
            }
        });

    });
});
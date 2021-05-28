//script for delete comment from wish map
$(document).ready(function () {
    $("a#delete-comment").click(function () {
        const deletedId = $(this).attr('data-id'); //get comment ID
        const wishMapId = $('button#post.btn.btn-outline-warning').attr('data-id');//get wish map ID
        $.ajax({
            url: '/wishmap/' + wishMapId + '/comment/delete/' + deletedId,
            type: 'delete',
            success: function () {
                window.location.href = '/wishmap/' + wishMapId + '/comments';
            }
        });

    });
});
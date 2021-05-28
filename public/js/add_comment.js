//script for adding comment to wish map
$(document).ready(function () {
    $("button#post.btn.btn-outline-warning").click(function () {
        const wishMapId = $(this).attr('data-id'); //get wish map ID
        const commentBody = $('textarea#msg.form-control').val(); //get comment to send
        $.ajax({
            type: 'GET',
            url: '/wishmap/' + wishMapId + '/comments/add',
            data: {
                comment_body: commentBody
            },
            success: function () {
                window.location.href = '/wishmap/' + wishMapId + '/comments';
            }
        });

    });
});
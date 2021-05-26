$(document).ready(function () {
    $("button#post.btn.btn-outline-warning").click(function () {
        const wishMapId = $(this).attr('data-id');
        const commentBody = $('textarea#msg.form-control').val();
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
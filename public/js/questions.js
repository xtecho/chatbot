$(document).ready(function () {
    $('#questions-datatable').DataTable();

    $('.edit-question').on('click', function () {
        var id = $(this).attr('data-id');

        var edit_modal = $('#edit-modal');

        $.ajax({
            url: '/questions/' + id,
            type: 'GET',
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (response) {
                edit_modal.find('.modal-header .modal-title').html('Edit Answers for question: "' + response.question.question);
                console.log(response);

                var html = "";
                $.each(response.answers, function (key, answer) {
                    html += "<div class='row form-group'><div class='col-xs-10'><input class='form-control' type='text' value='" + answer.answer + "'></div><span class='fa fa-check-square-o fa-2x save-answer' data-id='" + answer.id + "'></span></div>";
                });

                html += "<div class='row form-group text-center'><span class='fa fa-plus-square fa-4x add-answer'></span></div><div class='clearfix'></div>";

                edit_modal.find('.modal-body').html(html);

                $('.add-answer').on('click', function () {
                    html = "<div class='row form-group'><div class='col-xs-10'><input class='form-control' type='text' value=''></div><span class='fa fa-check-square-o fa-2x save-answer' data-id=''></span></div>";

                    $(html).insertBefore($(this).parent());
                });

                $('.save-answer').on('click', function () {
                    var id = $(this).attr('data-id');

                    $.ajax({
                        url: '/questions/answer/' + id,
                        type: 'POST',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        data: {
                            answer: $(this).parent().find('input').val()
                        },
                        success: function (response) {
                            console.log(response);
                        },
                        error: function (response) {

                        }
                    });
                });

            },
            error: function (response) {

            }
        });

        edit_modal.modal('show');
    });
    $('.delete-question').on('click', function () {
        var id = $(this).attr('data-id');
        console.log(id);
    });
});




$(document).ready(function () {
    var questions_datatable = $('#questions-datatable').DataTable({
        "initComplete": function(settings, json) {
            questionsDatatableButtons();
        }
    }).on('draw.dt initCompleteSince', function () {
        questionsDatatableButtons();
    });
});

function questionsDatatableButtons() {
    $(".edit-question:not('.visited')").on('click', function () {
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

                var html = "";
                $.each(response.answers, function (key, answer) {
                    html += "<div class='row form-group'><div class='col-xs-10'><input class='form-control' type='text' value='" + answer.answer + "'></div><span class='fa fa-check-square-o fa-2x save-answer' data-id='" + answer.id + "'></span><span class='fa fa-remove fa-2x delete-answer' data-id='" + answer.id + "'></span></div>";
                });

                html += "<div class='row form-group text-center'><span class='fa fa-plus-square fa-4x add-answer'></span></div><div class='clearfix'></div><input id='question_id' type='hidden' value='" + response.question.id + "'>";

                edit_modal.find('.modal-body').html(html);

                $('.add-answer').on('click', function () {
                    html = "<div class='row form-group'><div class='col-xs-10'><input class='form-control' type='text' value=''></div><span class='fa fa-check-square-o fa-2x save-answer' data-id=''></span><span class='fa fa-remove fa-2x delete-answer' data-id=''></span></div>";

                    let element = $(html).insertBefore($(this).parent());
                    element.find('.save-answer').on('click', function () {
                        let id = $(this).attr('data-id');
                        let _this = this;
                        if ($(this).parent().find('input').val().length) {
                            if (id.length === 0) {
                                $.ajax({
                                    url: '/questions/answer/add',
                                    type: 'POST',
                                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                    data: {
                                        answer: $(this).parent().find('input').val(),
                                        question: $(this).closest('.modal-body').find('#question_id').val()
                                    },
                                    success: function (response) {
                                        $(_this).attr('data-id', response.answer.id);
                                        $(_this).parent().find('.delete-answer').attr('data-id', response.answer.id);

                                        var answers_td = $('#questions-datatable').find("tr[data-id='" + response.answer.question_id + "'] td.answers");
                                        answers_td.html(parseInt(answers_td.html()) + 1);

                                        swal({
                                            title: 'Success!',
                                            html: 'Answer was created!',
                                            type: 'success',
                                            showConfirmButton: false,
                                            timer: 1500,
                                            customClass: 'animated tada'
                                        });
                                    },
                                    error: function (response) {

                                    }
                                });
                            } else {
                                $.ajax({
                                    url: '/questions/answer/' + id,
                                    type: 'POST',
                                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                    data: {
                                        answer: $(this).parent().find('input').val()
                                    },
                                    success: function (response) {
                                        swal({
                                            title: 'Success!',
                                            html: 'Answer was updated!',
                                            type: 'success',
                                            showConfirmButton: false,
                                            timer: 1500,
                                            customClass: 'animated tada'
                                        });
                                    },
                                    error: function (response) {

                                    }
                                });
                            }
                        } else {
                            swal({
                                title: 'Error!',
                                html: 'Answer message can\'t be empty!',
                                type: 'error',
                                customClass: 'animated tada'
                            });
                        }
                    });


                    element.find('.delete-answer').on('click', function () {
                        swal({
                            title: 'Are you sure?',
                            text: "You won't be able to revert this!",
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, delete it!'
                        }).then((result) => {
                            if (result.value) {
                                let id = $(this).attr('data-id');
                                let _this = this;
                                $.ajax({
                                    url: '/questions/answer/delete/' + id,
                                    type: 'GET',
                                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                    data: {
                                        answer: $(this).parent().find('input').val()
                                    },
                                    success: function (response) {
                                        swal({
                                            title: 'Success!',
                                            html: 'Answer was deleted!',
                                            type: 'success',
                                            showConfirmButton: false,
                                            timer: 1500,
                                            customClass: 'animated tada'
                                        });
                                        $(_this).closest('.form-group').remove();

                                        var answers_td = $('#questions-datatable').find("tr[data-id='" + response.answer.question_id + "'] td.answers");
                                        answers_td.html(parseInt(answers_td.html()) - 1);
                                    },
                                    error: function (response) {

                                    }
                                });
                            }
                        });
                    });
                });

                $('.save-answer').on('click', function () {
                    if ($(this).parent().find('input').val().length) {
                        var id = $(this).attr('data-id');

                        $.ajax({
                            url: '/questions/answer/' + id,
                            type: 'POST',
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            data: {
                                answer: $(this).parent().find('input').val()
                            },
                            success: function (response) {
                                swal({
                                    title: 'Success!',
                                    html: 'Answer was updated!',
                                    type: 'success',
                                    showConfirmButton: false,
                                    timer: 1500,
                                    customClass: 'animated tada'
                                });
                            },
                            error: function (response) {

                            }
                        });
                    } else {
                        swal({
                            title: 'Error!',
                            html: 'Answer message can\'t be empty!',
                            type: 'error',
                            customClass: 'animated tada'
                        });
                    }
                });

                $('.delete-answer').on('click', function () {
                    swal({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.value) {
                            let id = $(this).attr('data-id');
                            let _this = this;
                            $.ajax({
                                url: '/questions/answer/delete/' + id,
                                type: 'GET',
                                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                data: {
                                    answer: $(this).parent().find('input').val()
                                },
                                success: function (response) {
                                    swal({
                                        title: 'Success!',
                                        html: 'Answer was deleted!',
                                        type: 'success',
                                        showConfirmButton: false,
                                        timer: 1500,
                                        customClass: 'animated tada'
                                    });
                                    $(_this).closest('.form-group').remove();

                                    var answers_td = $('#questions-datatable').find("tr[data-id='" + response.answer.question_id + "'] td.answers");
                                    answers_td.html(parseInt(answers_td.html()) - 1);
                                },
                                error: function (response) {

                                }
                            });
                        }
                    });
                });

            },
            error: function (response) {

            }
        });

        edit_modal.modal('show');
    }).addClass('visited');

    $(".delete-question:not('visited')").on('click', function () {
        swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                let id = $(this).attr('data-id');
                $.ajax({
                    url: '/questions/delete/' + id,
                    type: 'GET',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: {
                        question: $(this).parent().find('input').val()
                    },
                    success: function (response) {
                        swal({
                            title: 'Success!',
                            html: 'Question was deleted!',
                            type: 'success',
                            showConfirmButton: false,
                            timer: 1500,
                            customClass: 'animated tada'
                        });
                        location.reload();
                    },
                    error: function (response) {

                    }
                });
            }
        });
    }).addClass('visited');
}




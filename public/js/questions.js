$(document).ready(function () {
    // se genereaza datatable
   $('#questions-datatable').DataTable({
        "initComplete": function (settings, json) {
            // cand se initializeaza tabelul adaugam evenimentele pentru butoanele generate
            questionsDatatableButtons();
        }
    }).on('draw.dt initCompleteSince', function () {
       // cand se schimbba pagina tabelului adaugam evenimentele pentru butoanele generate
        questionsDatatableButtons();
    });
});

function questionsDatatableButtons() {
    // se cauta fiecare buton de edit al intrebarii care nu a mai fost vizitat
    $(".edit-question:not('.visited')").on('click', function () {
        var id = $(this).attr('data-id');

        var edit_modal = $('#edit-modal');

        // pentru evenimentul de click al butonului de edit se realizeaza un AJAX request de tip GET
        $.ajax({
            url: '/questions/' + id,
            type: 'GET',
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (response) {
                // modificam titlul modalului cu intrebarea respectiva
                edit_modal.find('.modal-header .modal-title').html('Edit Answers for question: <strong>' + response.question.question + '</strong>');

                // genereaza inputurile pentru raspunsuri cu butoanele de salvare si stergere
                var html = "";
                $.each(response.answers, function (key, answer) {
                    html += "<div class='row form-group'><div class='col-xs-10'><input class='form-control' type='text' value='" + htmlspecialchars(answer.answer) + "'></div><span class='fa fa-check-square-o fa-2x save-answer' data-id='" + answer.id + "'></span><span class='fa fa-remove fa-2x delete-answer' data-id='" + answer.id + "'></span></div>";
                });

                // adauga un button de adaugare raspuns
                html += "<div class='row form-group text-center'><span class='fa fa-plus-square fa-4x add-answer'></span></div><div class='clearfix'></div><input id='question_id' type='hidden' value='" + response.question.id + "'>";

                edit_modal.find('.modal-body').html(html);

                // eveniment de click pe adaugare raspuns
                $('.add-answer').on('click', function () {
                    // se genereaza un nou input gol si butoanele pentru raspuns
                    html = "<div class='row form-group'><div class='col-xs-10'><input class='form-control' type='text' value=''></div><span class='fa fa-check-square-o fa-2x save-answer' data-id=''></span><span class='fa fa-remove fa-2x delete-answer' data-id=''></span></div>";
                    // se afiseaza html-ul generat inainte de butonul de adaugare raspuns
                    let element = $(html).insertBefore($(this).parent());
                    element.find('.save-answer').on('click', function () {
                        // pentru fiecare input now adaugat se genereaza codul de trimitere in backend a requestului
                        let id = $(this).attr('data-id');
                        let _this = this;
                        // se verifica daca inputul nu contine un string gol
                        if ($(this).parent().find('input').val().length) {
                            if (id.length === 0) {
                                // daca inputul este nou si nu are id atunci se creeaza un now raspuns
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

                                        // se afiseaza o notificare de success
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
                                // daca inputul nu este nou si are id atunci se creeaza un nou raspuns
                                $.ajax({
                                    url: '/questions/answer/' + id,
                                    type: 'POST',
                                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                    data: {
                                        answer: $(this).parent().find('input').val()
                                    },
                                    success: function (response) {
                                        // se afiseaza o notificare de success
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
                            // se afiseaza o notificare de eroare
                            swal({
                                title: 'Error!',
                                html: 'Answer message can\'t be empty!',
                                type: 'error',
                                customClass: 'animated tada'
                            });
                        }
                    });


                    element.find('.delete-answer').on('click', function () {
                        if ($(this).attr('data-id').length === 0) {
                            // daca nu are id setat atunci se sterge decat elementul HTML
                            $(this).closest('.form-group').remove();
                        } else {
                            // un popup de confirmare a actiunii de stergere
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
                                    // actiunea este confirmata
                                    let id = $(this).attr('data-id');
                                    let _this = this;
                                    // cerere de stergere a raspunsului
                                    $.ajax({
                                        url: '/questions/answer/delete/' + id,
                                        type: 'GET',
                                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                        data: {
                                            answer: $(this).parent().find('input').val()
                                        },
                                        success: function (response) {
                                            // afisare notificare de success
                                            swal({
                                                title: 'Success!',
                                                html: 'Answer was deleted!',
                                                type: 'success',
                                                showConfirmButton: false,
                                                timer: 1500,
                                                customClass: 'animated tada'
                                            });
                                            // se sterge elemetnul respectiv
                                            $(_this).closest('.form-group').remove();

                                            // se decrementeaza in tabela numarul de raspunsuri pentru intrebarea respectiva
                                            var answers_td = $('#questions-datatable').find("tr[data-id='" + response.answer.question_id + "'] td.answers");
                                            answers_td.html(parseInt(answers_td.html()) - 1);
                                        },
                                        error: function (response) {

                                        }
                                    });
                                }
                            });
                        }
                    });
                });

                $('.save-answer').on('click', function () {
                    // eveniment salvare raspuns
                    if ($(this).parent().find('input').val().length) {
                        var id = $(this).attr('data-id');

                        // cerere de modificare raspuns
                        $.ajax({
                            url: '/questions/answer/' + id,
                            type: 'POST',
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            data: {
                                answer: $(this).parent().find('input').val()
                            },
                            success: function (response) {
                                // afisare mesaj de success
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
                        // afisare mesaj de eroare
                        swal({
                            title: 'Error!',
                            html: 'Answer message can\'t be empty!',
                            type: 'error',
                            customClass: 'animated tada'
                        });
                    }
                });

                $('.delete-answer').on('click', function () {
                    // stergere raspuns urmat de confirmare a actiunii
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
                            // confirmare actiune de stergere raspuns
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
                                    // notificare de success
                                    swal({
                                        title: 'Success!',
                                        html: 'Answer was deleted!',
                                        type: 'success',
                                        showConfirmButton: false,
                                        timer: 1500,
                                        customClass: 'animated tada'
                                    });
                                    // stergere element ce contine raspunsul respectiv
                                    $(_this).closest('.form-group').remove();

                                    // se decrementeaza in tabela numarul de raspunsuri pentru intrebarea respectiva
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
        // stergere intrebare si confirmare a actiunii
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
                // confirmare a actiunii de stergere a intrebarii
                let id = $(this).attr('data-id');
                $.ajax({
                    url: '/questions/delete/' + id,
                    type: 'GET',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: {
                        question: $(this).parent().find('input').val()
                    },
                    success: function (response) {
                        // notificare de success
                        swal({
                            title: 'Success!',
                            html: 'Question was deleted!',
                            type: 'success',
                            showConfirmButton: false,
                            timer: 1500,
                            customClass: 'animated tada'
                        });
                        // reimprospatarea paginii
                        location.reload();
                    },
                    error: function (response) {

                    }
                });
            }
        });
    }).addClass('visited');
}

// functie de inlocuire caractere speciale
function htmlspecialchars(str) {
    if (typeof(str) === "string") {
        str = str.replace(/&/g, "&amp;");
        str = str.replace(/"/g, "&quot;");
        str = str.replace(/'/g, "&#039;");
        str = str.replace(/</g, "&lt;");
        str = str.replace(/>/g, "&gt;");
    }
    return str;
}
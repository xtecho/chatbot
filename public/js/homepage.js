if ($("#answear_appearance").attr('data-type') === "question") {
    setTimeout(function () {
        if ($("#answear_appearance").length) {
            // animatie aparitie input intrebare
            $("#answear_appearance").delay(1000).animate({"opacity": "1"}, 1200);
            document.getElementById('answear_appearance').style.visibility = "visible";
        }
    }, 500);
} else {
    setTimeout(function () {
        if ($("#answear_appearance").length) {
            // animatie aparitie input raspuns
            $("#answear_appearance").delay(1000).animate({"opacity": "1"}, 1200);
            document.getElementById('answear_appearance').style.visibility = "visible";
        }
    }, 3300);
}

$('#chatbot-form').submit(function (e) {
    e.preventDefault();

    if ($('#answer-input').val().length) {
        // daca inputul nu este gol se face un request AJAX cu toate valorile elementelor din formular
        $.ajax({
            url: '/add-answer',
            type: 'POST',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: new FormData(document.getElementById('chatbot-form')),
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (response) {
                // daca se retunreaza un rapsuns de tip redirect atunci se trimite user-ul la login
                if (response['redirect'] === true) {
                    location.replace('/login');
                }
                var question_square = $("#chatbot-form .square");
                if (question_square.find('input#type')[0].value === "question") {
                    // daca este de tip intrebare se seteaza valoare inputului cu id-ul ei
                    question_square.find('input#question_id')[0].value = response['question'].id;
                    if (response['answer'] === null) {
                        // daca nu are raspuns se afiseaza un input de invatare
                        question_square.find('input#answer-input').attr('disabled', true);
                        question_square.find('button#submit-btn')[0].style.display = "none";

                        var html = "<div class='typewriter'><h1>I don't know... Teach me master!<span class='write'>|</span></h1></div>";
                        question_square.append(html);

                        setTimeout(function () {
                            // animatie aparitie input
                            var html = "<div class='answear_input'><input type='text' name='answer_teach' placeholder='Type your answer here...' id='answer_teach' autocomplete='off' style='opacity: 0'><button type='submit' name='send' class='btn btn-info homepage_send_button' id='submit-btn'>Send</button></div>";
                            var answer = question_square.append(html);
                            question_square.find('input#answer_teach').animate({"opacity": "1"}, 1000);
                        }, 4000);
                    } else {
                        // se seteaza inputul in disabled
                        question_square.find('input#answer-input').attr('disabled', true);
                        question_square.find('button#submit-btn')[0].style.display = "none";

                        if (question_square.find('input#answer_teach').length === 0) {
                            // daca nu exista inputul de invatare atunci se afiseaza raspunsul
                            var html = "<div class='typewriter'><h1>" + response['answer'].answer + "</h1></div>";
                            question_square.append(html);
                        } else {
                            // daca exista inputul de invatare atunci se seteaza in disabled si se ascunde butonul de salvare
                            question_square.find('input#answer_teach').attr('disabled', true);
                            question_square.find('button#submit-btn')[1].style.display = "none";

                            // se afiseaza un mesaj de multumire
                            var html = "<div class='typewriter'><h1>Thank you for your support!<span class='write'>|</span></h1></div>";
                            question_square.append(html);
                        }

                        // se adauga un button pentru a trece la urmatoarea intrebare
                        var html = "<button class='btn btn-danger pull-center' id='next-question'>Next Question</button>";
                        $("#chatbot-form").append(html);

                        $('#chatbot-form button#next-question').on('click', function (e) {
                            // eveniment click pe buton de afisare a urmatoarei intrebari
                            e.preventDefault();

                            // se reseteaza inputurile pentru adaugare intrebare noua
                            question_square.find('input#answer-input').attr('disabled', false);
                            question_square.find('input#answer-input')[0].value = "";
                            question_square.find('button#submit-btn')[0].style.display = "initial";
                            $(this).remove();
                            question_square.find('.typewriter').remove();
                            if (question_square.find('.answear_input').length > 1) {
                                // daca exista mai multe inputuri de raspuns atunci se sterg
                                question_square.find('.answear_input')[1].remove();
                            }
                        });
                    }
                } else {
                    // animatie adaugare intrebare
                    question_square.animate({"opacity": "0"}, 500, function () {
                        question_square.find('.typewriter').remove();
                        question_square.find('input#answer-input')[0].value = "";
                        question_square.find('input#answer-input')[0].placeholder = "Type your question here...";
                        document.getElementById('answear_appearance').style.opacity = "0";
                        question_square.find('input#type')[0].value = "question";

                        var guest = $('input#guest').val();

                        if (guest) {
                            question_square.animate({"opacity": "1"}, 500, function () {
                                var html = "<div class='typewriter'><h1>You can ask me 3 questions. Go for it!<span class='write'>|</span></h1></div>";
                                question_square.prepend(html);

                                setTimeout(function () {
                                    question_square.animate({"opacity": "0"}, 500, function () {
                                        question_square.find('.typewriter')[0].style.display = "none";

                                        question_square.animate({"opacity": "1"}, 300, function () {
                                            $("#answear_appearance").animate({"opacity": "1"}, 1200);
                                            document.getElementById('answear_appearance').style.visibility = "visible";
                                        });
                                    });
                                }, 5000);
                            });
                        } else {
                            question_square.animate({"opacity": "1"}, 300, function () {
                                $("#answear_appearance").animate({"opacity": "1"}, 1200);
                                document.getElementById('answear_appearance').style.visibility = "visible";
                            });
                        }
                    });
                }

            },
            error: function (response) {

            }
        });
    }
});

$('#teach-form').submit(function (e) {
    e.preventDefault();

    // daca inputul de raspuns nu este gol
    if ($(this).find('input#answer-input').val().length) {
        let _this = this;
        // se face un request AJAX pentru adaugare a raspunsului
        $.ajax({
            url: '/teach/add',
            type: 'POST',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: {
                input: $(this).find('input#answer-input').val(),
                type: $(this).find('input#type').val(),
                question_id: $(this).find('input#question_id').val(),
            },
            success: function (response) {
                switch (response.type) {
                    case 'question':
                        // daca este intrebare atunci tipul se schimba in raspuns si se reseteaza formularul
                        $(_this).find('input#answer-input').val(null).attr('placeholder', 'Type your answer here...');
                        $(_this).find('input#type').val('answer');
                        $(_this).find('input#question_id').val(response.model.id);
                        // se afiseaza intrebarea inserata anterior
                        $('.typewriter').html('<h1>' + response.model.question + '<span class="write">|</span></h1>');
                        break;
                    case 'answer':
                        // daca este raspuns atunci tipul se schimba in intrebare si se reseteaza formularul
                        $(_this).find('input#answer-input').val(null).attr('placeholder', 'Type your question here...');
                        $(_this).find('input#type').val('question');
                        $(_this).find('input#question_id').val(null);
                        // se schima titlul in mesajul initial
                        $('.typewriter').html('<h1>Help me improve!<span class="write">|</span></h1>');

                        // notificare de success
                        swal({
                            title: 'Success!',
                            html: 'Thank you for your support!',
                            type: 'success',
                            showConfirmButton: false,
                            timer: 1500,
                            customClass: 'animated tada'
                        });
                        break;
                    case 'badword':
                        // daca este de tip badword atunci se afiseaza o notificare de eroare
                        swal({
                            title: 'Error!',
                            html: response.message,
                            type: 'error',
                            showConfirmButton: true,
                            customClass: 'animated tada'
                        });
                        break;
                }
            },
            error: function (response) {

            }
        });
    }
});
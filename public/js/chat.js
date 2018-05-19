$('#chat-form').submit(function (e) {
    e.preventDefault();

    function changeSource0() {
        let image = document.querySelectorAll("#generate-img > img")[0];
        image.src = "/img/robot-status-wrong.png";     //replace robot emotions images
    }
    
    function changeSource1() {
        let image = document.querySelectorAll("#generate-img > img")[0];
        image.src = "/img/robot-status-happy.png";     //replace robot emotions images
    }

    function changeSource2() {
        let image = document.querySelectorAll("#generate-img > img")[0];
        image.src = "/img/robot-status-angry.png";     //replace robot emotions images
    }

    var question = $(this).find('input#question')[0].value;

    if (question.length) {
        // daca intrebarea nu are un string gol
        var chat = $("ul.chat");
        var left = chat.find("li.left.hidden").clone();
        var right = chat.find("li.right.hidden").clone();

        // se genereaza html-ul pentru componenta din stanga cu ora curenta
        left.find('.message')[0].innerHTML = question;
        left.find('.time')[0].innerHTML = new Date().toLocaleTimeString();
        // se adauga mesajul la chat
        left.appendTo("ul.chat").removeClass('hidden');

        // se genereaza html-ul pentru componenta din dreapta cu ora curenta
//        right.find('.message')[0].innerHTML = "Is typing...";
        right.find('.message')[0].innerHTML = "<img src='/img/loader.gif' style='width: 60px; height: auto'>";
        right.find('.time')[0].innerHTML = "";
        right.find('.time').parent()[0].style = 'visibility: hidden';
        // se adauga mesajul la chat
        right.appendTo("ul.chat").removeClass('hidden');

        // se deruleaza chatul la ultimul mesaj
        $('.chat').parent()[0].scrollTop = $('.chat').parent()[0].scrollHeight;

        // trimite prin AJAX request de tip POST datele din formular
        $.ajax({
            url: '/add-answer',
            type: 'POST',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: new FormData(document.getElementById('chat-form')),
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (response) {
                // dupa ce se proceseaza informatiile si se gaseste sau nu raspunsul se afiseaza raspunsul si se schimba imaginea dupa o perioada la intamplare
                setTimeout(function () {
                    if (response['answer'] !== null) {
                        // daca gaseste raspunsul atunci se afiseaza in chat
                        right.find('.message')[0].innerHTML = response['answer'].answer;
                        if(response['answer'].answer === "Even the internet doesn't know that...") {
                            changeSource2();
                        } else {
                            changeSource1();
                        }
                    } else {
                        // daca nu gaseste raspuns se afiseaza un mesaj predefinit si se schimba imaginea
                        right.find('.message')[0].innerHTML = "I don't know...";
                        changeSource0();
                    }
                    // se adauga timpul cand a raspuns
                    right.find('.time').parent()[0].style = 'visibility: visible';
                    right.find('.time')[0].innerHTML = new Date().toLocaleTimeString();

                    $('.chat').parent()[0].scrollTop = $('.chat').parent()[0].scrollHeight;
                }, Math.floor(Math.random() * 3000) + 1000);
            },
            error: function (response) {

            }
        });

        $('#chat-form').find('input#question')[0].value = "";
    }
});
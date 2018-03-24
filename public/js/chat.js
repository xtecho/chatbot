/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$('#chat-form').submit(function (e) {
    e.preventDefault();

    function changeSource0() {
        var image = document.querySelectorAll("img")[0];
        var source = image.src = image.src.replace("robot-status-ok.png", "robot-status-wrong.png");     //replace robot emotions images
    }
    
    function changeSource1() {
        var image = document.querySelectorAll("img")[0];
        var source = image.src = image.src.replace("robot-status-wrong.png", "robot-status-happy.png");     //replace robot emotions images
    }
    
    function changeSource2() {
        var image = document.querySelectorAll("img")[0];
        var source = image.src = image.src.replace("robot-status-wrong.png", "robot-status-happy.png");     //replace robot emotions images
    }
    
    function changeSource3() {
        var image = document.querySelectorAll("img")[0];
        var source = image.src = image.src.replace("robot-status-ok.png", "robot-status-angry.png");     //replace robot emotions images
    }

    var question = $(this).find('input#question')[0].value;

    if (question.length) {
        var chat = $("ul.chat");
        var left = chat.find("li.left.hidden").clone();
        var right = chat.find("li.right.hidden").clone();

        left.find('.message')[0].innerHTML = question;
        left.find('.time')[0].innerHTML = new Date().toLocaleTimeString();
        left.appendTo("ul.chat").removeClass('hidden');

//        right.find('.message')[0].innerHTML = "Is typing...";
        right.find('.message')[0].innerHTML = "<img src='/img/loader.gif' style='width: 60px; height: auto'>";
        right.find('.time')[0].innerHTML = "";
        right.find('.time').parent()[0].style = 'visibility: hidden';
        right.appendTo("ul.chat").removeClass('hidden');

        $('.chat').parent()[0].scrollTop = $('.chat').parent()[0].scrollHeight;

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
                setTimeout(function () {
                    if (response['answer'] !== null) {
                        right.find('.message')[0].innerHTML = response['answer'].answer;
                        changeSource1();
                    } else {
                        right.find('.message')[0].innerHTML = "I don't know...";
                        changeSource0();
                    }
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




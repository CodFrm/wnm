window.onload = function () {
    var ws = new WebSocket("ws://127.0.0.1:8000/wsocket");
    ws.onopen = function () {
        ws.send('test');
    }
    ws.onclose = function () {
        console.log('close');
    }
    
    $('.dropdown').click(function () {
        $(this).find('.nav-sub').slideToggle().end();
    });
}
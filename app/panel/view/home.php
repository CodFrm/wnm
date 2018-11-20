<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

<script>
    var ws = new WebSocket("ws://127.0.0.1:8000/wsocket");
    ws.onopen=function () {
        ws.send('test');
    }
    ws.onclose=function () {
        console.log('close');
    }
</script>
</body>
</html>
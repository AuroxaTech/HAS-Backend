<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebSocket Test</title>
</head>
<body>
   
    <script>
        Echo.channel(`chat`)
        .listen('ChatMessageSent', (e) => {
            console.log(e);
        });
    </script>
</body>
</html>

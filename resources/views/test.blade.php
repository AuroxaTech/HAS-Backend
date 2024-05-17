<!DOCTYPE html>
<html>
<head>
    <title>WebSocket Test</title>
</head>
<body>
    <input type="text" id="messageInput">
    <button onclick="sendMessage()">Send</button>
    <ul id="messageList"></ul>

    <script>
        // var socket = new WebSocket("ws://localhost:8080");
        var socket = new WebSocket("ws://localhost:8080/api/websocket");

        socket.onopen = function(event) {
            console.log("WebSocket connected");
        };
        
        socket.onmessage = function(event) {
            console.log(event); 
            console.log(event.data);

            var messageList = document.getElementById("messageList");
            var li = document.createElement("li");
            li.appendChild(document.createTextNode(event.data)); // Access event.data for the message content
            messageList.appendChild(li);
        };

        function sendMessage() {
            var dummyMessage = {
                sender_id: 1,
                receiver_id: 2,
                message: "This is a dummy message",
                type: 0
            };
            var dummyMessageJSON = JSON.stringify(dummyMessage);
            socket.send(dummyMessageJSON);
            // console.log(message);
            // alert('messageInput');
            // socket.send(message);
            // messageInput.value = "";
        }
        
        // Simulate receiving a message
        // var dummyMessage = {
        //     sender_id: 1,
        //     receiver_id: 2,
        //     message: "This is a dummy message",
        //     type: 0
        // };
        // var dummyMessageJSON = JSON.stringify(dummyMessage);
        // setTimeout(function() {
        //     socket.send(dummyMessageJSON);
        //     console.log("Dummy message received");
        // }, 2000); 
    </script>
</body>
</html>

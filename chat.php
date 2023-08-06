<!DOCTYPE html>
<html>
<head>
    <title>صفحه چت</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding-bottom: 60px; /* اضافه کردن فاصله برای فرم ارسال پیام */
        }
        .chat-container {
            height: 300px;
            overflow-y: scroll;
            border: 1px solid #ccc;
            padding: 10px;
        }
        .message {
            margin-bottom: 10px;
        }
        .sender {
            font-weight: bold;
        }
        .timestamp {
            font-size: 0.8em;
            color: #666;
        }
        .input-form {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: #f0f0f0;
            padding: 10px;
            border-top: 1px solid #ccc;
            display: flex;
        }
        .input-form input[type="text"] {
            flex: 1;
            padding: 5px;
        }
        .input-form input[type="submit"] {
            padding: 5px 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="chat-container">
        <!-- پیام‌ها اینجا نمایش داده می‌شوند -->
    </div>

    <form class="input-form">
        <input type="text" id="message-input" placeholder="پیام خود را وارد کنید">
        <input type="submit" value="ارسال">
    </form>

    <script>
        // اضافه کردن رویداد برای ارسال پیام
        document.getElementById("message-input").addEventListener("keydown", function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                sendMessage();
            }
        });
        document.querySelector(".input-form input[type='submit']").addEventListener("click", function(event) {
            event.preventDefault();
            sendMessage();
        });

        // تابعی برای ارسال پیام
        function sendMessage() {
            var messageInput = document.getElementById("message-input");
            var message = messageInput.value.trim();
            if (message !== "") {
                var chatContainer = document.querySelector(".chat-container");
                var newMessage = document.createElement("div");
                newMessage.classList.add("message");
                newMessage.innerHTML = "<span class='sender'>شما: </span>" + message + "<br><span class='timestamp'>" + getCurrentTime() + "</span>";
                chatContainer.appendChild(newMessage);
                messageInput.value = "";
                chatContainer.scrollTop = chatContainer.scrollHeight;
            }
        }

        // تابعی برای گرفتن زمان فعلی
        function getCurrentTime() {
            var date = new Date();
            var hours = date.getHours();
            var minutes = date.getMinutes();
            return hours + ":" + (minutes < 10 ? "0" + minutes : minutes);
        }
    </script>
</body>
</html>
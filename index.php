<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        /* Custom CSS styles here */
        body {
            color: #000;
            overflow-x: hidden;
            height: 100%;
            background-image: linear-gradient(to right, #D500F9, #FFD54F);
            background-repeat: no-repeat;
        }

        .container {
            opacity: 0;
            animation: fadeIn 2s forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        input, textarea {
            background-color: #F3E5F5;
            border-radius: 50px !important;
            padding: 12px 15px 12px 15px !important;
            width: 100%;
            box-sizing: border-box;
            border: none !important;
            border: 1px solid #F3E5F5 !important;
            font-size: 16px !important;
            color: #000 !important;
            font-weight: 400;
            margin-bottom: 20px;
        }

        input:focus, textarea:focus {
            -moz-box-shadow: none !important;
            -webkit-box-shadow: none !important;
            box-shadow: none !important;
            border: 1px solid #D500F9 !important;
            outline-width: 0;
            font-weight: 400;
        }

        .btn-color {
            border-radius: 50px;
            color: #fff;
            background-image: linear-gradient(to right, #FFD54F, #D500F9);
            padding: 15px;
            cursor: pointer;
            border: none !important;
        }

        .btn-color:hover {
            color: #fff;
            background-image: linear-gradient(to right, #D500F9, #FFD54F);
        }

        .form-container {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 300px;
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            text-align: center;
        }

        .error-message {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h3>Login</h3>
            <form id="loginForm" action="process_login.php" method="post">
                <input type="text" id="username" name="username" placeholder="Username" class="form-control" required>
                <input type="password" id="password" name="password" placeholder="Password" class="form-control" required>
                <button type="submit" class="btn-color">Login</button>
            </form>
            <p id="error-message" class="error-message"></p>
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission

            var form = this;
            var formData = new FormData(form);

            var xhr = new XMLHttpRequest();
            xhr.open('POST', form.action, true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

            xhr.onload = function() {
                if (xhr.status >= 200 && xhr.status < 400) {
                    var response = xhr.responseText;

                    if (response.trim() === 'success') {
                        window.location.href = 'dashboard.php';
                    } else {
                        document.getElementById('error-message').textContent = response;
                    }
                } else {
                    console.error('Error: ' + xhr.status + ' - ' + xhr.statusText);
                }
            };

            xhr.onerror = function() {
                console.error('Request failed');
            };

            xhr.send(formData);
        });
    </script>
</body>
</html>

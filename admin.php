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
            <div id="login-message"></div>
            <form id="login-form">
                <input type="text" id="username" name="username" placeholder="Username" class="form-control" required>
                <input type="password" id="password" name="password" placeholder="Password" class="form-control" required>
                <button type="submit" class="btn-color">Login</button>
            </form>

            <!-- Add new user form -->
            <h3>Create Account</h3>
            <div id="registration-message"></div>
            <form id="registration-form">
                <input type="text" id="new_username" name="new_username" placeholder="New Username" class="form-control" required>
                <input type="password" id="new_password" name="new_password" placeholder="New Password" class="form-control" required>
                <button type="submit" class="btn-color">Create Account</button>
            </form>
        </div>
    </div>

    <script>
        // AJAX for login
        document.getElementById("login-form").addEventListener("submit", function(event){
            event.preventDefault(); // Prevent default form submission
            var formData = new FormData(this);
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "process_login.php", true);
            xhr.onload = function() {
                var response = JSON.parse(this.responseText);
                if (response.success) {
                    // Redirect to dashboard upon successful login
                    window.location.href = "dashboard.php";
                } else {
                    // Display error message for invalid credentials
                    document.getElementById("login-message").innerHTML = "<p class='error-message'>" + response.error + "</p>";
                }
            };
            xhr.send(formData);
        });

        // AJAX for registration
        document.getElementById("registration-form").addEventListener("submit", function(event){
            event.preventDefault(); // Prevent default form submission
            var formData = new FormData(this);
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "process_registration.php", true);
            xhr.onload = function() {
                var response = JSON.parse(this.responseText);
                if (response.success) {
                    // Display success message
                    document.getElementById("registration-message").innerHTML = "<p class='success-message'>" + response.message + "</p>";
                } else {
                    // Display error message for existing username or other errors
                    document.getElementById("registration-message").innerHTML = "<p class='error-message'>" + response.error + "</p>";
                }
            };
            xhr.send(formData);
        });
    </script>
</body>
</html>

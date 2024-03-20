<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://accounts.google.com/gsi/client" async></script>

</head>
<body>
<div class="container box-ALL">

    <div id="login-info"></div>
    <!-- "Вийти з акаунту" -->
    <button id="logoutBtn" style="display: none;" onclick="logoutUser()">Вийти з акаунту</button>

    <div id="loginForm">
        <h2>Авторизація</h2>

        <div id="g_id_onload"
            data-client_id="647304147612-o5t7qihkot1s6tsgni8s8f1v2fjf9qrk.apps.googleusercontent.com"
            data-context="signin"
            data-ux_mode="popup"
            data-callback="handleCredentialResponse"
            data-auto_prompt="false">
        </div>

        <div class="g_id_signin"
            data-type="standard"
            data-size="large"
            data-text="Увійти за допомогою Google"
            data-prompt_parent_id="none">
        </div>


        <form>
            <label for="username">Юзернейм:</label>
            <input type="text" id="username" name="username" >
            <br>
            <label for="password">Пароль:</label>
            <input type="text" id="password" name="password"  >
            <br>
            <button type="button" id="loginBtn" onclick="loginUser()" class="registration-btn">Увійти</button>
            <button type="button" id="showRegistrationForm">Реєстрація</button>

        </form>
    </div>

    <div id="registrationForm">
        <h2>Реєстрація</h2>
        <form>
            <label for="firstName">Ім'я:</label>
            <input type="text" id="firstName" name="firstName" required>
            <br>
            <label for="lastName">Прізвище:</label>
            <input type="text" id="lastName" name="lastName" required>
            <br>
            <label for="regUsername">Юзернейм:</label>
            <input type="text" id="regUsername" name="regUsername" required>
            <br>
            <label for="regPassword">Пароль:</label>
            <input type="text" id="regPassword" name="regPassword" required>
            <br>
            <label for="confirmPassword">Підтвердження пароля:</label>
            <input type="text" id="confirmPassword" name="confirmPassword" required>
            <br>
            <button type="button" id="registerBtn" onclick="registerUser()">Зареєструватися</button>
        </form>
    </div>
</div>

    <div class="box-ALL">
        <div class="TestHeader">
            Тест
        </div>
        <ul class="question">
            <li>2+2=? <br>
                <span style="font-size: 20px;">
                    a) 4 <br>
                    b) 5 <br>
                    c) 2
                </span> 
            </li>
            <hr>
            <li>4+2*2=?<br>
                <span style="font-size: 20px;">
                    a) 7 <br>
                    b) 8 <br>
                    c) 5
                </span>
            </li>
        </ul>

        <div class="answer">
            <div class="text-bold">Впишіть свої дані та відповіді</div>
                <form id="dataForm">
                    <div class="form-group">
                        <label for="name">Ім'я:</label>
                        <input type="text" id="name" name="name" required>
                    </div>

                    <div class="form-group">
                        <label for="age">Вік:</label>
                        <input type="text" id="age" name="age" required>
                    </div>

                    <div class="form-group">
                        <label for="response">Відповіді:</label>
                        <textarea id="response" name="response" required></textarea>
                    </div>

                    <div class="form-groups">
                        <button class='buttonStile' type="button" onclick="submitAnswer()">Відправити</button>
                        <button class='buttonStile' type="button" onclick="loadAnswer()">Завантажити відповіді користувачів</button>
                    </div>
                </form>
        </div>
        <div id="userAnswerAll"></div>
    </div>
    
<script>
        //Функція для входу google
        function handleCredentialResponse(response) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'auth_int.php', true); // перевірка токена Google
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.setRequestHeader('Accept', 'application/json');

        xhr.onload = function() {
            if (this.status == 200) {
                var data = JSON.parse(this.responseText);
                if (data.status == 1) {

                    document.getElementById('loginForm').style.display = 'none';
                    document.querySelector('.g_id_signin').style.display = 'none';
                    document.getElementById('logoutBtn').style.display = 'block';

                    var profileHTML = `
                        <h2 style="text-align: center;">Інформація користувача</h2>
                        <div style="text-align:center;">
                            <p><b>Ім'я: </b>${data.pdata.given_name}</p>
                            <p><b>Прізвище: </b>${data.pdata.family_name || ''}</p>
                            <p><b>Email: </b>${data.pdata.email}</p>
                        </div>
                    `;


                    document.getElementById('login-info').innerHTML = profileHTML;
                    document.getElementById('login-info').classList.remove("hidden");

                } else {
                    console.error('Error: ', data.msg);
                }
            } else {
                console.error('Failed to fetch user data: ', this.status);
            }
        };


        xhr.onerror = function() {
            console.error('Request failed');
        };

        // Відправка токена отриманого від Google
        xhr.send(JSON.stringify({ request_type: 'user_auth', credential: response.credential }));
    }

    
    document.addEventListener('DOMContentLoaded', function() {
        
        document.getElementById('registrationForm').style.display = 'none';
        document.getElementById('showRegistrationForm').addEventListener('click', function() {
            
            document.getElementById('loginForm').style.display = 'none';
            
            var googleSignInBtn = document.querySelector('.g_id_signin');
            if (googleSignInBtn) {
                googleSignInBtn.style.display = 'none';
            }
            
            document.getElementById('registrationForm').style.display = 'block';
        });
    });

    
function registerUser() {

event.preventDefault();

var formData = new FormData();
formData.append('firstName', document.getElementById('firstName').value);
formData.append('lastName', document.getElementById('lastName').value);
formData.append('username', document.getElementById('regUsername').value);
formData.append('password', document.getElementById('regPassword').value);

var xhr = new XMLHttpRequest();
xhr.open('POST', 'register.php', true);
xhr.onload = function() {
    if (this.status == 200) {
        if (this.responseText.includes("успішно зареєстровані")) {
            alert(this.responseText); // повідомлення про реєстрацію
           
            document.getElementById('registrationForm').style.display = 'none';
            document.getElementById('loginForm').style.display = 'block';

            var googleSignInBtn = document.querySelector('.g_id_signin');
            if (googleSignInBtn) {
                googleSignInBtn.style.display = 'block';
            }

        } else {
            alert("Помилка реєстрації: " + this.responseText);
        }
    } else {
        console.error('Помилка AJAX запиту: ' + this.status);
    }
};
xhr.onerror = function() {
    console.error('Помилка AJAX запиту');
};
xhr.send(formData); // Відправка форми
}

function loginUser() {
    event.preventDefault();

    var formData = new FormData();
    formData.append('username', document.getElementById('username').value);
    formData.append('password', document.getElementById('password').value);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'login.php', true);
    xhr.setRequestHeader('Accept', 'application/json');
    xhr.onload = function() {
        if (this.status == 200) {
            var response = JSON.parse(this.responseText);
            if (response.status === 'success') {
                document.getElementById('loginForm').style.display = 'none'; 
                document.querySelector('.g_id_signin').style.display = 'none'; 
                document.getElementById('logoutBtn').style.display = 'block'; 

                // інформація про користувача
                var userInfoHTML = `
                    <h2 style="text-align: center;">Інформація користувача</h2>
                    <p>Ім'я: ${response.firstName}</p>
                    <p>Прізвище: ${response.lastName}</p>
                `;
                document.getElementById('login-info').innerHTML = userInfoHTML;
                document.getElementById('login-info').style.display = 'block';
            } else {
                alert(response.message);
            }
        } else {
            console.error('Помилка AJAX запиту: ' + this.status);
        }
    };
    xhr.onerror = function() {
        console.error('Помилка AJAX запиту');
    };
    xhr.send(formData);
}

        // Функція виходу з акаунту
        function logoutUser() {
            
            document.getElementById('loginForm').style.display = 'block';
            document.querySelector('.g_id_signin').style.display = 'block';

            document.getElementById('login-info').innerHTML = '';
            document.getElementById('login-info').classList.add("hidden");

            document.getElementById('logoutBtn').style.display = 'none';
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('logoutBtn').addEventListener('click', function() {
                logoutUser();
            });
        });


</script>

    <script src="script.js"> </script>
</body>
</html>
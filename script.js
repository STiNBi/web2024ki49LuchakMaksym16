function submitAnswer() {
    var name = document.getElementById('name').value;
    var age = document.getElementById('age').value;
    var response = document.getElementById('response').value;

    var xhr = new XMLHttpRequest();
    var formData = new FormData(document.getElementById('dataForm'));
    xhr.open("POST", "submit_data.php", true);

    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            document.getElementById('name').value = '';
            document.getElementById('age').value = '';
            document.getElementById('response').value = '';
        }
    };
    xhr.send(formData);
    return false;
}


function loadAnswer() {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "get_ans_base.php", true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            console.log(xhr.responseText);
            var answers = JSON.parse(xhr.responseText);
            if (answers.length === 0) {
                document.getElementById("userAnswerAll").innerHTML = "";
            } else {
                var html = "";
                answers.forEach(function(answer) {
                    html += "<div class='answer'>";
                    html += "<p><strong>Ім'я: </strong>" + answer.name + "</p>";
                    html += "<p><strong>Вік: </strong>" + answer.age + "</p>";
                    html += "<p><strong>Відповідь: </strong>" + answer.response + "</p>";
                    html += "</div>";
                });
                document.getElementById("userAnswerAll").innerHTML = html;
            }
        }
    };
    xhr.send();
}
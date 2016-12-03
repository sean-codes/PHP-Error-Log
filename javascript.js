//Check and reset
check(1);
function check(reset){
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (xhttp.readyState == 4 && xhttp.status == 200) {
            var errors = JSON.parse(xhttp.responseText);
            errors.forEach(function(error){
                var li = document.createElement('li');
                li.classList = 'new_log';
                li.innerHTML = `
                    Date: ${error.date} <small>Client: ${error.client}</small> <br>
                    Location: ${error.location} <br>
                    <h3>${error.error}</h3>
                `;
                appendNewLog(li);
            });
            check(0);
        }
    };

    xhttp.open("GET", "check.php?" + (reset ? 'reset=1' : ''), true);
    xhttp.send();
}

function appendNewLog(error){
    var log = document.getElementById('errors');
    if(log.childNodes.length > 0)
        log.insertBefore(error, log.childNodes[0]); 
    else
        log.appendChild(error);

    //Remove Fade Out Class
    setTimeout(function(){
        error.classList.remove('new_log');
    }, 100, error);
}

function testError(){
    var newTestError = new XMLHttpRequest();
    newTestError.open("GET", "error.php", true);
    newTestError.send();
}

function clearLog(){
    var xhttp = new XMLHttpRequest();
    xhttp.open("GET", "clear.php", true);
    xhttp.send();

    document.getElementById('errors').innerHTML = "";
}
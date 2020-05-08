
function signIn(event) {
    event.preventDefault();

    var credentials = {
        'email': document.getElementById("email").value,
        'password': document.getElementById("password").value
    }
   
    $.ajax({
        type: "POST",
        url: base_url + "/api/login.php",
        data: JSON.stringify(credentials),
        success: function (data, status, xhr) {
            data = JSON.parse(data);
            console.log(data);
        },
        error: function (response) {
            console.log(response.responseText);
        }
    });

}



var base_url = "http://localhost/app/user";


$(document).ready(function () {

    $("#signin_btn").click(signIn);

});


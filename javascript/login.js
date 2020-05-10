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
            Cookies.set("name", data.name);
            Cookies.set("email", data.email);
            Cookies.set("id", data.id);
            Cookies.set("token", data.jwt);
            document.location.href = "userprofile";
        },
        error: function (response) {
            console.log(response.responseText);
            alert("Credentials do not match");
        }
    });

}



var base_url = "http://localhost/app/user";


$(document).ready(function () {

    $("#signin_btn").click(signIn);

});
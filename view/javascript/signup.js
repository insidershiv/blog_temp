var base_url = "http://localhost/app/user";



function signup(event) {
    event.preventDefault();
    console.log("ss");
    var credentials = {
        'name': document.getElementById("name").value,
        'email': document.getElementById("email").value,
        'password': document.getElementById("password").value
    };
    
    console.log(credentials);


    $.ajax({
        type: "POST",
        url: base_url + "/api/user",
        data: JSON.stringify(credentials),
        success: function (response, status, xhr) {
            data = JSON.parse(response);
            console.log(data);
        },
        error: function (xhr, textStatus, errorMessage) {
            body = JSON.parse(xhr.responseText);
            alert(body.msg);
        }
    });

}



$(document).ready(function () {


    // $("#signup").click(signup);

    $("#signbtn").click(signup);




});
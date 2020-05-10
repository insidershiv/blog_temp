function signIn(event) {
    event.preventDefault();

    var credentials = {
        'email': document.getElementById("email").value,
        'password': document.getElementById("password").value
    }
   
    $.ajax({
        type: "POST",
        url: base_url + "/api/login",
        data: JSON.stringify(credentials),
        success: function (data, status, xhr) {
            data = JSON.parse(data);
            
            Cookies.set("name", data.name, { sameSite: 'lax' });
            Cookies.set("email", data.email, { sameSite: 'lax' });
            Cookies.set("user_id", data.user_id, { sameSite: 'lax' });
            Cookies.set("token", data.jwt, { sameSite: 'lax' });

            document.location.href = "userprofile";
        },  
        error: function (response) {
            data = response.responseText;
            alert("Credentials do not match");
        }
    });

}



var base_url = "http://localhost/app/user";


$(document).ready(function () {

    // coookie avaible
    
    if(Cookies.get("user_id") != undefined){
        document.location.href="userprofile";

   

    }
    else {
        $("#signin_btn").click(signIn);
    }
});
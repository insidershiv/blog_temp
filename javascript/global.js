

function logout(event) {
    event.preventDefault();

    Cookies.remove("name", {
        sameSite: 'lax'
    });
    Cookies.remove("user_id", {
        sameSite: 'lax'
    });
    Cookies.remove("token", {
        sameSite: 'lax'
    });
    document.location.href = "index";
}


$(document).ready(function () {
    
$("#logout").click(logout);
});
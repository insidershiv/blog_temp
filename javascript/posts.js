base_url = "http://localhost/app/blog/api";

$(document).ready(function () {

        $("#post_btn").click(create_post);
        $("#logout").click(logout);

});


function create_post(event) {
    event.preventDefault();


    var data = {

        "post_title": document.getElementById("title").value,
        "post_content": document.getElementById("post_content").value,
    }
    console.log(data);

    $.ajax({
        type: "POST",
        url: base_url + "/blog",
        data: JSON.stringify(data),
        headers: { Authorization: "Bearer " + Cookies.get("token") },
        success: function (response) {
            console.log(response);
            data = JSON.parse(response);
            console.log("sucess " + data.msg);
        },

        error: function (response) {
            data = JSON.parse(response.responseText);
            console.log("error " + data.msg);
        }
    });



}



function logout(event) {
    event.preventDefault();

    Cookies.remove("name", { sameSite: 'lax' });
    Cookies.remove("user_id",{ sameSite: 'lax' });
    Cookies.remove("token", { sameSite: 'lax' });
    document.location.href = "index";
}

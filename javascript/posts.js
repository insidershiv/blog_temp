base_url = "http://localhost/app/blog/api";


$(document).ready(function () {

    

if (Cookies.get("token") == undefined) {
    document.location.href = "index";
} else {

    var name = Cookies.get("name");
    $("#items").prepend('<li class="nav-item" id="username"> <a href="#" >' + name + '</a></li>');

    if (localStorage.getItem("gid") == null) {

        document.getElementById("post_btn").innerText = "Post";
        
        $("#post_btn").click(create_post);
        localStorage.removeItem("gid");
        localStorage.removeItem("gpost_title");
        localStorage.removeItem("gpost_body");
    
    } else {
        
        document.getElementById("post_btn").innerText = "Update";

        var id = localStorage.getItem("gid");
        var title = localStorage.getItem("gpost_title");
        var body = localStorage.getItem("gpost_body");
        
        document.getElementById("title").value = title;
        document.getElementById("post_content").value = body;
        
        $("#post_btn").click(update_post);
         localStorage.removeItem("gid");
        localStorage.removeItem("gpost_title");
        localStorage.removeItem("gpost_body");

    
    }
       

  
}

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
        headers: {
            Authorization: "Bearer " + Cookies.get("token")
        },
        success: function (response) {
            console.log(response);
            data = JSON.parse(response);
            if(window.confirm(data.msg)){
                document.location.href="userprofile";

            }else {
                document.location.href="posts";
            }
            
        },

        error: function (response) {
            data = JSON.parse(response.responseText);
            console.log("error " + data.msg);
        }
    });



}

function update_post(event){

    event.preventDefault();

  
    var data = {

        "post_title": document.getElementById("title").value,
        "post_content": document.getElementById("post_content").value,
    }
    console.log (localStorage.getItem("gid"));
    $.ajax({
        type: "PATCH",
        url: base_url+"/blog/" + localStorage.getItem("gid"),
        data: JSON.stringify(data),
        headers: {
            Authorization: "Bearer " + Cookies.get("token")
        },
        success: function (response,status,xhr) {
            data = JSON.parse(response);
            console.log(data);
        },
        error: function (xhr, textStatus, errorMessage){
            console.log(xhr.responseText);
        }
    });



}


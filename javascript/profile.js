base_url = "http://localhost/app/blog";

$(document).ready(function () {


    var name = Cookies.get("name");


    $("#items").prepend('<li class="nav-item" id="username"> <a href="#" >' + name + '</a></li>');
    console.log(Cookies.get("token"));

    $("#post").click(get_post);
    $("#logout").click(logout);



});


function get_post(event) {
    
    event.preventDefault();
   
   
    $.ajax({
        type: "GET",
        url: base_url + "/api/blog/",
        headers: { Authorization: "Bearer " + Cookies.get("token") },
       
        success: function (response, status, xhr) {
            data = JSON.parse(response);
            appened_post(data);

            
        },

        error: function(response){
            data = JSON.parse(response);
            console.log(response);
        }
    });


}


function logout(event) {
    event.preventDefault();

    Cookies.remove("name", { sameSite: 'lax' });
    Cookies.remove("user_id",{ sameSite: 'lax' });
    Cookies.remove("token", { sameSite: 'lax' });
    document.location.href = "userlogin";
}


function appened_post(data) {

    
    my_id = Cookies.get("user_id");
    for(i =0;i<data.length;i++) {
        
        var childs = "<li id=" + data[i].post_id + ">"  + "<br>" + "<h3 class=title> "  + data[i].post_title + "</h3>" + "<section>" + data[i].post_content + "</section>" + "<br>";
        if (data[i].user_id == my_id)
            childs = childs + `<button id = {{data[i].post_id}}>Delete</button>`;
        
        childs = childs + "</li>" 
        $("#post-items").prepend(childs);

    }





}
base_url = "http://localhost/app/blog";
var id = undefined;
var body = undefined;
var title = undefined; 

$(document).ready(function () {


    // check if cookie has some datza

    if (Cookies.get("user_id") == undefined) {

        document.location.href = "index";

    } else {

        var name = Cookies.get("name");


        $("#items").prepend('<li class="nav-item" id="username"> <a href="userprofile" >' + name + '</a></li>');
        document.onload = get_post();

    }

});


function get_post() {




    $.ajax({
        type: "GET",
        url: base_url + "/api/blog/",
        headers: {
            Authorization: "Bearer " + Cookies.get("token")
        },

        success: function (response, status, xhr) {
            data = JSON.parse(response);
            appened_post(data);


        },

        error: function (response) {
            data = JSON.parse(response);
            console.log(response);
        }
    });


}




function delete_post(id) {


    //delete ajax request method
            
             id = id.substr(1,id.length-1);
                
          
            


    $.ajax({
        type: "DELETE",
        url: base_url + "/api/blog/" + id,
        headers: {
            Authorization: "Bearer " + Cookies.get("token")
        },
        success: function (response) {
            data = JSON.parse(response);
            console.log(data);
            
            var posts = document.getElementById("post-items");   //getting post element it is an object

            
            var childNodes = posts.childNodes;                 //getting childnodes of the postnodes array of objects
            var post = undefined;
            for ( var i =0 ; i < childNodes.length ; i++){      //searching and trying to match the id of the button with the id of the <li> to be deleted
                
                if ( childNodes[i].id == "l"+ id){
                    post = childNodes[i];
                    break;
                }
                
            } 
            posts.removeChild(post);                    //removing the post from UI by removing child
            
        }, 

        error: function (response) {
            
            console.log(response.responseText);
            // data = JSON.parse(response);
            // console.log(data);
        }
    });


}


function appened_post(data) {


    my_id = Cookies.get("user_id");
    for (i = 0; i < data.length; i++) {
        var count = 0 ;
        
        var childs = "<div id= l" + data[i].post_id + " class='post_item'>"  +  "<br>" +  "<h3 class=title> " + data[i].post_title + "</h3>" + "<section>" + data[i].post_content + "</section>" + "<br>";
        if (data[i].user_id == my_id)
            param_data = data[i];
            
            childs = childs + "<button id = d" + data[i].post_id + " class='submit' onclick=" + "delete_post(this.id) " + ">Delete</button>";
            childs = childs + "<button id = e" + data[i].post_id + " class='submit' onclick="+"edit_post(this.id)"  + ">Edit Post</button>";


        childs = childs + "</div>"
        $("#post-items").prepend(childs);

    }





}

function edit_post(id) {
    
    

    id = id.substr(1,id.length-1);                      //id is the post_id which we want to edit we got from clicking the button
                
    var posts = document.getElementById("post-items");

    
    var childNodes = posts.childNodes;
    var post = undefined;
    for ( var i =0 ; i < childNodes.length ; i++){      //searching for the matching id among the posts
        
        if ( childNodes[i].id == "l"+ id){
            post = childNodes[i];
            break;
        }
        
    }
    
    localStorage.setItem("gid", id);               //creating client side storage to store id, title, body of the post

    localStorage.setItem("gpost_title",post.childNodes[1].innerText);
    localStorage.setItem("gpost_body",post.childNodes[2].innerHTML);
    
    document.location.href = "posts";
}

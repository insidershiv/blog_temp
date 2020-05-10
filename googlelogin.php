<?php


include '../util/gconfig.php';


if (isset($_GET["code"])) {
    $token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);

    if (!isset($token['error'])) {
        //Set the access token used for requests
        $google_client->setAccessToken($token['access_token']);

        //Store "access_token" value in $_SESSION variable for future use.


        //Create Object of Google Service OAuth 2 class
        $google_service = new Google_Service_Oauth2($google_client);

        //Get user profile data from google
        $data = $google_service->userinfo->get();
        $name = '';
        $email = '';

        if (!empty($data['given_name'])) {
            $name = $data['given_name'];
        }

        if (!empty($data['family_name'])) {
            $name =     $name . ' ' . $data['family_name'];
        }


        if (!empty($data['email'])) {
            $email = $data['email'];
        }



        echo $name   . "  "  .  $email;
    }
}

?>




<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous">
</script>
<script src="javascript/jecookie.js"></script>





<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="">





    <script type="text/javascript">
        $(document).ready(function() {


            console.log("svjbdsvj");


            if (checkToken == true) {
                console.log("redirect");


            } else {


                <?php
                echo "console.log('No uservdvcd')";



                ?>


            }




        });







        function checkToken() {

            var id = Cookies.get('id');
            var token = Cookies.get('token');



            if (id && token) {


                return true;


            }
            return false;


        }
    </script>










</head>

<body>
    <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="#">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

    <form>

        <input type="text" name="email" id="email" placeholder="Email">
        <br>
        <input type="text" name="password" id="password" placeholder="Password">

        <input type="submit" name="submit">



    </form>


    <?php

    $google_login_btn = '<a href="' . $google_client->createAuthUrl() . '"><img src="https://www.tutsmake.com/wp-content/uploads/2019/12/google-login-image.png" /></a>';




    echo '<div align="center">' . $google_login_btn . '</div>';
    ?>




</body>

</html>
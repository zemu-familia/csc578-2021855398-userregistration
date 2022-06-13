<?php
require_once("db_connect.php");



if(isset($_POST['email'])){
    //$conn;
    if(!empty($_POST['email'])){
        $query = "SELECT uid FROM users WHERE email = '{$_POST['email']}'";
        $result = mysqli_query($conn, $query);

        if($result){
            if(mysqli_num_rows($result) > 0){
                echo "This email has already been registered.";
            }
        }else{
            echo "An error occurred.";
        }
    }else {
        echo '';
    }
}
?>
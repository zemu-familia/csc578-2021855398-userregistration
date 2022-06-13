<?php
require_once('db_connect.php');

if(isset($_GET['verify'])){
    $query = "SELECT * FROM verification WHERE hash = '{$_GET['verify']}'";

    $result = mysqli_query($conn, $query);
    if($result){
        if(mysqli_num_rows($result) == 1){
            $row = mysqli_fetch_row($result);

            $query = "UPDATE verification SET verified = 'true' WHERE hash = '{$_GET['verify']}'";
            $result = mysqli_query($conn, $query);
            if(mysqli_affected_rows($conn) == 1){
                echo "Your registration is complete! <a href='login.php'>Click here to Login</a>";
            }else{
                echo "An error unexpected error occurred.<br>" . mysqli_error($conn);
            }
        }else{
            echo "This verification is invalid.";
        }
    }else{
        echo "An error unexpected error occurred.<br>" . mysqli_error($conn);
    }
}
?>
<?php
session_start();
include_once("db_connect.php");
if(isset($_SESSION['user_id'])!="") {
    header("Location: index.php");
}
if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $result = mysqli_query($conn, "SELECT uid, user, pass, verified FROM users A INNER JOIN verification B ON A.uid = B.userID WHERE email = '$email'");
    if($result){
        if ($row = mysqli_fetch_assoc($result)) {
            $pass = $row['pass'];
            if (password_verify($password, $pass)){
                $_SESSION['user_id'] = $row['uid'];
                $_SESSION['user_name'] = $row['user'];
                if($row['verified'] == 'true')
                    header("Location: index.php");
                else
                    $error_message = "Registration incomplete!!!";
            }else
                $error_message = "Incorrect Email or Password!!!";
        } else {
            $error_message = "Incorrect Email or Password!!!";
        }
    }else{
        echo "An error occurred. " . mysqli_error($conn);
    }

}
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
<script src="/js/jquery-3.6.0.min.js"></script>
<script>
    // https://stackoverflow.com/questions/18584389/listen-to-mouse-hold-event-on-website
    $(document).ready(function (){
        var passText = $("#passField");

        $("#peekBtn").on("mousedown mouseup", function mouseState(e){
            if(e.type == "mousedown"){
                passText.prop("type", "text");
            }else{
                passText.prop("type", "password");
            }
        })
    });
</script>
<div class="container">
    <h2>Example: Login and Registration Script with PHP, MySQL</h2>
    <h4>Password Peek, Email Validity and Verification</h4>
    <div class="row">
        <div class="col-md-4 col-md-offset-4 well">
            <form role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="loginform">
                <fieldset>
                    <div class="row">
                        <legend>Login</legend>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label for="name">Email</label>
                            <input type="text" name="email" placeholder="Your Email" required class="form-control" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                                <label for="name">Password</label>
                                <input type="password" id="passField" name="password" placeholder="Your Password" required class="form-control" />
                        </div>
                        <div class="col mt-4">
                            <button class="btn btn-primary" type="button" id="peekBtn">Peek</button>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="submit" name="login" value="Login" class="btn btn-primary" />
                    </div>
                </fieldset>
            </form>
            <span class="text-danger"><?php if (isset($error_message)) { echo $error_message; } ?></span>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 col-md-offset-4 text-center">
            New User? <a href="register.php">Sign Up Here</a>
        </div>
    </div>
</div>
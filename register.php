<?php
include_once("db_connect.php");
session_start();
if(isset($_SESSION['user_id'])) {
    header("Location: index.php");
}
$error = false;
if (isset($_POST['signup'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $cpassword = mysqli_real_escape_string($conn, $_POST['cpassword']);
    if (!preg_match("/^[a-zA-Z ]+$/",$name)) {
        $error = true;
        $uname_error = "Name must contain only alphabets and space";
    }
    if(!filter_var($email,FILTER_VALIDATE_EMAIL)) {
        $error = true;
        $email_error = "Please Enter Valid Email ID";
    }
    if(strlen($password) < 6) {
        $error = true;
        $password_error = "Password must be minimum of 6 characters";
    }
    if($password != $cpassword) {
        $error = true;
        $cpassword_error = "Password and Confirm Password doesn't match";
    }
    if (!$error) {
        if(mysqli_query($conn, "INSERT INTO users(user, email, pass) VALUES('" . $name . "', '" . $email . "', '" . password_hash($password, PASSWORD_BCRYPT) . "')")) {
            $hash = password_hash(uniqid(), PASSWORD_BCRYPT);

            $query = "INSERT INTO verification(userID, hash, verified) VALUES((SELECT uid FROM users WHERE user = '$name'),'$hash', 'false')";
            if(mysqli_query($conn, $query)){
                $msg = "<html><body><div><h2>You have signed up on localhost!</h2><p>Just one final step.</p><p>Click <a href='localhost/php-exercise/verifySuccess.php?verify=$hash'>here</a> to verify your email address!</p></div></body></html>";
                mail($email, "PHP Exercise Registration - Verify your email.", $msg, 'Content-type:text/html;charset=UTF-8');
                $success_message = "Registration successful. Check your email for the verification process!</a>";
            }else{
                $error_message = "An unexpected error occurred.<br>" . mysqli_error($conn);
            }

        } else {
            $error_message = "Error in registering...Please try again later!" . mysqli_error($conn);
        }
    }
}
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
<script src="/js/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function (){
        $("#emailField").on("keyup change", function (){
            var emailVal = $("#emailField").val();
            $.ajax(
                {
                    url: "functions.php",
                    type: "POST",
                    data:jQuery.param({email: emailVal}),
                    success: function(result){
                        $("#emailError").html(result);
                    }
                }
                );
        })

    })
</script>
<div class="container">
    <h2>Example: Login and Registration Script with PHP, MySQL</h2>
    <div class="row">
        <div class="col-md-4 col-md-offset-4 well">
            <form role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="signupform">
                <fieldset>
                    <legend>Sign Up</legend>
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" placeholder="Enter Full Name" required value="<?php if($error) echo $name; ?>" class="form-control" />
                        <span class="text-danger"><?php if (isset($uname_error)) echo $uname_error; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="name">Email</label>
                        <input type="text" name="email" id="emailField" placeholder="Email" required value="<?php if($error) echo $email; ?>" class="form-control" />
                        <span id="emailError" class="text-danger"><?php if (isset($email_error)) echo $email_error; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="name">Password</label>
                        <input type="password" name="password" placeholder="Password" required class="form-control" />
                        <span class="text-danger"><?php if (isset($password_error)) echo $password_error; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="name">Confirm Password</label>
                        <input type="password" name="cpassword" placeholder="Confirm Password" required class="form-control" />
                        <span class="text-danger"><?php if (isset($cpassword_error)) echo $cpassword_error; ?></span>
                    </div>
                    <div class="form-group">
                        <input type="submit" name="signup" value="Sign Up" class="btn btn-primary" />
                    </div>
                </fieldset>
            </form>
            <span class="text-success"><?php if (isset($success_message)) { echo $success_message; } ?></span>
            <span class="text-danger"><?php if (isset($error_message)) { echo $error_message; } ?></span>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 col-md-offset-4 text-center">
            Already Registered? <a href="login.php">Login Here</a>
        </div>
    </div>
</div>
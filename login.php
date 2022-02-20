<?php
session_start();
$user = null;
extract($_GET,0);
require_once ('AllClass/login.php');
$login = new login();
$login->IssetLogin('index');//check login or not login only for sign in page
$login->CheckLoginCookies('inc/login.inc.php');//check remember or not

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Chatting System Login Page</title>
    <link rel="stylesheet" href="bootstrap-4.1.2/bootstrap.min.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/popper.js"></script>
    <style>
        .hover-bg:hover {
            box-shadow: 0 .5rem 1rem #27ff006b!important;
            background: #8de262!important;
            color: white;
        }
        .hover-bg {
            -webkit-transition: all 200ms ease;
            -moz-transition: all 200ms ease;
            -ms-transition: all 200ms ease;
            -o-transition: all 200ms ease;
            transition: all 700ms ease;
        }
    </style>
</head>
<body class="bg-light body">
<div class="container">
    <div class="row justify-content-center m-auto flex-lg-wrap">
        <div class="col-md-12">
            <br>
        </div>
        <div class="col-md-6 offset-3">

        </div>
        <div class="col-md-6 border-dark rounded p-5 mt-5 bg-white shadow hover-bg">
            <h1 class="text-center">Login here</h1>
            <?php
            if (isset($_SESSION['message']['error']) && !empty($_SESSION['message']['error'])):
            ?>
            <div class="col-md-10 offset-1">
                <div class="row">
                    <div class="position-absolute message-section d-block m-auto w-100">
                        <div class="alert alert-danger alert-dismissible">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><?php echo $_SESSION['message']['error'];?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            endif;
            unset($_SESSION['message']['error']);
            ?>
            <form action="inc/login.inc.php" method="post" class="p-5">
                <div class="form-group">
                    <label for="email">User Name/Email Address</label>
                    <input type="text" id="email" class="form-control" placeholder="User Name/Email Address" required name="email" value="<?php echo($user);?>">
                </div>
                <div class="form-group">
                    <label for="password">Account Password</label>
                    <input type="password" id="password" class="form-control" placeholder="Account password" required name="password">
                </div>
                <div class="form-group">
                    <input type="checkbox" id="remember" name="remember"><label for="remember">Remember me</label>
                    <a href="#" class="float-right"><label>Forgot Password</label></a>
                    <input type="submit" id="" class="form-control bg-success p-2 text-uppercase text-white" value="Submit" name="login">
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
<?php
unset($login);
?>
<!--<script>-->
<!--    $(document).ready(function () {-->
<!--        $('body').changedTouches-->
<!--    })-->
<!--</script>-->

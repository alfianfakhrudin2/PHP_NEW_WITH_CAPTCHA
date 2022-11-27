<?php
// session untuk memeriksa apakah user sudah melakukan login
session_start();
require 'koneksi.php';
$rand = rand(9999, 1000);
$error = '';

// cek cookie
if (isset($_COOKIE['user_id']) && isset($_COOKIE['key']) ){
    $id = $_COOKIE['user_id'];
    $key = $_COOKIE['key'];
    // ambil username berdasarkan id
    $result = mysqli_query($conn, "SELECT username FROM user WHERE user_id = $user_id");
    $row = mysqli_fetch_assoc($result);

    // cek cookie dan username
    if ($key === hash('sha512', $row['username'])) {
        $_SESSION['login'] = true;
    }
} 


if ( isset($_SESSION["login"])) {
    header("location: index.php");
    exit;
}


if (isset($_POST['login'])) {
    $username = stripslashes($_POST['username']);
    $username = mysqli_real_escape_string($conn, $username);
    $password = stripslashes($_POST['password']);
    $password = mysqli_real_escape_string($conn, $password);
    $captcha = $_POST["captcha"];
    $confirmcaptcha = $_POST["confirmcaptcha"];

    // Vaslidasi captcha
    if ($captcha != $confirmcaptcha){
        echo "<div class='alert alert-danger alert-dismissible fade show' role='alert' style='width: 400px; text-align:center; margin-left: 485px; margin-top:15px; position:fixed;'>
        <strong>Incorrect captcha code!</strong> Enter Again.
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        // echo "<div class='alert alert-danger' role='alert' style='width: 300px; text-align:center; margin-left:530px; margin-top:15px; position:fixed;'>
        // Invalid Captcha Code!
        // </div>";
    } else {
        $result = mysqli_query($conn, "SELECT * FROM user WHERE username = '$username'");
        $hitung = mysqli_num_rows($result);
        $pwd = mysqli_fetch_array($result);


        // cek username
        if ($hitung > 0) {
            // cek password
            // $row = mysqli_fetch_assoc($result);
            if (password_verify($password, $pwd['password'])) {
                // set Session
                $_SESSION['name'] = $pwd['name'];
                $_SESSION['login'] = true;

                // Remember Me
                if ( isset($_POST['remember']) ) {
                    // Cookie
                    // setcookie('login', 'true', time() + 60);
                    setcookie('user_id', $row['user_id'], time()+60);
                    setcookie('key', hash('sha512', $row['username']), time()+60);
                }
                header("location: dashboard.php");
            } else {
                echo "<div class='alert alert-danger alert-dismissible fade show' role='alert' style='width: 400px; text-align:center; margin-left: 485px; margin-top:15px; position:fixed;'>
        <strong>Incorrect password or username!</strong> Enter Again.
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";}
        } else {
            echo "<div class='alert alert-danger alert-dismissible fade show' role='alert' style='width: 400px; text-align:center; margin-left: 485px; margin-top:15px; position:fixed;'>
            <strong>Incorrect password or username!</strong> Enter Again.
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
        }
    }
}   
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Login</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <!-- favicon -->
        <link rel="icon" type="image/x-icon" href="asset/img/logoler.svg">
        <!-- css login -->
        <link rel="stylesheet" href="asset/css/login.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
        <style>
            .captcha{
                width: 50%;
                background: yellow;
                text-align: center;
                font-size: 24px;
                font-weight: 700px;
                border-radius: 12px;
            }
            .ReloadBtn{
            background: url('asset/img/refresh.png') left top no-repeat;
            background-size: 100%;
            width: 28px;
            height: 32px;
            border: 0px;
            outline: none;
            position: absolute;
            bottom: 180px;
            left: 220px;
            cursor: pointer;
            }
        </style>
    </head>
    <body>
        <div class="center">
            <h1>
                Login
            </h1>
            <form method="post">
                <?php if($error !=  ''){ ?>
                    <div class="alert  alert-danger" role="alert"><?= $error;?></div>
                <?php } ?>
                <div class="txt_field">
                    <input type="text" name="username" id="username" required>
                    <span></span>
                    <label for="username">Username</label>
                </div>
                <div class="txt_field">
                    <input type="password" name="password" id="password" required>
                    <span></span>
                    <label for="password">Password</label>
                </div>
                <div class="txt_field">
                    <input type="text" name="confirmcaptcha" id="captcha"  required data_parsley_trigger="keyup" value="" required>
                    <input type="hidden" name="captcha-rand" value="<?php echo $rand; ?>">
                    <span></span>
                    <label for="captcha">Enter Captcha!</label>
                </div>
                <label for="captcha-code" style="text-align: left;">Captcha code</label>
                <div class="txt-field" style="margin-bottom: 20px;">
                    <!-- <input type="text" name="captcha" id="captcha" placeholder="Enter Captcha!" required> -->
                    <!-- <span class="fas fa-lock"></span> -->
                    <input type="text" class="captcha" name="captcha" style="pointer-events: none;" value="<?php echo substr(uniqid(), 5);?>"></input>
                    <input type="button" class="ReloadBtn" onclick="CreateCaptcha()">
                </div>
                
                <div style="margin-bottom: 20px;">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember">Remember Me</label>
                </div>
                <button type="submit" name="login" id="login" class="btn">Sign in</button>
                <p class="signup_link">
                don't have account? <a href="registrasi.php" style="color: #6c5ce7;">Sign Up</a>
                </p>
            </form>
        </div>
        <script src="asset/js/captcha.js"></script>
        <script src="asset/js/jquery.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
    </body>
</html>
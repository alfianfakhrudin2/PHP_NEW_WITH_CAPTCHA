<?php
// session untuk memeriksa apakah user sudah melakukan login
session_start();

if( isset($_SESSION["login"]))
    header("location: index.php");

require "koneksi.php";

// if (isset($_POST['register'])) {
//     if (registrasi($_POST) > 0) {
//         echo "<script> 
//         alert('user baru berhasil di tambahkan! ');
//         </script>";
//     } else {
//         echo mysqli_error($conn);
//     }
// }
?>


<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="UTF-8">
        <title>Registrasi Page</title>
        <link rel="icon" type="image/x-icon" href="asset/img/logoler.svg">
        <link rel="stylesheet" href="asset/css/registrasi.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <div class="container">
            <div class="title">Registration</div>
            <div class="content">
                <div>
                    <!-- logic register -->
                    <?php 
                    $error= '';
                    $validate='';
                    // untuk daftar
                    if (isset($_POST['submit'])) {
                        // jika tombol di klik
                        $username = stripslashes($_POST['username']);
                        $username = mysqli_real_escape_string($conn, $username);
                        $name = stripslashes($_POST['name']);
                        $name = mysqli_real_escape_string($conn, $name);
                        $password = stripslashes($_POST['password']);
                        $password = mysqli_real_escape_string($conn, $password);
                        
                        $dob = $_POST['dob'];
                        $phone = $_POST['phone'];
                        $jenis_kelamin = $_POST['jenis_kelamin'];
                        $password2 = stripslashes($_POST['password2']);
                        $password2 = mysqli_real_escape_string($conn, $password2);

                        

                        if ($password == $password2) {
                            // untuk eknripsi password
                            $password = password_hash($password, PASSWORD_DEFAULT);
                            if(cek_nama($name, $conn) == 0){
                                // untuk eknripsi password
                                $password = password_hash($password, PASSWORD_DEFAULT);
                                // untuk memasukan ke db
                                $script = "INSERT INTO user SET username='$username',name='$name' ,password='$password' ,dob='$dob',phone='$phone' ,jenis_kelamin='$jenis_kelamin'";
                                $query = mysqli_query($conn, $script);
                                if ($query) {
                                    $_SESSION['username'] = $username;
                                    header("location: index.php");
                                } else{
                                    $error = 'register gagal';
                                }
                            } else {
                                $error = 'Username sudah terdaftar!';
                            }
                        } else {
                            $validate = 'Password tidak sama!';
                        }
                        
                        

                        // if ($query) {
                        //     // jika berhasil maka akan ke halaman login
                        //     header("location: index.php");
                        // } else {
                        //     // dan jika gagal maka akan memunculkan alert gagal
                        //     echo '<script>
                        //             alert("Registrasi gagal");
                        //             window.location.href="register.php";
                        //         </script>';
                        // }
                    }
                    function cek_nama($username, $conn){
                        $name = mysqli_real_escape_string($conn, $username);
                        $script = "SELECT * FROM user WHERE username = '$name'";
                        if($query = mysqli_query($conn, $script)) return mysqli_num_rows($query);
                    }
                    ?>
                    <!-- End logic register -->
                </div>
                <form method="post">
                    <div class="user-details">
                    <div class="input-box">
                        <span class="details">Full Name</span>
                        <input type="text" placeholder="Enter your name" name="name" id="name" required>
                    </div>
                    <div class="input-box">
                        <span class="details">Username</span>
                        <input type="text" placeholder="Enter your username" name="username" id="username" required>
                    </div>
                    <div class="input-box">
                        <span class="details">DOB</span>
                        <input type="date" placeholder="Enter your dob" name="dob" id="dob" required>
                    </div>
                    <div class="input-box">
                        <span class="details">Phone Number</span>
                        <input type="number" placeholder="Enter your number" name="phone" id="phone"  required>
                    </div>
                    <div class="input-box">
                        <span class="details">Password</span>
                        <input type="password" name="password" id="password" placeholder="Enter your password" required>
                    </div>
                    <div class="input-box">
                        <span class="details">Confirm Password</span>
                        <input type="password" name="password2" id="password2" placeholder="Confirm your password" required>
                    </div>
                    </div>
                    <div class="gender-details">
                        <input type="radio" name="jenis_kelamin" value="Laki-Laki" id="dot-1">
                        <input type="radio" name="jenis_kelamin" value="Perempuan" id="dot-2">
                        <input type="radio" name="jenis_kelamin" value="null" id="dot-3">
                        <span class="gender-title">Gender</span>
                        <div class="category">
                            <label for="dot-1">
                                <span class="dot one"></span>
                                <span class="gender">Male</span>
                            </label>
                            <label for="dot-2">
                                <span class="dot two"></span>
                                <span class="gender">Female</span>
                            </label>
                            <label for="dot-3">
                                <span class="dot three"></span>
                                <span class="gender">Prefer not to say</span>
                            </label>
                        </div>
                    </div>
                    <div class="button">
                    <input type="submit" name="submit" value="submit">
                    </div>
                    <p class="sign-up" style="font-size:16px;">
                        Have already an account ?
                        <a href="index.php" style="color: #6c5ce7;">Login here</a>
                    </p>
                </form>
            </div>
        </div>
    </body>
</html>

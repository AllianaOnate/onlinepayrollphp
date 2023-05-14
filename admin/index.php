<?php
  session_start();
  if(isset($_SESSION['admin'])){
    header('location:home.php');
  }
?>


<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <style type="text/css">


        * {
            margin: 0;
            padding: 0;
        }


        .login-box{
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            width: 100%;
            background: url('../images/login_bg.png');
            background-position: center;
            background-size: cover;
        }


        .login-box-body{
            margin-left: 300px;
            position: relative;
            width: 400px;
            height: 450px;
            background: transparent;
            border: 2px solid rgba(255,255,255,0.5);
            border-radius: 20px;
            backdrop-filter: blur(15px);
            display: flex;
            justify-content: center;
            align-items: center;
        }


        h2{
            font-size: 2em;
            color: #fff;
            text-align: center;
        }
        
        .form-group{
            position: relative;
            margin: 30px 0;
            width: 310px;
            border-bottom: 2px solid #fff;
        }


        .form-group label{
            position: absolute;
            top: 50%;
            left: 5px;
            transform: translateY(-50%);
            color: #fff;
            font-size: 1em;
            pointer-events: none;
            transition: .5s;
        }


        input:focus ~ label,
        input:valid ~ label{
            top: -5px;
        }


        .form-group input{
            width: 100%;
            height: 50px;
            background: transparent;
            border: none;
            outline: none;
            font-size: 1em;
            padding: 0 35px 0 5px;
            color: #fff;
        }


        button {
			width: 100%;
			height: 40px;
			border-radius: 40px;
			background: #6e4cb3;
			border-color: none;
			color: white;
			outline: none;
			cursor: pointer;
			font-size: 1em;
			font-weight: 600;
		}


        .form-group ion-icon{
            position: absolute;
            right: 8px;
            color: #fff;
            font-size: 1.2em;
            top: 20px;
        }


        .user-login-btn {
            padding-left: 10px;
            padding-top: 20px;
            color: #fff;
        }


        .time-in-out-btn{
            padding-left: 10px;
            color: #fff;
        }


    </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-box-body">
        <form action="login.php" method="POST">
            <h2>Admin Login</h2>
            <div class="form-group has-feedback">
                <ion-icon name="person-outline"></ion-icon>
                <input type="text" class="form-control" name="username" required>
                <label>Username</label>
            </div>
            <div class="form-group has-feedback">
                <!-- <ion-icon name="lock-closed-outline"></ion-icon> -->
                <input type="password" class="form-control" name="password" required>
                <label>Password</label>
            </div>
            <div class="sign-in-btn">
                <button type="submit"  name="login">Sign In</button>
            </div>
            <div class="user-login-btn">
                <p>Click here for <a href="http://localhost/onlinepayrollphp/employee/"> Employee Login</a>.</p>    
            </div>
            <div class="time-in-out-btn">
                <p>Click here for <a href="http://localhost/onlinepayrollphp/">Daily Login</a>.</p> 
            </div>
        </form>
    </div>
    <?php
        if(isset($_SESSION['error'])){
            echo "
                <div class='callout callout-danger text-center mt20'>
                    <p>".$_SESSION['error']."</p> 
                </div>
            ";
            unset($_SESSION['error']);
        }
    ?>
</div>


<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
<?php include 'includes/scripts.php' ?>


</body>
</html>


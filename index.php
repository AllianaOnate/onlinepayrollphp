<?php session_start(); ?>


<!DOCTYPE html>
<html>
<head>
<title>Daily Login</title>
  <style type="text/css">
    * {
      margin: 0;
      padding: 0;
    }


    .login-box {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        width: 100%;
        background: url('./images/login_bg.png');
        background-position: center;
        background-size: cover;
    }


    .login-box-body {
        margin-left: 300px;
        position: relative;
        width: 400px;
        height: 450px;
        background: #c19adf;
        border: 2px solid #fff;
        border-radius: 20px;
        backdrop-filter: blur(15px);
        display: flex;
        justify-content: center;
        align-items: center;
    }


    h4 {
            font-size: 2em;
            padding: 30px;
            color: #fff;
            text-align: center;
        }


    .form-group {
            position: relative;
            margin: 30px 0;
            width: 310px;
            border-bottom: 2px solid #fff;
        }


        .form-group label {
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
        input:valid ~ label {
            top: -5px;
        }


    .form-group input {
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


    .form-group ion-icon {
            position: absolute;
            right: 8px;
            color: #fff;
            font-size: 1.2em;
            top: 20px;
        }


    .admin-login-btn {
            padding-left: 10px;
            padding-top: 20px;
            color: #fff;
        }


        .employee-login-btn {
            padding-left: 10px;
            color: #fff;
        }


    .form-group1 select {
      background: transparent;
      width: 100%;
      color: white;
      display: flex;
      align-items: center;
      border: 2px #fff solid;
      border-radius: 0.5em;
      padding: 1em;
      cursor: pointer;
    }


    .form-control select:focus {
      outline: none;
    }


    .form-control option {
      color: black;
    }
    
  </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <p id="date"></p>
      <p id="time" class="bold"></p>
    </div>
  
    <div class="login-box-body">
        <form id="attendance">
          <div class="form-group1">
            <h4>Daily Login</h4>
            <select class="form-control" name="status">
              <option value="in">Time In</option>
              <option value="out">Time Out</option>
            </select>
          </div>
            <div class="form-group has-feedback">
                <input type="text" class="form-control" id="employee" name="employee" required>
            <label>Employee ID</label>
            <ion-icon name="today-outline"></ion-icon>
            </div>


          <div class="row">
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat" name="signin"><i class="fa fa-sign-in"></i> Sign In</button>
                </div>
            </div>


          <div class="admin-login-btn">
                    <p>Click here for <a href="http://localhost/onlinepayrollphp/admin">Admin Login</a>.</p>    
                </div>
          <div class="employee-login-btn">
            <p>Click here for <a href="http://localhost/onlinepayrollphp/employee/">Employee Login</a>.</p>  
          </div>


        </form>
    </div>
        <div class="alert alert-success alert-dismissible mt20 text-center" style="display:none;">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <span class="result"><i class="icon fa fa-check"></i> <span class="message"></span></span>
    </div>
        <div class="alert alert-danger alert-dismissible mt20 text-center" style="display:none;">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <span class="result"><i class="icon fa fa-warning"></i> <span class="message"></span></span>
    </div>  
</div>


<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
<?php include 'scripts.php' ?>
<script type="text/javascript">
$(function() {
  var interval = setInterval(function() {
    var momentNow = moment();
  }, 100);


  $('#attendance').submit(function(e){
    e.preventDefault();
    var attendance = $(this).serialize();
    $.ajax({
      type: 'POST',
      url: 'attendance.php',
      data: attendance,
      dataType: 'json',
      success: function(response){
        if(response.error){
          $('.alert').hide();
          $('.alert-danger').show();
          $('.message').html(response.message);
        }
        else{
          $('.alert').hide();
          $('.alert-success').show();
          $('.message').html(response.message);
          $('#employee').val('');
        }
      }
    });
  });
});
</script>
</body>
</html>


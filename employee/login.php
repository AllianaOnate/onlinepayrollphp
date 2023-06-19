<?php
// login.php
session_start();
include 'includes/conn.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM employees WHERE username = '$username'";
    $query = $conn->query($sql);

    if ($query->num_rows < 1) {
        $_SESSION['error'] = 'Cannot find an account with the username';
    } else {
        $row = $query->fetch_assoc();
        if ($row['status'] == '1') {
            $_SESSION['error'] = 'Your account is deactivated. Please contact the admin.';
        } else if (password_verify($password, $row['password'])) {
            $_SESSION['employees'] = $row['id'];
        } else {
            if (isset($_SESSION['login_attempts'])) {
                $_SESSION['login_attempts']++;
            } else {
                $_SESSION['login_attempts'] = 1;
            }

            if ($_SESSION['login_attempts'] >= 3) {
                $sql_update = "UPDATE employees SET status = '1' WHERE username = '$username'";
                $conn->query($sql_update);
                $_SESSION['error'] = 'Incorrect password. Your account has been deactivated after 3 unsuccessful login attempts. Please contact the admin.';
            } else {
                $_SESSION['error'] = 'Incorrect password. Please try again.';
            }
        }
    }
} else {
    $_SESSION['error'] = 'Input employee credentials first';
    header('location: login.php');
    exit();
}

header('location: employee.php');
exit();

?>

<?php
session_start();
include 'database/connection.php';

if (isset($_POST['login-btn'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM tbl_teachers WHERE email = ?");
    $stmt->execute([$email]);
    $teacher = $stmt->fetch();

    if ($teacher) {
        if ($password === $teacher['password']) { // Replace with password_verify() if storing hashed passwords
            $_SESSION['teacher_id'] = $teacher['id'];
            $_SESSION['teacher_name'] = $teacher['name'];
            header("Location: teacher/students.php");
            exit();
        }
    }
    echo "<script>alert('Invalid email or password!');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>

<body>
    <h1>Login Form</h1>
    <form action="" method="POST">
        <label>Email: </label>
        <input type="email" name="email" required><br>
        <label>Password: </label>
        <input type="password" name="password" required><br>
        <button type="submit" name="login-btn">Login</button>
    </form>
</body>

</html>
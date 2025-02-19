<?php
session_start();
include 'database/connection.php';

if (isset($_POST['login-btn'])) {
    $identifier = $_POST['identifier']; // Email or Student ID
    $password = $_POST['password'];

    // Check if input is an email
    if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
        // Check if user is a teacher
        $stmt = $conn->prepare("SELECT * FROM tbl_teachers WHERE email = ?");
        $stmt->execute([$identifier]);
        $teacher = $stmt->fetch();

        if ($teacher && $password === $teacher['password']) {
            $_SESSION['teacher_id'] = $teacher['id'];
            $_SESSION['teacher_name'] = $teacher['name'];
            header("Location: teacher/students.php");
            exit();
        }

        // Check if user is a student (using email)
        $stmt = $conn->prepare("SELECT * FROM tbl_students WHERE email = ?");
        $stmt->execute([$identifier]);
        $student = $stmt->fetch();

        if ($student && $password === $student['password']) {
            $_SESSION['student_id'] = $student['student_id'];
            $_SESSION['student_name'] = $student['name'];
            header("Location: mygrades.php");
            exit();
        }
    } elseif (ctype_digit($identifier)) {
        // If input is a numeric student ID, check students
        $stmt = $conn->prepare("SELECT * FROM tbl_students WHERE student_id = ?");
        $stmt->execute([$identifier]);
        $student = $stmt->fetch();

        if ($student && $password === $student['password']) {
            $_SESSION['student_id'] = $student['student_id'];
            $_SESSION['student_name'] = $student['name'];
            header("Location: mygrades.php");
            exit();
        }
    }

    echo "<script>alert('Invalid email or student ID or password!');</script>";
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex align-items-center vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg p-4">
                    <h2 class="text-center mb-4">Login</h2>
                    <form id="loginForm" action="" method="POST" novalidate>
                        <div class="mb-3">
                            <label for="identifier" class="form-label">Email or Student ID</label>
                            <input type="text" class="form-control" name="identifier" id="identifier" placeholder="Enter your email or student ID" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" id="password" placeholder="Enter your password" required>
                        </div>
                        <button type="submit" name="login-btn" class="btn btn-primary w-100">Login</button>
                        <a href="register.php">Register here</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- jQuery Validation Plugin -->
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#loginForm").validate({
                rules: {
                    identifier: {
                        required: true
                    },
                    password: {
                        required: true
                    }
                },
                messages: {
                    identifier: {
                        required: "Please enter your email or student ID"
                    },
                    password: {
                        required: "Please enter your password"
                    }
                },
                errorElement: 'div',
                errorClass: 'invalid-feedback',
                highlight: function(element) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid');
                },
                errorPlacement: function(error, element) {
                    error.insertAfter(element);
                }
            });
        });
    </script>
</body>

</html>
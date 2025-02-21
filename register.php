<?php
session_start();
include 'database/connection.php';

// Fetch sections where adviser_id IS NULL (unassigned sections)
$stmt = $conn->prepare("SELECT id, name, section, strand FROM tbl_sections WHERE adviser_id IS NULL");
$stmt->execute();
$sections = $stmt->fetchAll();

if (isset($_POST['register-btn'])) {
    $name = $_POST['name']; // Added name input
    $email = $_POST['email'];
    $password = $_POST['password']; // Not hashed (for now)

    // Insert teacher into tbl_teachers
    $stmt = $conn->prepare("INSERT INTO tbl_teachers (name, email, password, created_at) VALUES (?, ?, ?, NOW())");
    if ($stmt->execute([$name, $email, $password])) {
        $teacher_id = $conn->lastInsertId();

        // Assign teacher as the adviser of the selected section
        $updateSection = $conn->prepare("UPDATE tbl_sections SET adviser_id = ? WHERE id = ?");
        $updateSection->execute([$teacher_id, $_POST['section_id']]);

        echo "<div class='alert alert-success'>Registration successful! <a href='index.php'>Login here</a></div>";
    } else {
        echo "<div class='alert alert-danger'>Registration failed. Try again.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex align-items-center vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg p-4">
                    <h2 class="text-center mb-4">CLASS ADVISER REGISTRATION</h2>
                    <form id="registerForm" action="" method="POST" novalidate>
                        <div class="mb-3">
                            <label for="name" class="form-label">Fullname</label>
                            <input type="text" name="name" class="form-control" placeholder="Enter your full name" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="Enter your email address" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                        </div>

                        <div class="mb-3">
                            <label for="section_id" class="form-label">Advising Class</label>
                            <select name="section_id" class="form-select" required>
                                <option value="">-- Choose Advising Class --</option>
                                <?php foreach ($sections as $section): ?>
                                    <option value="<?= $section['id']; ?>">
                                        <?= $section['name'] . " - " . $section['section'] . " (" . $section['strand'] . ")"; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <button type="submit" name="register-btn" class="btn btn-primary w-100">Register</button>
                        <a href="index.php" style="text-decoration: none;">Already have an account? Login here</a>
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
            $("#registerForm").validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 3
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        required: true,
                        minlength: 6
                    },
                    section_id: {
                        required: true
                    }
                },
                messages: {
                    name: {
                        required: "Please enter your full name",
                        minlength: "Your name must be at least 3 characters long"
                    },
                    email: {
                        required: "Please enter your email address",
                        email: "Please enter a valid email address"
                    },
                    password: {
                        required: "Please enter your password",
                        minlength: "Your password must be at least 6 characters long"
                    },
                    section_id: {
                        required: "Please select an advising class"
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
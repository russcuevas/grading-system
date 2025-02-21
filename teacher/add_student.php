<?php
session_start();
include '../database/connection.php';

if (!isset($_SESSION['teacher_id'])) {
    header("Location: ../index.php");
    exit();
}

$teacher_id = $_SESSION['teacher_id'];

// Fetch the section assigned to the teacher
$stmt = $conn->prepare("SELECT id, name, section, strand FROM tbl_sections WHERE adviser_id = ?");
$stmt->execute([$teacher_id]);
$section = $stmt->fetch();

if (!$section) {
    echo "<script>alert('You are not assigned to any section.'); window.location.href='dashboard.php';</script>";
    exit();
}

$section_id = $section['id']; // Automatically assign this section_id

// Function to generate a unique Student ID
function generateStudentID($conn)
{
    do {
        $student_id = rand(100000, 999999); // Example: STU123456
        $checkStmt = $conn->prepare("SELECT * FROM tbl_students WHERE student_id = ?");
        $checkStmt->execute([$student_id]);
    } while ($checkStmt->rowCount() > 0); // Keep generating until we find a unique ID
    return $student_id;
}

// Generate a random student ID
$student_id = generateStudentID($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $password = $_POST['password']; // ⚠️ You should hash passwords for security!

    // Insert student with the automatically generated student_id
    $insertStmt = $conn->prepare("INSERT INTO tbl_students (student_id, password, name, section_id) VALUES (?, ?, ?, ?)");
    if ($insertStmt->execute([$student_id, $password, $name, $section_id])) {
        $success = "Student successfully added!";
        // Generate a new Student ID for the next entry
        $student_id = generateStudentID($conn);
    } else {
        $error = "Failed to add student.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="card shadow p-4" style="width: 400px;">
        <h3 class="text-center">Add Student</h3>

        <?php if (isset($success)) : ?>
            <div class="alert alert-success"><?= $success; ?></div>
        <?php elseif (isset($error)) : ?>
            <div class="alert alert-danger"><?= $error; ?></div>
        <?php endif; ?>

        <form action="add_student.php" method="POST">
            <input type="hidden" name="section_id" value="<?= $section_id; ?>">

            <div class="mb-3">
                <label class="form-label">Class</label>
                <input type="text" class="form-control" value="<?= $section['name']; ?> - <?= $section['section']; ?> (<?= $section['strand']; ?>)" disabled>
            </div>

            <div class="mb-3">
                <label for="student_id" class="form-label">Student ID</label>
                <input type="text" name="student_id" id="student_id" class="form-control" value="<?= htmlspecialchars($student_id); ?>" readonly disabled>
            </div>

            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Add Student</button>
            <a href="students.php" class="btn btn-secondary w-100 mt-2">Back to List</a>
        </form>
    </div>
</body>

</html>
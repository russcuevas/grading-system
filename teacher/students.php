<?php
session_start();
include '../database/connection.php';

if (!isset($_SESSION['teacher_id'])) {
    header("Location: ../index.php");
    exit();
}

$teacher_id = $_SESSION['teacher_id'];

$stmt = $conn->prepare("SELECT id, name, section, strand FROM tbl_sections WHERE adviser_id = ?");
$stmt->execute([$teacher_id]);
$section = $stmt->fetch();

if (!$section) {
    echo "You are not assigned to any section.";
    exit();
}

$section_id = $section['id'];

// Get students in the assigned section
$stmt = $conn->prepare("SELECT student_id, name FROM tbl_students WHERE section_id = ?");
$stmt->execute([$section_id]);
$students = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students</title>
</head>

<body>
    <h1>Welcome, <?= $_SESSION['teacher_name']; ?></h1>
    <h2>Section: <?= $section['name']; ?> - <?= $section['section'] ?> (<?= $section['strand']; ?>)</h2>

    <h3>Students</h3>
    <table border="1">
        <tr>
            <th>Student ID</th>
            <th>Name</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($students as $student) : ?>
            <tr>
                <td><?= $student['student_id']; ?></td>
                <td><?= $student['name']; ?></td>
                <td><a href="add_grades.php?student_id=<?= $student['student_id']; ?>">Add Grades</a></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <a href="logout.php">Logout</a>
</body>

</html>
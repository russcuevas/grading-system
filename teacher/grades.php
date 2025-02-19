<?php
session_start();
include '../database/connection.php';

if (!isset($_SESSION['teacher_id'])) {
    header("Location: ../index.php");
    exit();
}

$teacher_id = $_SESSION['teacher_id'];

// Get the teacher's assigned section
$stmt = $conn->prepare("SELECT id, name, section, strand FROM tbl_sections WHERE adviser_id = ?");
$stmt->execute([$teacher_id]);
$section = $stmt->fetch();

if (!$section) {
    echo "You are not assigned to any section.";
    exit();
}

$section_id = $section['id'];
$strand = $section['strand'];

// Determine selected semester
$semester = isset($_GET['semester']) ? $_GET['semester'] : '1st semester';

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
    <title>Semester Grades</title>
    <script>
        function updateSemester() {
            const semester = document.getElementById('semester').value;
            window.location.href = "grades.php?semester=" + semester;
        }
    </script>
</head>

<body>
    <h1>Semester Grades</h1>
    <h2>Section: <?= $section['name']; ?> - <?= $section['section']; ?> (<?= $section['strand']; ?>)</h2>

    <label for="semester">Select Semester:</label>
    <select id="semester" name="semester" onchange="updateSemester()">
        <option value="1st semester" <?= ($semester == '1st semester') ? 'selected' : ''; ?>>1st Semester</option>
        <option value="2nd semester" <?= ($semester == '2nd semester') ? 'selected' : ''; ?>>2nd Semester</option>
    </select>

    <br><br>

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
                <td><a href="view_grades.php?student_id=<?= $student['student_id']; ?>&semester=<?= $semester; ?>">View Grades</a></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <a href="students.php">Back to Students</a>
</body>

</html>
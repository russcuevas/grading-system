<?php
session_start();
include '../database/connection.php';

if (!isset($_SESSION['teacher_id'])) {
    header("Location: ../index.php");
    exit();
}

$teacher_id = $_SESSION['teacher_id'];
$student_id = $_GET['student_id'] ?? null;
$semester = $_GET['semester'] ?? '1st semester'; // Default to 1st semester

if (!$student_id) {
    echo "Invalid student ID.";
    exit();
}

// Get student details
$stmt = $conn->prepare("SELECT id, name, section_id FROM tbl_students WHERE student_id = ?");
$stmt->execute([$student_id]);
$student = $stmt->fetch();

if (!$student) {
    echo "Student not found.";
    exit();
}

$student_id_db = $student['id'];
$name = $student['name'];
$section_id = $student['section_id'];

// Get section details
$stmt = $conn->prepare("SELECT strand, section FROM tbl_sections WHERE id = ?");
$stmt->execute([$section_id]);
$section = $stmt->fetch();

$strand = $section['strand'];
$section_name = $section['section'];

// Fetch grades based on the selected semester
$stmt = $conn->prepare("
    SELECT s.name AS subject_name, g.final_grade 
    FROM tbl_grades g 
    JOIN tbl_subjects s ON g.subject_id = s.id 
    WHERE g.student_id = ? AND g.semester = ?
");
$stmt->execute([$student_id_db, $semester]);
$grades = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View <?= $semester; ?> Grades</title>
</head>

<body>
    <h1><?= $semester; ?> Grades</h1>
    <h2>Name: <?= $name; ?></h2>
    <h3>Section: <?= $section_name; ?> (<?= $strand; ?>)</h3>

    <table border="1">
        <tr>
            <th>Subject</th>
            <th>Final Grade</th>
        </tr>
        <?php if ($grades): ?>
            <?php foreach ($grades as $grade): ?>
                <tr>
                    <td><?= $grade['subject_name']; ?></td>
                    <td><?= $grade['final_grade']; ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="2">
                    No grades recorded for this semester.
                    <a href="add_grades.php?student_id=<?= $student_id; ?>&semester=<?= $semester; ?>">Add grades</a>
                </td>
            </tr>
        <?php endif; ?>
    </table>

    <a href="grades.php?semester=<?= $semester; ?>">Back to List</a>
</body>

</html>
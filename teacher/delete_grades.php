<?php
session_start();
include '../database/connection.php';

if (!isset($_SESSION['teacher_id'])) {
    header("Location: ../index.php");
    exit();
}

// Validate input
if (!isset($_GET['student_id']) || !isset($_GET['semester'])) {
    header("Location: grades.php?error=Invalid request.");
    exit();
}

$student_id = $_GET['student_id'];
$semester = $_GET['semester'];

// Validate semester input
$allowed_semesters = ['1st semester', '2nd semester'];
if (!in_array($semester, $allowed_semesters)) {
    header("Location: grades.php?error=Invalid semester.");
    exit();
}

// Get the internal student ID from tbl_students
$stmt = $conn->prepare("SELECT id FROM tbl_students WHERE student_id = ?");
$stmt->execute([$student_id]);
$student = $stmt->fetch();

if (!$student) {
    header("Location: grades.php?semester=$semester&error=Student not found.");
    exit();
}

$student_id_db = $student['id'];

// Check if grades exist before deleting
$checkStmt = $conn->prepare("SELECT * FROM tbl_grades WHERE student_id = ? AND semester = ?");
$checkStmt->execute([$student_id_db, $semester]);
$grades = $checkStmt->fetchAll();

if (!$grades) {
    header("Location: grades.php?semester=$semester&error=No grades found for deletion.");
    exit();
}

// Delete grades
try {
    $deleteStmt = $conn->prepare("DELETE FROM tbl_grades WHERE student_id = ? AND semester = ?");
    $deleteStmt->execute([$student_id_db, $semester]);

    if ($deleteStmt->rowCount() > 0) {
        header("Location: grades.php?semester=$semester&success=Grades successfully deleted.");
    } else {
        header("Location: grades.php?semester=$semester&error=No grades found for deletion.");
    }
} catch (PDOException $e) {
    header("Location: grades.php?semester=$semester&error=Failed to delete grades: " . $e->getMessage());
}

exit();

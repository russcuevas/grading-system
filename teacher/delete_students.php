<?php
session_start();
include '../database/connection.php';

if (!isset($_SESSION['teacher_id'])) {
    header("Location: ../index.php");
    exit();
}

if (!isset($_GET['student_id'])) {
    header("Location: students.php?error=No student selected.");
    exit();
}

$student_id = $_GET['student_id'];
$checkStmt = $conn->prepare("SELECT * FROM tbl_students WHERE student_id = ?");
$checkStmt->execute([$student_id]);
$student = $checkStmt->fetch();

if (!$student) {
    header("Location: students.php?error=Student not found.");
    exit();
}

// Delete student
$deleteStmt = $conn->prepare("DELETE FROM tbl_students WHERE student_id = ?");
if ($deleteStmt->execute([$student_id])) {
    header("Location: students.php?success=Student successfully deleted.");
} else {
    header("Location: students.php?error=Failed to delete student.");
}
exit();

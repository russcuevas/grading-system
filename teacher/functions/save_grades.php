<?php
session_start();
include '../../database/connection.php';

if (!isset($_POST['student_id'])) {
    echo "<script>alert('Invalid submission.'); window.location.href='../grades.php';</script>";
    exit();
}

$student_number = $_POST['student_id'];
$semester = $_POST['semester'];
$file = $_FILES['excel_file'] ?? null;

// Get the student ID from the database
$stmt = $conn->prepare("SELECT id FROM tbl_students WHERE student_id = ?");
$stmt->execute([$student_number]);
$student = $stmt->fetch();

if (!$student) {
    echo "<script>alert('Student ID not found.'); window.location.href='../grades.php';</script>";
    exit();
}

$student_id = $student['id'];

// Check if a grade record already exists for the student and semester
$stmt = $conn->prepare("SELECT excel_file FROM tbl_grades WHERE student_id = ? AND semester = ?");
$stmt->execute([$student_id, $semester]);
$grade_record = $stmt->fetch();

$existing_file = $grade_record ? $grade_record['excel_file'] : null;
$new_filename = $existing_file;

if ($file && $file['error'] === UPLOAD_ERR_OK) {
    $uploadDir = '../../grades/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $new_filename = time() . "_" . basename($file["name"]);
    $filepath = $uploadDir . $new_filename;

    if (!move_uploaded_file($file["tmp_name"], $filepath)) {
        echo "<script>alert('Failed to save file. Please try again.'); window.location.href='../grades.php';</script>";
        exit();
    }
}

// Insert or update the database record
if ($grade_record) {
    // Update existing record
    $stmt = $conn->prepare("UPDATE tbl_grades SET excel_file = ? WHERE student_id = ? AND semester = ?");
    $stmt->execute([$new_filename, $student_id, $semester]);
} else {
    // Insert new record
    $stmt = $conn->prepare("INSERT INTO tbl_grades (student_id, excel_file, semester) VALUES (?, ?, ?)");
    $stmt->execute([$student_id, $new_filename, $semester]);
}

echo "<script>alert('Grades updated successfully!'); window.location.href='../grades.php';</script>";

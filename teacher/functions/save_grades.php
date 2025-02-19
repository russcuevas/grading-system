<?php
session_start();
include '../../database/connection.php';

header('Content-Type: application/json');

if (!isset($_SESSION['teacher_id'])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized access."]);
    exit();
}

$teacher_id = $_SESSION['teacher_id'];
$student_id = $_POST['student_id'] ?? null;
$semester = $_POST['semester'] ?? '1st semester';

if (!$student_id) {
    echo json_encode(["status" => "error", "message" => "Invalid student ID."]);
    exit();
}
$stmt = $conn->prepare("SELECT id FROM tbl_students WHERE student_id = ?");
$stmt->execute([$student_id]);
$student = $stmt->fetch();

if (!$student) {
    echo json_encode(["status" => "error", "message" => "Student not found."]);
    exit();
}

$student_id_db = $student['id'];
$stmt = $conn->prepare("SELECT subject_id FROM tbl_grades WHERE student_id = ? AND semester = ?");
$stmt->execute([$student_id_db, $semester]);
$existing_grades = $stmt->fetchAll(PDO::FETCH_COLUMN);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['final_grades'])) {
    $final_grades = $_POST['final_grades'];

    foreach ($final_grades as $subject_id => $final_grade) {
        if (in_array($subject_id, $existing_grades)) {
            $stmt = $conn->prepare("UPDATE tbl_grades SET final_grade = ? WHERE student_id = ? AND subject_id = ? AND semester = ?");
            $stmt->execute([$final_grade, $student_id_db, $subject_id, $semester]);
        } else {
            $stmt = $conn->prepare("INSERT INTO tbl_grades (student_id, subject_id, semester, final_grade) 
                                    VALUES (?, ?, ?, ?)");
            $stmt->execute([$student_id_db, $subject_id, $semester, $final_grade]);
        }
    }

    echo json_encode(["status" => "success", "message" => "Grades successfully saved."]);
    exit();
}

echo json_encode(["status" => "error", "message" => "Invalid request."]);
exit();

<?php
session_start();
include '../../database/connection.php';

if (!isset($_SESSION['teacher_id'])) {
    header("Location: ../../index.php");
    exit();
}

$student_id = $_GET['student_id'] ?? null;
$semester = $_GET['semester'] ?? '1st semester';

if (!$student_id) {
    echo "Invalid student ID.";
    exit();
}

// Get student details
$stmt = $conn->prepare("SELECT id, name, student_id, section_id FROM tbl_students WHERE student_id = ?");
$stmt->execute([$student_id]);
$student = $stmt->fetch();

if (!$student) {
    echo "Student not found.";
    exit();
}

$student_id_db = $student['id'];
$name = $student['name'];
$student_number = $student['student_id'];
$section_id = $student['section_id'];

// Get section details
$stmt = $conn->prepare("SELECT strand, section, name FROM tbl_sections WHERE id = ?");
$stmt->execute([$section_id]);
$section = $stmt->fetch();

$strand = $section['strand'];
$section_name = $section['section'];
$grade_level = $section['name'];

// Fetch grades
$stmt = $conn->prepare("SELECT s.name AS subject_name, g.final_grade FROM tbl_grades g 
                        JOIN tbl_subjects s ON g.subject_id = s.id 
                        WHERE g.student_id = ? AND g.semester = ?");
$stmt->execute([$student_id_db, $semester]);
$grades = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Copy of Grades</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print {
                display: none;
            }
        }

        .grade-header {
            text-align: left;
            margin-bottom: 20px;
        }

        .grade-info {
            display: flex;
            justify-content: space-between;
            font-size: 18px;
            font-weight: bold;
        }

        .table th,
        .table td {
            text-align: left;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <h2 class="grade-header">Student Copy of Grades</h2>

        <div class="grade-info">
            <span>Name: <?= $name; ?></span>
            <span>Student No: <?= $student_number; ?></span>
        </div>

        <div class="grade-info">
            <span>Year: <?= $grade_level; ?> - <?= $section_name; ?></span>
            <span>Strand: <?= $strand; ?></span>
        </div>

        <h4 class="text-left mt-3"><?= $semester; ?> Semester</h4>

        <table class="table table-bordered mt-3">
            <thead class="table-dark">
                <tr>
                    <th>Subject</th>
                    <th>Final Grade</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($grades): ?>
                    <?php foreach ($grades as $grade): ?>
                        <tr>
                            <td><?= $grade['subject_name']; ?></td>
                            <td class="grade"><?= $grade['final_grade']; ?></td> <!-- Added class="grade" -->
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="2" class="text-center">No grades recorded for this semester.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th></th>
                    <td><label id="gwaLabel" class="fw-bold">GWA: 0.00</label></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <script>
        function calculateGWA() {
            let grades = document.querySelectorAll(".grade");
            let total = 0;
            let count = 0;

            grades.forEach(grade => {
                let value = parseFloat(grade.textContent);
                if (!isNaN(value)) {
                    total += value;
                    count++;
                }
            });

            let gwa = count > 0 ? (total / count).toFixed(2) : "0.00";
            document.getElementById("gwaLabel").textContent = `GWA: ${gwa}`;
        }

        window.onload = function() {
            calculateGWA(); // Ensure GWA is calculated before printing
            window.print();
            window.onafterprint = function() {
                window.close();
            };
        };
    </script>

</body>

</html>
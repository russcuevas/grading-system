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
$stmt = $conn->prepare("SELECT s.name AS subject_name, g.final_grade FROM tbl_grades g JOIN tbl_subjects s ON g.subject_id = s.id WHERE g.student_id = ? AND g.semester = ?");
$stmt->execute([$student_id_db, $semester]);
$grades = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View <?= $semester; ?> Grades</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            display: flex;
            height: 100vh;
        }

        .sidebar {
            width: 250px;
            min-height: 100vh;
            background-color: #212529;
            color: white;
            padding: 20px;
            position: fixed;
            left: 0;
            top: 0;
            transition: left 0.3s;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 12px;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .sidebar a:hover {
            background-color: #495057;
        }

        .content {
            margin-left: 260px;
            flex-grow: 1;
            padding: 20px;
            transition: margin-left 0.3s;
        }

        .sidebar-toggler {
            display: none;
            position: absolute;
            top: 15px;
            right: 15px;
        }

        @media (max-width: 768px) {
            .sidebar {
                left: -100%;
                position: fixed;
                z-index: 1000;
            }

            .content {
                margin-left: 0;
                width: 100%;
            }

            .sidebar-toggler {
                display: block;
            }
        }
    </style>
</head>

<body>
    <div class="sidebar" id="sidebar">
        <button class="sidebar-toggler btn btn-light" onclick="toggleSidebar()">&#9776;</button>
        <div class="text-center mb-4">
            <img src="../images/logo.jpg" style="width: 100px;" alt="Logo" class="img-fluid rounded-circle">
            <h4 class="mt-3">Dashboard</h4>
        </div>
        <hr>
        <a href="students.php">Student List</a>
        <a href="grades.php">Grades List</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="content">
        <button class="btn btn-dark d-md-none" onclick="toggleSidebar()">&#9776; Menu</button>
        <h1><?= $semester; ?> Grades</h1>
        <h2>Name: <?= $name; ?></h2>
        <h3>Section: <?= $section_name; ?> (<?= $strand; ?>)</h3>
        <a href="javascript:void(0);" onclick="openPrintWindow('<?= $student_id; ?>', '<?= urlencode($semester); ?>');" class="btn btn-primary">Print</a>
        <div class="mt-4">
            <table class="table table-bordered" id="gradesTable">
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
                                <td class="grade"><?= $grade['final_grade']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="2" class="text-center">
                                No grades recorded for this semester.<br>
                                <a href="add_grades.php?student_id=<?= $student_id; ?>&semester=<?= $semester; ?>" class="btn btn-primary mt-2">Add Grades</a>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th></th>
                        <td> <label id="gwaLabel" class="fw-bold">GWA: 0.00</label>
                            <br>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <a href="delete_grades.php?student_id=<?= $student_id; ?>&semester=<?= urlencode($semester); ?>" class="btn btn-danger">Delete Grades</a>
        <a href="grades.php?semester=<?= $semester; ?>" class="btn btn-secondary">Back to List</a>
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

        window.onload = calculateGWA;
    </script>



    <script>
        function openPrintWindow(studentId, semester) {
            let printWindow = window.open(`print/print_grades.php?student_id=${studentId}&semester=${semester}`, '_blank');
            printWindow.onload = function() {
                printWindow.print();
                printWindow.onafterprint = function() {
                    printWindow.close();
                };
            };
        }

        function toggleSidebar() {
            let sidebar = document.getElementById('sidebar');
            sidebar.style.left = sidebar.style.left === "0px" ? "-100%" : "0px";
        }
    </script>
</body>

</html>
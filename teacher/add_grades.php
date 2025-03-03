<?php
session_start();
include '../database/connection.php';

if (!isset($_SESSION['teacher_id'])) {
    header("Location: ../index.php");
    exit();
}

$teacher_id = $_SESSION['teacher_id'];
$student_id = $_GET['student_id'] ?? null;
$semester = $_GET['semester'] ?? '1st semester';

if (!$student_id) {
    echo "Invalid student ID.";
    exit();
}

// Fetch student details
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

// Fetch section details, ensuring the teacher is assigned as the adviser
$stmt = $conn->prepare("SELECT name, strand, section FROM tbl_sections WHERE id = ? AND adviser_id = ?");
$stmt->execute([$section_id, $teacher_id]);
$section = $stmt->fetch();

if (!$section) {
    echo "You are not authorized to manage this student's grades.";
    exit();
}

$grade_level = $section['name'];
$strand = $section['strand'];
$section_name = $section['section'];

// Fetch subjects only if they match the teacher's assigned section
$stmt = $conn->prepare("SELECT id, name FROM tbl_subjects WHERE grade_level = ? AND strand = ? AND semester = ?");
$stmt->execute([$grade_level, $strand, $semester]);
$subjects = $stmt->fetchAll();

// Check if grades file exists for the student and semester
$stmt = $conn->prepare("SELECT excel_file FROM tbl_grades WHERE student_id = ? AND semester = ?");
$stmt->execute([$student_id_db, $semester]);
$grade_record = $stmt->fetch();
$existing_file = $grade_record ? $grade_record['excel_file'] : null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $existing_file ? "Update Grades" : "Add Grades"; ?></title>
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
            transition: 0.3s;
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
                width: 100%;
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
            <h4 class="mt-3">Student Records</h4>
        </div>
        <hr>
        <a href="students.php">Student List</a>
        <a href="grades.php">Grades List</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="content">
        <button class="btn btn-dark d-md-none" onclick="toggleSidebar()">&#9776; Menu</button>

        <h1><?= $existing_file ? "Update Grades" : "Add Grades"; ?> for Student ID: <?= $student_id; ?></h1>
        <h3>Name: <?= $name; ?></h3>
        <h4>Strand: <?= $strand; ?></h4>
        <h4>Section: <?= $section_name; ?></h4>

        <label for="semester">Select Semester:</label>
        <select name="semester" id="semester" class="form-select w-auto" onchange="updateSemester()">
            <option value="1st semester" <?= ($semester == '1st semester') ? 'selected' : ''; ?>>1st Semester</option>
            <option value="2nd semester" <?= ($semester == '2nd semester') ? 'selected' : ''; ?>>2nd Semester</option>
        </select>

        <br>
        <form id="grades-form" method="POST" action="functions/save_grades.php" enctype="multipart/form-data">
            <input type="hidden" name="student_id" value="<?= $student_id; ?>">
            <input type="hidden" name="semester" id="selected-semester" value="<?= $semester; ?>">

            <div style="padding: 20px; border: 2px solid black;">
                <label for="excel_file" class="form-label" style="font-weight: 900;">UPLOAD EXCEL FILE FOR GRADES</label>
                <input type="file" name="excel_file" id="excel_file" class="form-control" accept=".xls,.xlsx" <?= $existing_file ? "" : "required"; ?>>

                <?php if ($existing_file): ?>
                    <p>Current File: <a href="../grades/<?= $existing_file; ?>" target="_blank"><?= $existing_file; ?></a></p>
                <?php endif; ?>
            </div>

            <br>
            <button type="submit" class="btn btn-primary"><?= $existing_file ? "Update Grades" : "Upload & Save Grades"; ?></button>
            <a href="students.php" class="btn btn-secondary">Back to Students</a>
        </form>

    </div>

    <script>
        function toggleSidebar() {
            let sidebar = document.getElementById('sidebar');
            sidebar.style.left = sidebar.style.left === "0px" ? "-100%" : "0px";
        }

        function updateSemester() {
            let semester = document.getElementById("semester").value;
            let studentId = "<?= $student_id; ?>";
            window.location.href = "add_grades.php?student_id=" + studentId + "&semester=" + encodeURIComponent(semester);
        }
    </script>
</body>

</html>
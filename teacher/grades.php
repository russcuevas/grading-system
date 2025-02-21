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
$semester = isset($_GET['semester']) ? $_GET['semester'] : '1st semester';
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
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
                width: 100%;
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
        <h1>Semester Grades</h1>
        <h2>Class: <?= $section['name']; ?> - <?= $section['section']; ?> (<?= $section['strand']; ?>)</h2>


        <div class="mt-4 p-4 border rounded shadow-sm bg-light">
            <label for="semester" style="color: black !important;">Select Semester:</label>
            <select id="semester" name="semester" class="form-select w-auto" onchange="updateSemester()">
                <option value="1st semester" <?= ($semester == '1st semester') ? 'selected' : ''; ?>>1st Semester</option>
                <option value="2nd semester" <?= ($semester == '2nd semester') ? 'selected' : ''; ?>>2nd Semester</option>
            </select>
            <br>
            <table id="studentsTable" class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Student ID</th>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student) : ?>
                        <tr>
                            <td><?= $student['student_id']; ?></td>
                            <td><?= $student['name']; ?></td>
                            <td><a href="view_grades.php?student_id=<?= $student['student_id']; ?>&semester=<?= $semester; ?>" class="btn btn-primary">View Grades</a>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#studentsTable').DataTable();
        });

        function updateSemester() {
            const semester = document.getElementById('semester').value;
            window.location.href = "grades.php?semester=" + semester;
        }

        function toggleSidebar() {
            let sidebar = document.getElementById('sidebar');
            sidebar.style.left = sidebar.style.left === "0px" ? "-100%" : "0px";
        }
    </script>
</body>

</html>
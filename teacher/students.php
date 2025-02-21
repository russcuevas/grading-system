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

$stmt = $conn->prepare("SELECT student_id, name FROM tbl_students WHERE section_id = ?");
$stmt->execute([$section_id]);
$students = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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
            transition: all 0.3s;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
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
            transition: all 0.3s;
        }

        .sidebar-toggler {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 20px;
            position: absolute;
            top: 15px;
            right: 15px;
            cursor: pointer;
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
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <button class="sidebar-toggler" onclick="toggleSidebar()">&#9776;</button>
        <h4 class="text-center">Dashboard</h4>
        <hr>
        <a href="students.php">Student List</a>
        <a href="grades.php">Grades List</a>
        <a href="logout.php">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="content">
        <button class="btn btn-dark d-md-none" onclick="toggleSidebar()">&#9776; Menu</button>
        <h1>Welcome, <?= $_SESSION['teacher_name']; ?></h1>
        <h2>Class: <?= $section['name']; ?> - <?= $section['section']; ?> (<?= $section['strand']; ?>)</h2>
        <div class="mt-4 p-4 border rounded shadow-sm bg-light">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h3>List of Students</h3>
                <a href="add_student.php" class="btn btn-primary">Add Student +</a>
            </div>

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
                            <td>
                                <a href="add_grades.php?student_id=<?= $student['student_id']; ?>" class="btn btn-primary">Add Grades</a>
                                <a href="delete_students.php?student_id=<?= $student['student_id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this student?');">Delete</a>
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

        function toggleSidebar() {
            let sidebar = document.getElementById('sidebar');
            if (sidebar.style.left === "0px") {
                sidebar.style.left = "-100%";
            } else {
                sidebar.style.left = "0px";
            }
        }
    </script>
</body>

</html>
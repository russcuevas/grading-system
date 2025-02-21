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

$stmt = $conn->prepare("SELECT strand, section FROM tbl_sections WHERE id = ?");
$stmt->execute([$section_id]);
$section = $stmt->fetch();

$strand = $section['strand'];
$section_name = $section['section'];

$stmt = $conn->prepare("SELECT id, name FROM tbl_subjects WHERE strand = ? AND semester = ?");
$stmt->execute([$strand, $semester]);
$subjects = $stmt->fetchAll();

$stmt = $conn->prepare("SELECT subject_id, final_grade FROM tbl_grades WHERE student_id = ? AND semester = ?");
$stmt->execute([$student_id_db, $semester]);
$existing_grades = $stmt->fetchAll();

$grades_map = [];
foreach ($existing_grades as $grade) {
    $grades_map[$grade['subject_id']] = $grade['final_grade'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= empty($grades_map) ? "Add Grades" : "Update Grades"; ?></title>
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
            <h4 class="mt-3">Dashboard</h4>
        </div>
        <hr>
        <a href="students.php">Student List</a>
        <a href="grades.php">Grades List</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="content">
        <button class="btn btn-dark d-md-none" onclick="toggleSidebar()">&#9776; Menu</button>

        <h1><?= empty($grades_map) ? "Add Grades" : "Update Grades"; ?> for Student ID: <?= $student_id; ?></h1>
        <h3>Name: <?= $name; ?></h3>
        <h4>Strand: <?= $strand; ?></h4>
        <h4>Section: <?= $section_name; ?></h4>

        <label for="semester">Select Semester:</label>
        <select name="semester" id="semester" class="form-select w-auto">
            <option value="1st semester" <?= ($semester == '1st semester') ? 'selected' : ''; ?>>1st Semester</option>
            <option value="2nd semester" <?= ($semester == '2nd semester') ? 'selected' : ''; ?>>2nd Semester</option>
        </select>

        <br>

        <form id="grades-form">
            <input type="hidden" name="student_id" value="<?= $student_id; ?>">
            <input type="hidden" id="semester-input" name="semester" value="<?= $semester; ?>">

            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Subject</th>
                        <th>Final Grade</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($subjects as $subject) : ?>
                        <tr>
                            <td><?= $subject['name']; ?></td>
                            <td>
                                <input type="text" name="final_grades[<?= $subject['id']; ?>]"
                                    class="final-grade form-control" required
                                    value="<?= $grades_map[$subject['id']] ?? ''; ?>">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <td><label id="gwa" class="fw-bold">GWA: 0.00</label><br></td>
                    </tr>
                </tfoot>
            </table>

            <br>

            <button type="submit" id="submit-btn" class="btn btn-primary"><?= empty($grades_map) ? "Add Grades" : "Update Grades"; ?></button>
            <a href="students.php" class="btn btn-secondary">Back to Students</a>

        </form>
    </div>

    <script>
        $(document).ready(function() {
            const form = $("#grades-form");
            const gwaLabel = $("#gwa");
            const submitButton = $("#submit-btn");
            const semesterSelect = $("#semester");

            function calculateGWA() {
                let totalGrades = 0;
                let totalSubjects = 0;
                $(".final-grade").each(function() {
                    const grade = parseFloat($(this).val());
                    if (!isNaN(grade)) {
                        totalGrades += grade;
                        totalSubjects++;
                    }
                });

                const gwa = totalSubjects > 0 ? (totalGrades / totalSubjects).toFixed(2) : "0.00";
                gwaLabel.text(`GWA: ${gwa}`);
            }

            $(".final-grade").on("input", calculateGWA);

            form.on("submit", function(e) {
                e.preventDefault();
                submitButton.text("Saving...");
                submitButton.prop("disabled", true);

                $.ajax({
                    url: "functions/save_grades.php",
                    type: "POST",
                    data: form.serialize(),
                    dataType: "json",
                    success: function(response) {
                        alert(response.message);
                        calculateGWA();
                    },
                    error: function(xhr) {
                        console.error("AJAX Error:", xhr.responseText);
                        alert("An error occurred while saving grades.");
                    },
                    complete: function() {
                        submitButton.text("Save Grades");
                        submitButton.prop("disabled", false);
                    }
                });
            });

            semesterSelect.on("change", function() {
                const newSemester = $(this).val();
                window.location.href = `add_grades.php?student_id=<?= $student_id; ?>&semester=` + newSemester;
            });

            calculateGWA();
        });
    </script>

    <script>
        function toggleSidebar() {
            let sidebar = document.getElementById('sidebar');
            sidebar.style.left = sidebar.style.left === "0px" ? "-100%" : "0px";
        }
    </script>
</body>

</html>
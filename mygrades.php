<?php
session_start();
include 'database/connection.php';

if (!isset($_SESSION['student_id'])) {
    header("Location: index.php");
    exit();
}

$student_id = $_SESSION['student_id'];
$semester = trim($_GET['semester'] ?? '1st semester');

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
$stmt = $conn->prepare("SELECT name, strand, section FROM tbl_sections WHERE id = ?");
$stmt->execute([$section_id]);
$section = $stmt->fetch();

$grade_level = $section['name'];
$strand = $section['strand'];
$section_name = $section['section'];

// Fetch subjects, grades, and Excel file
$stmt = $conn->prepare("
    SELECT excel_file, semester 
    FROM tbl_grades 
    WHERE student_id = ? AND semester = ?
");
$stmt->execute([$student_id_db, $semester]);
$grades = $stmt->fetchAll();


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Grades</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-light">
    <div class="container mt-5">
        <h2>Welcome, <?= htmlspecialchars($name); ?></h2>
        <h4><?= htmlspecialchars($grade_level); ?> - <?= htmlspecialchars($section_name); ?></h4>
        <h4>Strand: <?= htmlspecialchars($strand); ?></h4>

        <label for="semester">Select Semester:</label>
        <select id="semester" class="form-select w-25">
            <option value="1st semester" <?= $semester == '1st semester' ? 'selected' : ''; ?>>1st Semester</option>
            <option value="2nd semester" <?= $semester == '2nd semester' ? 'selected' : ''; ?>>2nd Semester</option>
        </select>

        <br>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>MY EXCEL GRADES</th>
                </tr>
            </thead>
            <tbody id="grades-table">
                <?php if (count($grades) > 0): ?>
                    <?php foreach ($grades as $grade): ?>
                        <tr>
                            <td>
                                <?php if (!empty($grade['excel_file'])) : ?>CLICK TO DOWNLOAD GRADES <i class="fa-solid fa-file-excel"></i> -
                                <a style="text-decoration: none;" href="grades/<?= $grade['excel_file']; ?>" target="_blank"><?= $grade['excel_file']; ?></a>
                            <?php else : ?>
                                <span class="text-danger" style="font-weight: 600;">NO FILE</span>
                            <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td class="text-center">No grades available for this semester.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>


        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#semester").change(function() {
                window.location.href = "mygrades.php?semester=" + $(this).val();
            });
        });
    </script>
</body>

</html>
<?php
session_start();
include 'database/connection.php';

if (!isset($_SESSION['student_id'])) {
    header("Location: index.php");
    exit();
}

$student_id = $_SESSION['student_id'];
$semester = trim($_GET['semester'] ?? '1st semester'); // Trim to prevent extra spaces

// Get student details
$stmt = $conn->prepare("SELECT id, name, section_id FROM tbl_students WHERE student_id = ?");
$stmt->execute([$student_id]);
$student = $stmt->fetch();

if (!$student) {
    echo "Student not found.";
    exit();
}

$student_id_db = $student['id']; // This is the actual student ID used in tbl_grades
$name = $student['name'];
$section_id = $student['section_id'];

// Get section details
$stmt = $conn->prepare("SELECT name, strand, section FROM tbl_sections WHERE id = ?");
$stmt->execute([$section_id]);
$section = $stmt->fetch();

$grade_level = $section['name'];
$strand = $section['strand'];
$section_name = $section['section'];

// Fetch subjects and grades
$stmt = $conn->prepare("
    SELECT s.name AS subject_name, COALESCE(g.final_grade, 'N/A') AS final_grade
    FROM tbl_subjects s
    LEFT JOIN tbl_grades g ON s.id = g.subject_id AND g.student_id = ? AND g.semester = ?
    WHERE s.grade_level = ? AND s.strand = ? AND s.semester = ?
");
$stmt->execute([$student_id_db, $semester, $grade_level, $strand, $semester]);
$grades = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Grades</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
                    <th>Subject</th>
                    <th>Final Grade</th>
                </tr>
            </thead>
            <tbody id="grades-table">
                <?php
                $total_grades = 0;
                $count_grades = 0;
                ?>

                <?php if (count($grades) > 0): ?>
                    <?php foreach ($grades as $grade): ?>
                        <tr>
                            <td><?= htmlspecialchars($grade['subject_name']); ?></td>
                            <td>
                                <?= htmlspecialchars($grade['final_grade']); ?>
                                <?php
                                if (is_numeric($grade['final_grade'])) {
                                    $total_grades += $grade['final_grade'];
                                    $count_grades++;
                                }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="2" class="text-center">No grades available for this semester.</td>
                    </tr>
                <?php endif; ?>
            </tbody>

            <tfoot>
                <tr>
                    <th></th>
                    <th>
                        <?php
                        if ($count_grades > 0) {
                            $gwa = round($total_grades / $count_grades, 2);
                            echo "GWA: " . number_format($gwa, 2);
                        } else {
                            echo "GWA: N/A";
                        }
                        ?>
                    </th>
                </tr>
            </tfoot>
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
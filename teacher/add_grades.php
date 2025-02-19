<?php
session_start();
include '../database/connection.php';

if (!isset($_SESSION['teacher_id'])) {
    header("Location: ../index.php");
    exit();
}

$teacher_id = $_SESSION['teacher_id'];
$student_id = $_GET['student_id'];

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

$stmt = $conn->prepare("SELECT strand, section FROM tbl_sections WHERE id = ?");
$stmt->execute([$section_id]);
$section = $stmt->fetch();

$strand = $section['strand'];
$section_name = $section['section'];

$grade_level = '';
switch ($strand) {
        // Grade 11 strands
    case 'HE':
    case 'HUMSS':
    case 'ICT':
    case 'STEM':
    case 'GAS':
    case 'ABM':
    case 'TOURISM':
        $grade_level = 'Grade 11';
        break;

        // Grade 12 strands
    case 'ICT':
    case 'ABM':
    case 'HUMSS':
    case 'HE':
    case 'STEM':
    case 'GAS':
        $grade_level = 'Grade 12';
        break;
    default:
        $grade_level = 'Unknown Grade Level';
}

$semester = $_POST['semester'] ?? '1st semester';

$stmt = $conn->prepare("SELECT id, name FROM tbl_subjects WHERE strand = ? AND semester = ?");
$stmt->execute([$strand, $semester]);
$subjects = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['final_grades'])) {
    $final_grades = $_POST['final_grades'];

    foreach ($final_grades as $subject_id => $final_grade) {
        $stmt = $conn->prepare("INSERT INTO tbl_grades (student_id, subject_id, semester, final_grade) 
                                VALUES (?, ?, ?, ?)");
        $stmt->execute([$student_id_db, $subject_id, $semester, $final_grade]);
    }

    echo "Grades added successfully.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Grades</title>
    <script>
        function calculateGWA() {
            let totalGrades = 0;
            let totalSubjects = 0;
            const gradeInputs = document.querySelectorAll('.final-grade');
            gradeInputs.forEach(input => {
                const grade = parseFloat(input.value);
                if (!isNaN(grade)) {
                    totalGrades += grade;
                    totalSubjects++;
                }
            });
            const gwa = totalSubjects > 0 ? (totalGrades / totalSubjects).toFixed(2) : 0;
            document.getElementById('gwa').textContent = `GWA: ${gwa}`;
        }
    </script>
</head>

<body>
    <h1>Add Grades for Student ID: <?= $student_id; ?></h1>
    <h3>Name: <?= $name; ?></h3>
    <h4>Strand: <?= $strand; ?></h4> <!-- Display student's strand -->
    <h4>Section: <?= $section_name; ?></h4> <!-- Display student's section -->
    <h4>Grade Level: <?= $grade_level; ?></h4> <!-- Display grade level -->

    <form method="POST" action="add_grades.php?student_id=<?= $student_id; ?>">
        <label for="semester">Select Semester:</label>
        <select name="semester" id="semester" onchange="this.form.submit()">
            <option value="1st semester" <?= $semester == '1st semester' ? 'selected' : ''; ?>>1st Semester</option>
            <option value="2nd semester" <?= $semester == '2nd semester' ? 'selected' : ''; ?>>2nd Semester</option>
        </select>
    </form>

    <br>

    <?php if ($semester): ?>
        <form method="POST" action="add_grades.php?student_id=<?= $student_id; ?>" onsubmit="return validateForm()">
            <input type="hidden" name="semester" value="<?= $semester; ?>">

            <table border="1">
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Final Grade</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($subjects as $subject): ?>
                        <tr>
                            <td><?= $subject['name']; ?></td>
                            <td><input type="text" name="final_grades[<?= $subject['id']; ?>]" class="final-grade" required onchange="calculateGWA()"></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <br>

            <label id="gwa">GWA: </label><br>
            <button type="submit">Submit Grades</button>
        </form>
    <?php endif; ?>

    <a href="students.php">Back to Students</a>
</body>

</html>
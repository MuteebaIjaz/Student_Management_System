<?php
require_once "../includes/conn.php";

session_start();
if (empty($_SESSION['user_id']) || $_SESSION['user_role'] !== "student") {
    header("Location:../Login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

$query        = "SELECT `student_id`, `class_id` FROM `students` WHERE `user_id` = '$user_id'";
$query_result = mysqli_query($conn, $query);
$student      = mysqli_fetch_assoc($query_result);
$student_id   = $student['student_id'];
$class_id     = $student['class_id'];

$result_query = "
    SELECT u.Name AS student_name, c.class_name, c.section
    FROM   users u
    JOIN   students  s ON s.user_id  = u.user_id
    JOIN   classes   c ON c.class_id = s.class_id
    WHERE  u.user_id = '$user_id'
";
$result       = mysqli_query($conn, $result_query);
$student_info = mysqli_fetch_assoc($result);

$student_marks = "
    SELECT
        s.subject_name,
        s.code,
        s.type,
        u.Name AS teacher_name,
        m.exam_type,
        m.date,
        m.total_marks,
        m.marks,
        ROUND((m.marks / m.total_marks) * 100, 1) AS percentage
    FROM   marks m
    JOIN   subject               s   ON s.subject_id = m.subject_id
    JOIN   class_subject_teacher cst ON cst.subject_id = m.subject_id
                                    AND cst.class_id   = '$class_id'
    JOIN   users                 u   ON u.user_id      = cst.teacher_id
    WHERE  m.student_id = '$student_id'
    ORDER  BY s.subject_name, m.date DESC
";
$student_marks_result = mysqli_query($conn, $student_marks);
$all_marks            = mysqli_fetch_all($student_marks_result, MYSQLI_ASSOC);

$total_obtained    = array_sum(array_column($all_marks, 'marks'));
$total_marks_sum   = array_sum(array_column($all_marks, 'total_marks'));
$overall_percentage = ($total_marks_sum > 0)
    ? round(($total_obtained / $total_marks_sum) * 100, 1)
    : 0;

$by_subject = [];
foreach ($all_marks as $m) {
    $by_subject[$m['subject_name']][] = $m;
}

function getGrade($percentage)
{
    if ($percentage >= 90) return ['A+', '#1a7a56', '#e6f5ee'];
    if ($percentage >= 80) return ['A',  '#1a7a56', '#e6f5ee'];
    if ($percentage >= 70) return ['B+', '#1a4f8a', '#e6eff9'];
    if ($percentage >= 60) return ['B',  '#1a4f8a', '#e6eff9'];
    if ($percentage >= 50) return ['C',  '#9a6200', '#fdf3dc'];
    if ($percentage >= 40) return ['D',  '#9a6200', '#fdf3dc'];
    return                 ['F',  '#b83030', '#fdeaea'];
}

[$overall_grade, $og_color, $og_bg] = getGrade($overall_percentage);
?>
<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="IE=edge" />
    <title>Dashboard</title>
    <link rel="shortcut icon" type="image/png" href="../assets/images/favicon.png?v=10" />
    <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="../assets/vendors/css/vendors.min.css" />
    <link rel="stylesheet" type="text/css" href="../assets/vendors/css/daterangepicker.min.css" />
    <link rel="stylesheet" type="text/css" href="../assets/css/theme.min.css" />
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="profile.css">
    <link rel="stylesheet" href="student.css">
</head>
<body>
<?php
include "../includes/navbar/student_navbar.php";
include "../includes/header.php";
?>

 <main class="nxl-container">
        <div class="nxl-content">
            <div class="page-header">
                <div class="page-header-left d-flex align-items-center">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Dashboard</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                        <li class="breadcrumb-item">Dashboard</li>
                    </ul>
                </div>
                <div class="page-header-right ms-auto">
                    <div class="page-header-right-items">
                        <div class="d-flex d-md-none">
                            <a href="javascript:void(0)" class="page-header-right-close-toggle">
                                <i class="feather-arrow-left me-2"></i>
                                <span>Back</span>
                            </a>
                        </div>

                    </div>
                    <div class="d-md-none d-flex align-items-center">
                        <a href="javascript:void(0)" class="page-header-right-open-toggle">
                            <i class="feather-align-right fs-20"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="page">
 
            <div class="class-badge">
                <h1 ><b>My Result</b></h1>
            </div>
            
            <div class="result-page-header d-flex align-items-center justify-content-between flex-wrap gap-2 pt-0">
                <div>
                    <div class="class-chip">
                        <span class="chip-dot"></span>
                        <?php echo htmlspecialchars($student_info['class_name'] . ' — ' . $student_info['section']); ?>
                    </div>
                    <p class="page-sub">
                        <?php echo count($by_subject); ?> subject<?php echo count($by_subject) !== 1 ? 's' : ''; ?>
                        &middot;
                        <?php echo count($all_marks); ?> exam<?php echo count($all_marks) !== 1 ? 's' : ''; ?> recorded
                    </p>
                </div>
             
            </div>
            <div class="summary-grid">
                <div class="stat-card">
                    <div class="stat-label">Overall %</div>
                    <div class="stat-value green"><?php echo $overall_percentage; ?>%</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Marks Obtained</div>
                    <div class="stat-value"><?php echo $total_obtained; ?> <span style="font-size:16px;color:var(--ink3);">/ <?php echo $total_marks_sum; ?></span></div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Exams Recorded</div>
                    <div class="stat-value"><?php echo count($all_marks); ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Overall Grade</div>
                    <div class="stat-value" style="line-height:2;">
                        <span class="grade-pill" style="background:<?php echo $og_bg; ?>;color:<?php echo $og_color; ?>;">
                            <?php echo $overall_grade; ?>
                        </span>
                    </div>
                </div>
            </div>
 
            <?php if (empty($by_subject)): ?>
                <div class="empty-state">
                    <div class="empty-icon">📋</div>
                    <div class="empty-title">No results yet</div>
                    <div class="empty-sub">Your marks will appear here once your teacher records them.</div>
                </div>
 
            <?php else: ?>
 
                <div class="section-label">Per-subject breakdown</div>
                <div class="subjects-grid">
 
                    <?php foreach ($by_subject as $subject_name => $exams):
                        $sub_obtained = array_sum(array_column($exams, 'marks'));
                        $sub_total    = array_sum(array_column($exams, 'total_marks'));
                        $sub_pct      = $sub_total > 0
                            ? round(($sub_obtained / $sub_total) * 100, 1) : 0;
 
                        $sub_color = $sub_pct >= 60
                            ? '#1a7a56' : ($sub_pct >= 40 ? '#9a6200' : '#b83030');
 
                        [$grade, $g_color, $g_bg] = getGrade($sub_pct);
 
                        $s_init  = strtoupper(mb_substr($subject_name, 0, 1));
                        $teacher = $exams[0]['teacher_name'];
                        $type    = $exams[0]['type'];
 
                        $type_bg  = $type === 'core' ? '#e6f5ee' : '#eeebfb';
                        $type_col = $type === 'core' ? '#1a7a56' : '#4a2fa0';
                        $icon_bg  = $type === 'core' ? '#e6eff9' : '#eeebfb';
                        $icon_col = $type === 'core' ? '#1a4f8a' : '#4a2fa0';
 
                        $words  = explode(' ', $teacher);
                        $t_init = strtoupper(
                            mb_substr($words[0], 0, 1) .
                            (isset($words[1]) ? mb_substr($words[1], 0, 1) : '')
                        );
                    ?>
                    <div class="result-card">
 
                        <div class="card-top">
                            <div class="subj-icon" style="background:<?php echo $icon_bg; ?>;color:<?php echo $icon_col; ?>;">
                                <?php echo htmlspecialchars($s_init); ?>
                            </div>
                            <span class="grade-pill" style="background:<?php echo $g_bg; ?>;color:<?php echo $g_color; ?>;">
                                <?php echo $grade; ?>
                            </span>
                        </div>
 
                        <div class="subj-name"><?php echo ($subject_name); ?></div>
                        <div class="subj-meta">
                            <?php echo ($exams[0]['code']); ?>
                            <span class="type-tag" style="background:<?php echo $type_bg; ?>;color:<?php echo $type_col; ?>;">
                                <?php echo ucfirst(($type)); ?>
                            </span>
                        </div>
 
                        <div class="teacher-row">
                            <div class="t-avatar"><?php echo ($t_init); ?></div>
                            <span class="t-name"><?php echo ($teacher); ?></span>
                        </div>
 
                        <hr class="card-divider">
 
                        <div class="pct-row">
                            <span class="pct-label"><?php echo $sub_obtained; ?> / <?php echo $sub_total; ?> marks</span>
                            <span class="pct-value" style="color:<?php echo $sub_color; ?>;"><?php echo $sub_pct; ?>%</span>
                        </div>
                        <div class="progress-track">
                            <div class="progress-fill"
                                style="width:<?php echo $sub_pct; ?>%;background:<?php echo $sub_color; ?>;"></div>
                        </div>
 
                        <?php if (!empty($exams)): ?>
                        <div class="exam-list">
                            <?php foreach ($exams as $exam):
                                [$eg, $ec, $eb] = getGrade($exam['percentage']);
                            ?>
                            <div class="exam-row">
                                <div>
                                    <span class="exam-name"><?php echo ($exam['exam_type']); ?></span>
                                    <span class="exam-date">&middot; <?php echo date('d M Y', strtotime($exam['date'])); ?></span>
                                </div>
                                <div class="exam-score-wrap">
                                    <span class="exam-score"><?php echo $exam['marks']; ?>/<?php echo $exam['total_marks']; ?></span>
                                    <span class="mini-grade" style="background:<?php echo $eb; ?>;color:<?php echo $ec; ?>;">
                                        <?php echo $eg; ?>
                                    </span>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
 
                    </div>
                    <?php endforeach; ?>
 
                </div>
                <div class="table-section">
                    <div class="section-label">All results</div>
                    <div class="table-wrap">
                        <table class="results-table">
                            <thead>
                                <tr>
                                    <th>Subject</th>
                                    <th>Code</th>
                                    <th>Exam Type</th>
                                    <th>Date</th>
                                    <th>Marks</th>
                                    <th>Total</th>
                                    <th>Percentage</th>
                                    <th>Grade</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($all_marks as $row):
                                    [$grade, $g_color, $g_bg] = getGrade($row['percentage']);
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['subject_name']); ?></td>
                                    <td style="color:var(--ink3);"><?php echo htmlspecialchars($row['code']); ?></td>
                                    <td><?php echo htmlspecialchars($row['exam_type']); ?></td>
                                    <td><?php echo date('d M Y', strtotime($row['date'])); ?></td>
                                    <td><?php echo $row['marks']; ?></td>
                                    <td><?php echo $row['total_marks']; ?></td>
                                    <td style="font-weight:500;color:<?php echo $g_color; ?>;">
                                        <?php echo $row['percentage']; ?>%
                                    </td>
                                    <td>
                                        <span class="mini-grade" style="background:<?php echo $g_bg; ?>;color:<?php echo $g_color; ?>;">
                                            <?php echo $grade; ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
 
                            <tfoot>
                                <tr style="background:var(--surface2);">
                                    <td colspan="4" style="font-weight:600;color:var(--ink);padding:11px 16px;">
                                        Overall Total
                                    </td>
                                    <td style="font-weight:600;color:var(--ink);padding:11px 16px;">
                                        <?php echo $total_obtained; ?>
                                    </td>
                                    <td style="font-weight:600;color:var(--ink);padding:11px 16px;">
                                        <?php echo $total_marks_sum; ?>
                                    </td>
                                    <td style="font-weight:600;color:<?php echo $og_color; ?>;padding:11px 16px;">
                                        <?php echo $overall_percentage; ?>%
                                    </td>
                                    <td style="padding:11px 16px;">
                                        <span class="mini-grade" style="background:<?php echo $og_bg; ?>;color:<?php echo $og_color; ?>;">
                                            <?php echo $overall_grade; ?>
                                        </span>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
 
            <?php endif; ?>
            </div>
        </div>



        <br>

        <?php

        include "../includes/footer.php";
        ?>
    </main>


<script src="../assets/vendors/js/vendors.min.js"></script>
<script src="../assets/vendors/js/daterangepicker.min.js"></script>
<script src="../assets/vendors/js/apexcharts.min.js"></script>
<script src="../assets/vendors/js/circle-progress.min.js"></script>
<script src="../assets/js/common-init.min.js"></script>
<script src="../assets/js/dashboard-init.min.js"></script>
<script src="../assets/js/theme-customizer-init.min.js"></script>
</body>
</html>

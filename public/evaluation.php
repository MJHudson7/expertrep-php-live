<?php
require __DIR__ . '/../includes/demo-context.php';

$records = get_records();
$active_page = 'evaluation';
$page_title = 'Evaluation';

$app_data = [
    'theme'      => $theme_colors,
    'records'                    => $records,
    'courseEvaluationRecords'    => get_course_evaluation_records(),
    'workshopEvaluationRecords'  => get_workshop_evaluation_records(),
    'facilitatorReports'         => get_facilitator_reports(),
    'coursesWithWorkshop'        => get_courses_with_workshop(),
    'courseEvalQuestions'        => COURSE_EVAL_QUESTIONS,
    'courseEvalScale'            => COURSE_EVAL_SCALE,
    'workshopEvalQuestions'      => WORKSHOP_EVAL_QUESTIONS,
    'workshopScale'              => WORKSHOP_SCALE,
    'facilitatorReportQuestions' => FACILITATOR_REPORT_QUESTIONS,
    'courses'                    => COURSES,
    'teams'                      => TEAMS,
    'names'                      => NAMES,
    'months'                     => MONTHS,
    'monthYears'                 => MONTH_YEARS,
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include __DIR__ . '/../includes/head.php'; ?>
</head>
<body>

<div class="app">

<?php include __DIR__ . '/../includes/sidebar.php'; ?>

  <!-- MAIN -->
  <main class="main">
    <div class="main-top">
      <div class="page-title heading">EVALUATION</div>
      <div class="logo"><img src="assets/img/expertrep-logo.png" alt="ExpertRep" class="logo-mark">EXPERT<span class="dot">·</span>REP</div>
    </div>

    <div class="eval-section-title">Course Evaluation</div>
    <div class="eval-donut-row" id="courseEvalRow"></div>

    <div id="workshopSections"></div>

  </main>

<?php include __DIR__ . '/../includes/filters-panel.php'; ?>
<?php include __DIR__ . '/../includes/user-guide-modal.php'; ?>

</div>

<script>
  window.APP_DATA = <?php echo json_encode($app_data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>;
  // This page only: Course filter is single-select, defaulted to the
  // first course — "has a workshop or not" is a per-course concept, so
  // showing multiple courses at once here would be ambiguous.
  window.FILTER_CONFIG = {
    courseSingleSelectOnly: true,
    defaultCourse: window.APP_DATA.courses[0]
  };
</script>
<script src="assets/js/filters.js"></script>
<script src="assets/js/user-guide.js"></script>
<script src="assets/js/evaluation.js"></script>

</body>
</html>

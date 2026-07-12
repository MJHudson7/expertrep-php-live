<?php
require __DIR__ . '/../includes/demo-context.php';

$records = get_records();
$assessment_records = get_assessment_records();
$active_page = 'performance-detail';
$page_title = 'Performance Detail';

$app_data = [
    'theme'      => $theme_colors,
    'records'           => $records,
    'assessmentRecords' => $assessment_records,
    'courseActivities'  => get_course_activities(),
    'courses'           => COURSES,
    'teams'             => TEAMS,
    'names'             => NAMES,
    'months'            => MONTHS,
    'monthYears'        => MONTH_YEARS,
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
      <div class="page-title heading">PERFORMANCE - DETAIL</div>
      <div class="logo"><img src="assets/img/expertrep-logo.png" alt="ExpertRep" class="logo-mark">EXPERT<span class="dot">·</span>REP</div>
    </div>

    <div class="chart-panel">
      <div class="chart-panel-head">GRADES (%) BY ASSESSMENT</div>
    </div>

    <div class="view-toggle-bar">
      <div class="view-toggle">
        <button class="view-toggle-btn active" data-mode="person" id="viewModePerson">By Person</button>
        <button class="view-toggle-btn" data-mode="assessment" id="viewModeAssessment">By Assessment</button>
      </div>
      <select class="view-select" id="viewSelect"></select>
    </div>

    <div id="participantChartsContainer"></div>
  </main>

<?php include __DIR__ . '/../includes/filters-panel.php'; ?>
<?php include __DIR__ . '/../includes/user-guide-modal.php'; ?>

</div>

<script>
  window.APP_DATA = <?php echo json_encode($app_data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>;
  // This page only: Course filter is single-select (pick one course at a
  // time), defaulted to the first course in the list on load.
  window.FILTER_CONFIG = {
    courseSingleSelectOnly: true,
    defaultCourse: window.APP_DATA.courses[0]
  };
</script>
<script src="assets/js/filters.js"></script>
<script src="assets/js/user-guide.js"></script>
<script src="assets/js/performance-detail.js"></script>

</body>
</html>

<?php
require __DIR__ . '/../includes/demo-context.php';

$records = get_records();
$active_page = 'performance-results';
$page_title = 'Performance Results';

$app_data = [
    'theme'      => $theme_colors,
    'records'    => $records,
    'courses'    => COURSES,
    'teams'      => TEAMS,
    'roles'      => ROLES,
    'names'      => NAMES,
    'months'     => MONTHS,
    'monthYears' => MONTH_YEARS,
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
      <div class="page-title heading">PERFORMANCE RESULTS</div>
      <div class="main-top-right">
        <?php include __DIR__ . '/../includes/pdf-button.php'; ?>
        <div class="logo"><img src="assets/img/expertrep-logo.png" alt="ExpertRep" class="logo-mark">EXPERT<span class="dot">·</span>REP</div>
      </div>
    </div>

    <div class="chart-panel">
      <div class="chart-panel-head">LEARNING ACTIVITIES SUCCESSFULLY COMPLETED PER TEAM MEMBER</div>
      <div class="chart-panel-body">
        <div class="chart-panel-canvas-wrap"><canvas id="activitiesLineChart"></canvas></div>
      </div>
    </div>

    <div class="chart-panel">
      <div class="chart-panel-head">GRADE % BY PARTICIPANT</div>
      <div class="chart-panel-body">
        <div class="chart-panel-canvas-wrap"><canvas id="gradeBarChart"></canvas></div>
      </div>
    </div>
  </main>

<?php include __DIR__ . '/../includes/filters-panel.php'; ?>
<?php include __DIR__ . '/../includes/user-guide-modal.php'; ?>

</div>

<script>
  window.APP_DATA = <?php echo json_encode($app_data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>;
</script>
<script src="assets/js/filters.js"></script>
<script src="assets/js/user-guide.js"></script>
<script src="assets/js/performance-results.js"></script>

</body>
</html>

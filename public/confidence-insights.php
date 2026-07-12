<?php
require __DIR__ . '/../includes/demo-context.php';

$records = get_records();
$confidence_records = get_confidence_records();
$active_page = 'confidence-insights';
$page_title = 'Confidence Insights';

$app_data = [
    'theme'      => $theme_colors,
    'records'            => $records,
    'confidenceRecords'  => $confidence_records,
    'confidenceStatements' => CONFIDENCE_STATEMENTS,
    'courses'            => COURSES,
    'teams'              => TEAMS,
    'names'              => NAMES,
    'months'             => MONTHS,
    'monthYears'         => MONTH_YEARS,
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
      <div class="page-title heading">CONFIDENCE INSIGHTS</div>
      <div class="logo"><img src="assets/img/expertrep-logo.png" alt="ExpertRep" class="logo-mark">EXPERT<span class="dot">·</span>REP</div>
    </div>

    <div class="chart-panel">
      <div class="chart-panel-head">CONFIDENCE LEVEL - SELF-ASSESSMENT (REPORTED)</div>
      <div class="chart-panel-body">
        <div class="chart-panel-canvas-wrap" id="confidenceCanvasWrap" style="height: 420px;">
          <canvas id="confidenceChart"></canvas>
        </div>
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
<script src="assets/js/confidence-insights.js"></script>

</body>
</html>

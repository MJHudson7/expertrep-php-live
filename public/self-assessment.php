<?php
require __DIR__ . '/../includes/demo-context.php';

$records = get_records();
$self_assessment_records = get_self_assessment_records();
$active_page = 'self-assessment';
$page_title = 'Self Assessment';

$app_data = [
    'theme'      => $theme_colors,
    'records'                => $records,
    'selfAssessmentRecords'  => $self_assessment_records,
    'courses'                => COURSES,
    'teams'                  => TEAMS,
    'roles'                  => ROLES,
    'names'                  => NAMES,
    'months'                 => MONTHS,
    'monthYears'             => MONTH_YEARS,
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
      <div class="page-title heading">SELF ASSESSMENT</div>
      <div class="main-top-right">
        <?php include __DIR__ . '/../includes/pdf-button.php'; ?>
        <div class="logo"><img src="assets/img/expertrep-logo.png" alt="ExpertRep" class="logo-mark">EXPERT<span class="dot">·</span>REP</div>
      </div>
    </div>

    <div class="chart-panel">
      <div class="chart-panel-head">SELF ASSESSMENT SCORE %</div>
      <div class="chart-panel-body">
        <div class="chart-panel-body-with-summary">
          <div class="chart-panel-canvas-wrap" id="selfAssessmentCanvasWrap" style="height: 420px;">
            <canvas id="selfAssessmentChart"></canvas>
          </div>
          <div class="self-assessment-summary" id="selfAssessmentSummary">
            <div class="self-assessment-kpi" style="background:<?php echo htmlspecialchars($theme_colors['teal-dark']); ?>;">
              <div class="label">BEFORE<br>AVERAGE %</div>
              <div class="value" id="beforeAverage">-</div>
            </div>
            <div class="self-assessment-kpi" style="background:<?php echo htmlspecialchars($theme_colors['teal']); ?>;">
              <div class="label">AFTER<br>AVERAGE %</div>
              <div class="value" id="afterAverage">-</div>
            </div>
          </div>
        </div>
        <div class="custom-legend">
          <span class="legend-item"><span class="legend-swatch" style="background:<?php echo htmlspecialchars($theme_colors['teal-dark']); ?>"></span>Before</span>
          <span class="legend-item"><span class="legend-swatch" style="background:<?php echo htmlspecialchars($theme_colors['teal']); ?>"></span>After</span>
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
<script src="assets/js/self-assessment.js"></script>

</body>
</html>

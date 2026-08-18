<?php
require __DIR__ . '/../includes/demo-context.php';

$records = get_records();
$active_page = 'home';
$page_title = 'Overview';

// Data handed to the frontend. Later, when this reads from MySQL instead of
// dummy data, this is still the shape the JS expects — only get_records()
// inside data.php needs to change.
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
      <div class="page-title heading">OVERVIEW</div>
      <div class="main-top-right">
        <?php include __DIR__ . '/../includes/pdf-button.php'; ?>
        <div class="logo"><img src="assets/img/expertrep-logo.png" alt="ExpertRep" class="logo-mark">EXPERT<span class="dot">·</span>REP</div>
      </div>
    </div>

    <!-- Row 1: KPIs + top performers -->
    <div class="row-1">
      <div class="section-label">Performance Results</div>
      <div class="tp-head">
        <div class="tp-title">Top 3 Performers</div>
        <div class="tp-head-icons">
          <div class="tp-icon-col"><span class="tp-icon-badge">✓</span></div>
          <div class="tp-icon-col"><span class="tp-icon-badge">★</span></div>
        </div>
      </div>

      <div class="kpi-pair">
        <div class="kpi-card">
          <div class="label">AVERAGE GRADE</div>
          <div class="value" id="kpiAvgGrade">-</div>
        </div>
        <div class="kpi-card">
          <div class="label">BADGES EARNED</div>
          <div class="value" id="kpiBadges">-</div>
        </div>
      </div>
      <div class="top-performers-body" id="tpBody"></div>
    </div>

    <!-- Row 2: donuts -->
    <div class="donut-row">
      <div class="donut-panel">
        <div class="donut-head">ACTIVITIES SUCCESSFULLY COMPLETED</div>
        <div class="donut-body">
          <div class="donut-canvas-wrap"><canvas id="donutActivities"></canvas></div>
          <div class="donut-legend" id="legendActivities"></div>
        </div>
      </div>
      <div class="donut-panel">
        <div class="donut-head">KNOWLEDGE GAINED (REPORTED)</div>
        <div class="donut-body">
          <div class="donut-canvas-wrap"><canvas id="donutKnowledge"></canvas></div>
          <div class="donut-legend" id="legendKnowledge"></div>
        </div>
      </div>
      <div class="donut-panel">
        <div class="donut-head">CONFIDENCE GAINED (REPORTED)</div>
        <div class="donut-body">
          <div class="donut-canvas-wrap"><canvas id="donutConfidence"></canvas></div>
          <div class="donut-legend" id="legendConfidence"></div>
        </div>
      </div>
    </div>

    <!-- Quote -->
    <div class="quote-block">
      <span class="mark left">&ldquo;</span>
      <div class="quote-text">
        The platform fits perfectly into my day. I can quickly jump in, answer a few questions, and continue learning without needing to block out large chunks of time.
        <span class="attr">- Wayne Carolus</span>
      </div>
      <span class="mark right">&rdquo;</span>
    </div>
  </main>

<?php include __DIR__ . '/../includes/filters-panel.php'; ?>
<?php include __DIR__ . '/../includes/user-guide-modal.php'; ?>

</div>

<script>
  // Data generated server-side by PHP (includes/data.php), handed to the
  // frontend as JSON. This is the seam where dummy data becomes real data:
  // once MySQL is wired up, $app_data above still gets built the same way,
  // just sourced from a query instead of get_records().
  window.APP_DATA = <?php echo json_encode($app_data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>;
</script>
<script src="assets/js/filters.js"></script>
<script src="assets/js/user-guide.js"></script>
<script src="assets/js/home.js"></script>

</body>
</html>

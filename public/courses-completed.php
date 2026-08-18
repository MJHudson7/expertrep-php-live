<?php
require __DIR__ . '/../includes/demo-context.php';

$records = get_records();
$active_page = 'courses-completed';
$page_title = 'Courses Completed';

$app_data = [
    'records'    => $records,
    'courses'    => COURSES,
    'teams'      => TEAMS,
    'roles'      => ROLES,
    'names'      => NAMES,
    'months'     => MONTHS,
    'monthYears' => MONTH_YEARS,
];
// No badge images exist yet for the current 11-course sample list (the
// old mapping was tied to the previous 4 course names and no longer
// matches anything). Leaving this empty is intentional — the
// course-badge-card markup below already renders blank when a course
// has no entry here, so cards just show no image until real badge
// artwork is provided. Add entries as: 'Exact Course Name' => 'assets/img/badges/file.jpg'
$course_badges = [];
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
      <div class="page-title heading">COURSES COMPLETED</div>
      <div class="main-top-right">
        <?php include __DIR__ . '/../includes/pdf-button.php'; ?>
        <div class="logo"><img src="assets/img/expertrep-logo.png" alt="ExpertRep" class="logo-mark">EXPERT<span class="dot">·</span>REP</div>
      </div>
    </div>

    <?php foreach (COURSES as $i => $course): ?>
    <div class="course-card" data-course="<?php echo htmlspecialchars($course); ?>">
      <div class="course-title-heading"><?php echo htmlspecialchars($course); ?></div>
      <div class="course-card-row">

        <div class="course-badge-card">
          <?php if (isset($course_badges[$course])): ?>
            <img src="<?php echo htmlspecialchars($course_badges[$course]); ?>" alt="">
          <?php endif; ?>
        </div>

        <div class="course-badges-kpi-card">
          <div class="label">Badges Earned</div>
          <div class="kpi-underline"></div>
          <div class="value" id="badgesValue-<?php echo $i; ?>">-</div>
        </div>

        <div class="course-tp-card">
          <div class="course-tp-head">
            <span>Top 3 Performers</span>
            <span class="course-tp-star">★</span>
          </div>
          <div class="course-tp-body" id="tpBody-<?php echo $i; ?>"></div>
        </div>

      </div>
    </div>
    <?php endforeach; ?>

  </main>

<?php include __DIR__ . '/../includes/filters-panel.php'; ?>
<?php include __DIR__ . '/../includes/user-guide-modal.php'; ?>

</div>

<script>
  window.APP_DATA = <?php echo json_encode($app_data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>;
</script>
<script src="assets/js/filters.js"></script>
<script src="assets/js/user-guide.js"></script>
<script src="assets/js/courses-completed.js"></script>

</body>
</html>

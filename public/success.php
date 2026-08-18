<?php
require __DIR__ . '/../includes/demo-context.php';

$records = get_records();
$active_page = 'success';
$page_title = 'Success';

$app_data = [
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
      <div class="page-title heading">SUCCESS</div>
      <div class="main-top-right">
        <?php include __DIR__ . '/../includes/pdf-button.php'; ?>
        <div class="logo"><img src="assets/img/expertrep-logo.png" alt="ExpertRep" class="logo-mark">EXPERT<span class="dot">·</span>REP</div>
      </div>
    </div>

    <div class="success-lessons-panel">
      <div class="success-lessons-title">Interpretation</div>
      <ul class="success-lessons-list">
        <li>The overall average grade of 85.25% meets the required company standards.</li>
        <li>A total of 28 courses successfully completed and associated badges earned reflects the amount of learning achieved.</li>
        <li>Team members consistently reported an improvement of confidence from 71% confidence before, to 87% confidence after having completed the courses.</li>
        <li>The workshops consistently met expectations, built knowledge and confidence and sessions were effectively facilitated.</li>
      </ul>
    </div>

    <div class="success-lessons-panel">
      <div class="success-lessons-title">Lessons Learned &amp; Recommendations</div>
      <p style="margin: 0 0 10px; font-size: 13px; color: var(--text);">Areas where performance results indicate further development may be beneficial are:</p>
      <ul class="success-lessons-list">
        <li>Handling objections</li>
        <li>Negotiation principles and practices</li>
        <li>Advanced listening techniques</li>
      </ul>
    </div>

    <!--
      Quotes below use "Team Member" as a placeholder attribution — the
      content you sent didn't include names for these 3 quotes ("add
      names from report to each" reads like a note-to-self on your end).
      Swap in the real names whenever you have them.
    -->
    <div class="success-quotes-row">
      <div class="success-quote-card">
        <div class="success-quote-text">I love that we can do this on our own time. It is relaxed and I even do a few questions in front of the TV sometimes. And if I don&rsquo;t get it right the first time it is not a train smash. I actually remember the questions when I go back a second time, this really helps me to learn.</div>
        <div class="success-quote-attr">- Team Member</div>
      </div>
      <div class="success-quote-card">
        <div class="success-quote-text">Going into the personality types with different tools helped me to be better at identifying what type of personality my customers have. Changing my approach to call preparation and identifying possible objections and how to handle it.</div>
        <div class="success-quote-attr">- Team Member</div>
      </div>
      <div class="success-quote-card">
        <div class="success-quote-text">Loved the format of this course, self check constantly during course is lovely.</div>
        <div class="success-quote-attr">- Team Member</div>
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

</body>
</html>

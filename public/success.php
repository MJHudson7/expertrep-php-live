<?php
require __DIR__ . '/../includes/demo-context.php';

$records = get_records();
$active_page = 'success';
$page_title = 'Success';

$app_data = [
    'records'    => $records,
    'courses'    => COURSES,
    'teams'      => TEAMS,
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
      <div class="logo"><img src="assets/img/expertrep-logo.png" alt="ExpertRep" class="logo-mark">EXPERT<span class="dot">·</span>REP</div>
    </div>

    <div class="success-lessons-panel">
      <div class="success-lessons-title">Lessons Learned &amp; Recommendations</div>
      <ul class="success-lessons-list">
        <li>The overall learning experience was extremely positive. All team members indicated they have benefited from completing the course. Improvement in self-reported confidence was reported by 14/16 (88%) of the team, when comparing before and after course completion scores.</li>
        <li>Areas of improvement identified by the team were only related to the user-friendliness of the platform. We will review types of activities, feedback fields, responsiveness and overall site navigation.</li>
        <li>Brendan stated Strongly Disagree with ExpertRep communicating and responding to questions. We followed up with him and he responded that his rating was &ldquo;a mistake and that everything about that was very good&rdquo;.</li>
        <li>Chantel requested to view her team grades during the course. We are looking into providing a real-time overview of each team&rsquo;s grades available to Sales Managers within the ExpertRep platform.</li>
        <li>Time keeping was very good. During week 6 when most team members had to redo one or more activities, some left it a bit too late. This should be better in cycle 2, when the process is not new anymore.</li>
        <li>
          When activities required critical thinking and practical application (Week 4 and 5), the results were not as high as we would have wanted. This identified areas of improvement, of which the main areas are:
          <ol class="success-lessons-sublist">
            <li>Consistently mentioning a benefit with every feature.</li>
            <li>Purposefully include assertive language in predeveloped, practiced phrases.</li>
            <li>Reflect on their own responses, understand why they are passive, aggressive or assertive and improve their assertiveness.</li>
            <li>Create and practice realistic phrases which match your own personality and communication style to not sound rehearsed but prepared, authentic and assertive.</li>
          </ol>
        </li>
      </ul>
    </div>

    <div class="success-quotes-row">
      <div class="success-quote-card">
        <div class="success-quote-text">The topics covered were effective and informative.</div>
        <div class="success-quote-attr">- Thanusha</div>
      </div>
      <div class="success-quote-card">
        <div class="success-quote-text">Recommend? Yes, it helps in difficult situations to communicate your experience and listen to what the other person wants to tell you.</div>
        <div class="success-quote-attr">- Hanri</div>
      </div>
      <div class="success-quote-card">
        <div class="success-quote-text">Good assignments and learned a lot, had to sit and concentrate to do assignments that was very in depth.</div>
        <div class="success-quote-attr">- Marlien</div>
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

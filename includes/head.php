<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ExpertRep - <?php echo htmlspecialchars($page_title); ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">
<?php if (!empty($company_data['accent'])): ?>
<style>
  /* Per-company theme override (see includes/demo-context.php).
     Note: this re-colors the CSS-driven chrome (headers, buttons, nav,
     borders) but NOT the charts themselves, since Chart.js colors are
     set as literal hex values in each page's JS rather than reading
     CSS variables. Full per-company chart theming would mean passing
     the accent colors into window.APP_DATA and having each chart file
     use them instead of hardcoded hex - a follow-up if that's wanted. */
  :root {
    <?php foreach ($company_data['accent'] as $var => $value): ?>
    --<?php echo htmlspecialchars($var); ?>: <?php echo htmlspecialchars($value); ?>;
    <?php endforeach; ?>
  }
</style>
<?php endif; ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.2.0/chartjs-plugin-datalabels.min.js"></script>

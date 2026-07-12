<?php
/**
 * sidebar.php
 *
 * Expects $active_page to be set by the including page (e.g. 'home',
 * 'performance-results') so the matching nav item gets highlighted.
 *
 * Also expects $company_data and $is_admin, both set by demo-context.php
 * (which every page includes before this partial) - used to show the
 * current company's name/logo. There's no login in this standalone
 * build, so no logout/admin links here.
 */
$nav_items = [
    'home'                 => ['label' => 'Home',                 'href' => 'index.php',                 'icon' => '<path d="M3 11l9-8 9 8"/><path d="M5 10v10h14V10"/>'],
    'performance-results'  => ['label' => 'Performance Results',  'href' => 'performance-results.php',  'icon' => '<path d="M4 19h16"/><path d="M7 19v-6M12 19V9M17 19v-10"/>'],
    'performance-detail'   => ['label' => 'Performance Detail',   'href' => 'performance-detail.php',   'icon' => '<circle cx="12" cy="12" r="8.5"/><path d="M12 8v4l3 2"/>'],
    'courses-completed'    => ['label' => 'Courses Completed',    'href' => 'courses-completed.php',    'icon' => '<circle cx="12" cy="12" r="9"/><path d="M8 12.5l2.5 2.5L16 9"/>'],
    'confidence-insights'  => ['label' => 'Confidence Insights',  'href' => 'confidence-insights.php',  'icon' => '<path d="M4 5h16v11H8l-4 4V5z"/>'],
    'self-assessment'      => ['label' => 'Self Assessment',      'href' => 'self-assessment.php',      'icon' => '<circle cx="11" cy="11" r="6.5"/><path d="M20 20l-4.5-4.5"/>'],
    'evaluation'           => ['label' => 'Evaluation',           'href' => 'evaluation.php',           'icon' => '<circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="4.5"/><circle cx="12" cy="12" r="0.8" fill="currentColor"/>'],
    'success'              => ['label' => 'Success',              'href' => 'success.php',              'icon' => '<path d="M7 10v10H4V10h3zm0 0l4.5-6c.6-.8 1.8-.3 1.8.6L13 9h5.2c1 0 1.7.9 1.5 1.8l-1.7 7.2c-.2.9-1 1.5-1.9 1.5H7"/>'],
];
?>
  <aside class="sidebar">
    <div class="sidebar-account">
      <div class="sidebar-company-name"><?php echo htmlspecialchars($company_data['name'] ?? ''); ?></div>
    </div>
    <?php foreach ($nav_items as $key => $item): ?>
    <a href="<?php echo $item['href']; ?>" class="nav-item<?php echo ($active_page === $key ? ' active' : ''); ?>">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><?php echo $item['icon']; ?></svg>
      <span><?php echo $item['label']; ?></span>
    </a>
    <?php endforeach; ?>
    <div class="sidebar-logo">
      <img src="<?php echo htmlspecialchars($company_data['logo'] ?? 'assets/img/expertrep-logo.png'); ?>" alt="<?php echo htmlspecialchars($company_data['name'] ?? 'ExpertRep'); ?>">
    </div>
  </aside>

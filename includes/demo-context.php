<?php
/**
 * demo-context.php
 *
 * Standalone-demo replacement for auth.php + data.php. There's no login
 * here — this build always shows "Demo Client A" with the default
 * ExpertRep theme, no session, no company switching. Sets the exact same
 * variable names auth.php normally would ($company_data, $theme_colors,
 * $is_admin), so sidebar.php, head.php, and every page's $app_data
 * assembly work completely unchanged.
 */

require_once __DIR__ . '/data.php';

$company_data = [
    'name'   => 'Demo Client A',
    'logo'   => 'assets/img/expertrep-logo.png',
    'accent' => null, // null = default ExpertRep teal palette
];

$theme_colors = [
    'teal-dark'  => '#005B5E',
    'teal'       => '#008990',
    'teal-mid'   => '#007277',
    'teal-light' => '#5FB0B5',
    'teal-pale'  => '#D9EEEF',
];

$is_admin = false; // hides the "Back to Admin" sidebar link, which doesn't apply here

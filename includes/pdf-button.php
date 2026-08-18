<?php
/**
 * pdf-button.php
 *
 * Shared "Download PDF" button, included in the main-top of every page.
 * Uses the browser's native print-to-PDF (window.print()) rather than a
 * server-generated file — no backend/email service involved. Pairs with
 * the @media print rules in style.css, which hide the sidebar, filters,
 * and this button itself so only the dashboard content prints.
 */
?>
<button type="button" class="pdf-download-btn" onclick="window.print()" title="Download this page as a PDF">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3v12"/><path d="M7 10l5 5 5-5"/><path d="M5 20h14"/></svg>
  Download PDF
</button>

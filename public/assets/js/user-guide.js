/**
 * user-guide.js
 *
 * Shared across every page. Handles opening/closing the User Guide modal
 * and switching between its two panels ("Dashboard Explained" and "Using
 * the Filters"), triggered by the buttons in filters-panel.php.
 */

const guideOverlay = document.getElementById('guideOverlay');
const guideModalClose = document.getElementById('guideModalClose');
const guidePanels = {
  dashboard: document.getElementById('guidePanel-dashboard'),
  filters: document.getElementById('guidePanel-filters'),
};

function openGuide(which) {
  Object.keys(guidePanels).forEach(key => {
    guidePanels[key].style.display = (key === which) ? '' : 'none';
  });
  guideOverlay.classList.add('open');
}

function closeGuide() {
  guideOverlay.classList.remove('open');
}

document.querySelectorAll('.guide-icon-btn').forEach(btn => {
  btn.addEventListener('click', () => openGuide(btn.dataset.guide));
});

guideModalClose.addEventListener('click', closeGuide);
guideOverlay.addEventListener('click', (event) => {
  if (event.target === guideOverlay) closeGuide();
});
document.addEventListener('keydown', (event) => {
  if (event.key === 'Escape') closeGuide();
});

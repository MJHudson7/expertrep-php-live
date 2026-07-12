/**
 * evaluation.js
 *
 * Course Evaluation always renders (every course has this standardized
 * form). Workshop Evaluation and the Facilitator Report only render when
 * the currently-selected course (Course is single-select on this page —
 * see FILTER_CONFIG in evaluation.php) has an associated workshop; when
 * it doesn't, a single "no workshop" notice replaces both sections so the
 * page still displays cleanly rather than showing empty panels.
 */

const COURSE_EVAL_RECORDS = window.APP_DATA.courseEvaluationRecords;
const WORKSHOP_EVAL_RECORDS = window.APP_DATA.workshopEvaluationRecords;
const FACILITATOR_REPORTS = window.APP_DATA.facilitatorReports;
const COURSES_WITH_WORKSHOP = new Set(window.APP_DATA.coursesWithWorkshop);

const COURSE_EVAL_QUESTIONS = window.APP_DATA.courseEvalQuestions;
const COURSE_EVAL_SCALE = window.APP_DATA.courseEvalScale;
const WORKSHOP_EVAL_QUESTIONS = window.APP_DATA.workshopEvalQuestions;
const WORKSHOP_SCALE = window.APP_DATA.workshopScale;
const FACILITATOR_REPORT_QUESTIONS = window.APP_DATA.facilitatorReportQuestions;

const courseEvalRow = document.getElementById('courseEvalRow');
const workshopSections = document.getElementById('workshopSections');

const THEME = window.APP_DATA.theme;
const DONUT_COLORS = [THEME['teal-dark'], THEME['teal-mid'], THEME.teal, THEME['teal-light'], '#B9C3C5'];

let chartInstances = [];

/**
 * Builds one small donut panel showing the response distribution for a
 * single question, into the given container.
 */
function makeQuestionDonut(container, questionText, scale, counts) {
  const panel = document.createElement('div');
  panel.className = 'eval-donut-panel';
  const canvasId = 'donut-' + Math.random().toString(36).slice(2);
  panel.innerHTML = `
    <div class="eval-donut-question">${questionText}</div>
    <div class="eval-donut-body">
      <div class="eval-donut-canvas-wrap"><canvas id="${canvasId}"></canvas></div>
      <div class="eval-donut-legend" id="${canvasId}-legend"></div>
    </div>
  `;
  container.appendChild(panel);

  const total = counts.reduce((a, b) => a + b, 0) || 1;
  const chart = new Chart(document.getElementById(canvasId), {
    type: 'doughnut',
    data: { labels: scale, datasets: [{ data: counts, backgroundColor: DONUT_COLORS, borderWidth: 0 }] },
    options: {
      responsive: true, maintainAspectRatio: false,
      cutout: '55%',
      plugins: { legend: { display: false }, tooltip: { enabled: true } }
    }
  });
  chartInstances.push(chart);

  const legendEl = document.getElementById(canvasId + '-legend');
  scale.forEach((label, i) => {
    const pct = Math.round((counts[i] / total) * 100);
    const item = document.createElement('div');
    item.className = 'item';
    item.innerHTML = `<span class="sw" style="background:${DONUT_COLORS[i]}"></span>${label} ${pct}%`;
    legendEl.appendChild(item);
  });
}

function countsByScale(records, questionKey, scale) {
  return scale.map(option => records.filter(r => r.response === option).length);
}

/**
 * Makes every .eval-donut-question header within `row` the same height
 * (matching the tallest one), so the donuts below them all start at the
 * same vertical position regardless of how long each question's text is.
 */
function equalizeHeadingHeights(row) {
  const headings = row.querySelectorAll('.eval-donut-question');
  if (headings.length === 0) return;

  headings.forEach(h => { h.style.height = 'auto'; });
  let maxHeight = 0;
  headings.forEach(h => { maxHeight = Math.max(maxHeight, h.offsetHeight); });
  headings.forEach(h => { h.style.height = maxHeight + 'px'; });
}

function renderCourseEvaluation() {
  const filtered = window.applyCurrentFilters(COURSE_EVAL_RECORDS);
  courseEvalRow.innerHTML = '';

  if (filtered.length === 0) {
    courseEvalRow.innerHTML = '<div class="tp-empty">No data for current filters</div>';
    return;
  }

  COURSE_EVAL_QUESTIONS.forEach(question => {
    const forQuestion = filtered.filter(r => r.question === question);
    const counts = countsByScale(forQuestion, 'response', COURSE_EVAL_SCALE);
    makeQuestionDonut(courseEvalRow, question, COURSE_EVAL_SCALE, counts);
  });
  equalizeHeadingHeights(courseEvalRow);
}

function renderWorkshopSections() {
  const selectedCourse = window.getSelectedCourse();
  workshopSections.innerHTML = '';

  if (!selectedCourse || !COURSES_WITH_WORKSHOP.has(selectedCourse)) {
    workshopSections.innerHTML = `
      <div class="eval-section-title">Workshop Evaluation</div>
      <div class="no-workshop-notice">This course has no associated workshop.</div>
    `;
    return;
  }

  // Workshop Evaluation (participant-completed) — donuts
  const workshopTitle = document.createElement('div');
  workshopTitle.className = 'eval-section-title';
  workshopTitle.textContent = 'Workshop Evaluation';
  workshopSections.appendChild(workshopTitle);

  const workshopRow = document.createElement('div');
  workshopRow.className = 'eval-donut-row';
  workshopSections.appendChild(workshopRow);

  const filteredWorkshop = window.applyCurrentFilters(WORKSHOP_EVAL_RECORDS)
    .filter(r => r.course === selectedCourse);

  if (filteredWorkshop.length === 0) {
    workshopRow.innerHTML = '<div class="tp-empty">No data for current filters</div>';
  } else {
    WORKSHOP_EVAL_QUESTIONS.forEach(question => {
      const forQuestion = filteredWorkshop.filter(r => r.question === question);
      const counts = countsByScale(forQuestion, 'response', WORKSHOP_SCALE);
      makeQuestionDonut(workshopRow, question, WORKSHOP_SCALE, counts);
    });
    equalizeHeadingHeights(workshopRow);
  }

  // Facilitator Workshop Report — single answer per question, list format
  const facilitatorTitle = document.createElement('div');
  facilitatorTitle.className = 'eval-section-title';
  facilitatorTitle.textContent = 'Facilitator Workshop Report';
  workshopSections.appendChild(facilitatorTitle);

  const facilitatorPanel = document.createElement('div');
  facilitatorPanel.className = 'facilitator-report-panel';
  workshopSections.appendChild(facilitatorPanel);

  const answers = FACILITATOR_REPORTS[selectedCourse] || {};
  FACILITATOR_REPORT_QUESTIONS.forEach(question => {
    const row = document.createElement('div');
    row.className = 'facilitator-row';
    row.innerHTML = `
      <div class="facilitator-question">${question}</div>
      <div class="facilitator-answer">${answers[question] || '-'}</div>
    `;
    facilitatorPanel.appendChild(row);
  });
}

function renderEvaluation() {
  chartInstances.forEach(c => c.destroy());
  chartInstances = [];
  renderCourseEvaluation();
  renderWorkshopSections();
}

window.registerFilterListener(renderEvaluation);
renderEvaluation();

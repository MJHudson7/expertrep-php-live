/**
 * performance-detail.js
 *
 * Two independent things narrow what's shown here:
 *   1. The shared Course/Team/Name/CourseEndDate filters (right panel) —
 *      narrow which underlying assessment records are in play at all.
 *   2. This page's own view toggle (By Person / By Assessment) plus a
 *      dropdown — decides how those filtered records are GROUPED into
 *      chart panels, and whether to show one specific person/assessment
 *      or all of them stacked.
 *
 * By Person, "Show All": one panel per participant, topics down the side
 *   (this is the original layout).
 * By Person, one selected: single panel for that participant.
 * By Assessment, "Show All": one panel per topic, PEOPLE down the side
 *   (axes swapped vs. By Person).
 * By Assessment, one selected: single panel for that topic.
 */

Chart.register(ChartDataLabels);

const THEME = window.APP_DATA.theme;

// This page's Course filter is single-select (see FILTER_CONFIG in
// performance-detail.php + the forceSingleSelect support in filters.js).
// Add a small note so that's clear in the UI, without touching the
// shared filters-panel.php partial used by every other page.
(function addCourseSingleSelectNote() {
  const courseListEl = document.getElementById('courseList');
  const courseBlock = courseListEl ? courseListEl.closest('.filter-block') : null;
  const head = courseBlock ? courseBlock.querySelector('.filter-block-head') : null;
  if (head) {
    const note = document.createElement('div');
    note.style.cssText = 'font-size:10.5px;color:var(--text-faint);margin:-4px 0 8px;';
    note.textContent = 'Pick one course at a time on this page';
    head.insertAdjacentElement('afterend', note);
  }
})();

const COURSE_ACTIVITIES = window.APP_DATA.courseActivities;
const ASSESSMENT_RECORDS = window.APP_DATA.assessmentRecords;
const container = document.getElementById('participantChartsContainer');
const viewSelect = document.getElementById('viewSelect');
const btnPerson = document.getElementById('viewModePerson');
const btnAssessment = document.getElementById('viewModeAssessment');

let viewMode = 'person';       // 'person' | 'assessment'
let viewSelection = 'all';     // 'all' | a specific name or topic
let chartInstances = [];

btnPerson.addEventListener('click', () => {
  if (viewMode === 'person') return;
  viewMode = 'person';
  viewSelection = 'all';
  btnPerson.classList.add('active');
  btnAssessment.classList.remove('active');
  renderPerformanceDetail();
});
btnAssessment.addEventListener('click', () => {
  if (viewMode === 'assessment') return;
  viewMode = 'assessment';
  viewSelection = 'all';
  btnAssessment.classList.add('active');
  btnPerson.classList.remove('active');
  renderPerformanceDetail();
});
viewSelect.addEventListener('change', () => {
  viewSelection = viewSelect.value;
  renderPerformanceDetail();
});

function groupByParticipantAndTopic(filteredAssessment) {
  const byName = {};
  filteredAssessment.forEach(r => {
    if (!byName[r.name]) byName[r.name] = {};
    if (!byName[r.name][r.topic]) byName[r.name][r.topic] = [];
    byName[r.name][r.topic].push(r.score);
  });
  return byName;
}

function groupByTopicAndParticipant(filteredAssessment) {
  const byTopic = {};
  filteredAssessment.forEach(r => {
    if (!byTopic[r.topic]) byTopic[r.topic] = {};
    if (!byTopic[r.topic][r.name]) byTopic[r.topic][r.name] = [];
    byTopic[r.topic][r.name].push(r.score);
  });
  return byTopic;
}

function average(arr) { return arr.reduce((a, b) => a + b, 0) / arr.length; }

/**
 * Since Course is single-select on this page, everything in
 * filteredAssessment belongs to one course. Use that course's own
 * activity list (in its defined order) rather than a flat global list.
 */
function getTopicOrder(filteredAssessment) {
  if (filteredAssessment.length === 0) return [];
  const course = filteredAssessment[0].course;
  return COURSE_ACTIVITIES[course] || [];
}

function makePanel(headingText, labels, values, canvasHeight, averagePct) {
  const panel = document.createElement('div');
  panel.className = 'chart-panel';

  const averageBlockHtml = (averagePct !== undefined && averagePct !== null) ? `
      <div class="chart-average-block" style="height:${canvasHeight}px;">
        <div class="chart-average-label">AVERAGE</div>
        <div class="chart-average-value">${averagePct.toFixed(2)}%</div>
      </div>` : '';

  panel.innerHTML = `
    <div class="chart-panel-head">${headingText}</div>
    <div class="chart-panel-body${averageBlockHtml ? ' chart-panel-body-with-average' : ''}">
      <div class="chart-panel-canvas-wrap" style="height:${canvasHeight}px;">
        <canvas></canvas>
      </div>${averageBlockHtml}
    </div>
  `;
  container.appendChild(panel);

  const canvas = panel.querySelector('canvas');
  const chart = new Chart(canvas, {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [{ data: values, backgroundColor: THEME.teal, borderRadius: 3, maxBarThickness: 24 }]
    },
    options: {
      indexAxis: 'y',
      responsive: true, maintainAspectRatio: false,
      plugins: {
        legend: { display: false },
        datalabels: {
          color: '#1E2B33', anchor: 'end', align: 'end', offset: 4,
          font: { weight: '700', size: 10.5 },
          formatter: (v) => v.toFixed(2)
        }
      },
      scales: {
        x: { grid: { color: '#E2E5E7' }, ticks: { color: '#6B7B82', font: { size: 10 } }, max: 105, beginAtZero: true },
        y: { grid: { display: false }, ticks: { color: '#1E2B33', font: { size: 10.5 } } }
      }
    }
  });
  chartInstances.push(chart);
}

function populateViewSelect(options) {
  const previousValue = viewSelection;
  viewSelect.innerHTML = '';

  const allOpt = document.createElement('option');
  allOpt.value = 'all';
  allOpt.textContent = viewMode === 'person' ? 'Show all people' : 'Show all assessments';
  viewSelect.appendChild(allOpt);

  options.forEach(opt => {
    const el = document.createElement('option');
    el.value = opt;
    el.textContent = opt;
    viewSelect.appendChild(el);
  });

  // Keep the current selection if it's still valid, otherwise fall back to "all"
  if (previousValue !== 'all' && options.includes(previousValue)) {
    viewSelect.value = previousValue;
    viewSelection = previousValue;
  } else {
    viewSelect.value = 'all';
    viewSelection = 'all';
  }
}

function renderPerformanceDetail() {
  const filteredAssessment = window.applyCurrentFilters(ASSESSMENT_RECORDS);
  const topicOrder = getTopicOrder(filteredAssessment);

  chartInstances.forEach(c => c.destroy());
  chartInstances = [];
  container.innerHTML = '';

  if (viewMode === 'person') {
    const byName = groupByParticipantAndTopic(filteredAssessment);
    const names = Object.keys(byName).sort();
    populateViewSelect(names);

    if (names.length === 0) {
      container.innerHTML = '<div class="chart-panel"><div class="chart-panel-body"><div class="tp-empty">No data for current filters</div></div></div>';
      return;
    }

    const namesToShow = viewSelection === 'all' ? names : [viewSelection];
    namesToShow.forEach(name => {
      const topics = topicOrder.filter(t => byName[name][t]);
      const scores = topics.map(t => average(byName[name][t]));
      const overallAverage = scores.length ? average(scores) : 0;
      makePanel(name, topics, scores, Math.max(180, topics.length * 34), overallAverage);
    });

  } else {
    const byTopic = groupByTopicAndParticipant(filteredAssessment);
    const topicsPresent = topicOrder.filter(t => byTopic[t]);
    populateViewSelect(topicsPresent);

    if (topicsPresent.length === 0) {
      container.innerHTML = '<div class="chart-panel"><div class="chart-panel-body"><div class="tp-empty">No data for current filters</div></div></div>';
      return;
    }

    const topicsToShow = viewSelection === 'all' ? topicsPresent : [viewSelection];
    topicsToShow.forEach(topic => {
      const names = Object.keys(byTopic[topic]).sort();
      const scores = names.map(n => average(byTopic[topic][n]));
      makePanel(topic, names, scores, Math.max(180, names.length * 26));
    });
  }
}

window.registerFilterListener(renderPerformanceDetail);
renderPerformanceDetail();

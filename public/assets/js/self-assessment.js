/**
 * self-assessment.js
 *
 * Renders a grouped horizontal bar chart (Before / After) of self-
 * assessment scores per participant, with % labels on the bars, plus two
 * summary cards showing the overall Before/After average across whatever
 * the shared filters currently include. Course is multi-select on this
 * page, so with no filters applied this aggregates across ALL courses by
 * default.
 *
 * Ordering follows the shared Sort Order toggle in the filters panel
 * (window.getSortMode(): 'name' = alphabetical, 'score' = After-average
 * descending) — re-read at render time since it can change independently
 * of the record filters.
 */

Chart.register(ChartDataLabels);

const SELF_ASSESSMENT_RECORDS = window.APP_DATA.selfAssessmentRecords;
const THEME = window.APP_DATA.theme;
const canvasWrap = document.getElementById('selfAssessmentCanvasWrap');
const beforeAverageEl = document.getElementById('beforeAverage');
const afterAverageEl = document.getElementById('afterAverage');

function average(arr) { return arr.reduce((a, b) => a + b, 0) / arr.length; }

const selfAssessmentChart = new Chart(document.getElementById('selfAssessmentChart'), {
  type: 'bar',
  data: {
    labels: [],
    datasets: [
      { label: 'Before', data: [], backgroundColor: THEME['teal-dark'], borderRadius: 3, maxBarThickness: 16 },
      { label: 'After', data: [], backgroundColor: THEME.teal, borderRadius: 3, maxBarThickness: 16 }
    ]
  },
  options: {
    indexAxis: 'y',
    responsive: true, maintainAspectRatio: false,
    plugins: {
      legend: { display: false },
      datalabels: {
        color: '#1E2B33', anchor: 'end', align: 'end', offset: 4,
        font: { weight: '700', size: 10 },
        formatter: (v) => v.toFixed(0) + '%'
      }
    },
    scales: {
      x: { grid: { color: '#E2E5E7' }, ticks: { color: '#6B7B82', font: { size: 10 } }, max: 110, beginAtZero: true },
      y: { grid: { display: false }, ticks: { color: '#2B2B2B', font: { size: 10.5 } } }
    }
  }
});

function renderSelfAssessment() {
  const filtered = window.applyCurrentFilters(SELF_ASSESSMENT_RECORDS);

  const byName = {};
  filtered.forEach(r => {
    if (!byName[r.name]) byName[r.name] = { before: [], after: [] };
    byName[r.name].before.push(r.before);
    byName[r.name].after.push(r.after);
  });

  let names = Object.keys(byName);
  const sortMode = window.getSortMode ? window.getSortMode() : 'name';
  if (sortMode === 'score') {
    names.sort((a, b) => average(byName[b].after) - average(byName[a].after));
  } else {
    names.sort();
  }

  // Set the canvas container's height BEFORE updating the chart.
  const chartHeight = Math.max(320, names.length * 30);
  canvasWrap.style.height = chartHeight + 'px';

  selfAssessmentChart.data.labels = names;
  selfAssessmentChart.data.datasets[0].data = names.map(n => average(byName[n].before));
  selfAssessmentChart.data.datasets[1].data = names.map(n => average(byName[n].after));
  selfAssessmentChart.update();

  // Align the summary cards to the bars' baseline, not the full container
  // (which also includes the x-axis tick-label row below the bars).
  // Reading Chart.js's own chartArea here is unreliable — its resize is
  // driven by a ResizeObserver that doesn't necessarily finish before this
  // code runs, so chartArea.bottom can reflect a stale size (this is why
  // it looked right for the full 24-person list but broke as soon as the
  // dataset was filtered to a different row count). A fixed offset for
  // the axis-label row avoids that timing problem entirely.
  const AXIS_LABEL_ROW_HEIGHT = 30;
  document.getElementById('selfAssessmentSummary').style.height = Math.max(0, chartHeight - AXIS_LABEL_ROW_HEIGHT) + 'px';

  if (filtered.length === 0) {
    beforeAverageEl.textContent = '-';
    afterAverageEl.textContent = '-';
  } else {
    beforeAverageEl.textContent = Math.round(average(filtered.map(r => r.before))) + '%';
    afterAverageEl.textContent = Math.round(average(filtered.map(r => r.after))) + '%';
  }
}

window.registerFilterListener(renderSelfAssessment);
renderSelfAssessment();

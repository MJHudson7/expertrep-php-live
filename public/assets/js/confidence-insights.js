/**
 * confidence-insights.js
 *
 * Renders a single grouped horizontal bar chart (Before / After) of the
 * 8 confidence statements, averaged across whatever the shared filters
 * currently include. Course is multi-select on this page (not restricted
 * like Performance Detail), so with no filters applied this naturally
 * aggregates across ALL courses — matching the requirement that the
 * chart show combined data by default, not just the first course.
 */

Chart.register(ChartDataLabels);

const CONFIDENCE_STATEMENTS = window.APP_DATA.confidenceStatements;
const CONFIDENCE_RECORDS = window.APP_DATA.confidenceRecords;
const THEME = window.APP_DATA.theme;
const canvasWrap = document.getElementById('confidenceCanvasWrap');

function average(arr) { return arr.reduce((a, b) => a + b, 0) / arr.length; }

const confidenceChart = new Chart(document.getElementById('confidenceChart'), {
  type: 'bar',
  data: {
    labels: CONFIDENCE_STATEMENTS,
    datasets: [
      { label: 'Before', data: [], backgroundColor: THEME['teal-dark'], borderRadius: 3, maxBarThickness: 16 },
      { label: 'After', data: [], backgroundColor: THEME.teal, borderRadius: 3, maxBarThickness: 16 }
    ]
  },
  options: {
    indexAxis: 'y',
    responsive: true, maintainAspectRatio: false,
    plugins: {
      legend: { position: 'bottom', labels: { color: '#2B2B2B', font: { size: 11.5 }, boxWidth: 14 } },
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

function renderConfidenceInsights() {
  const filtered = window.applyCurrentFilters(CONFIDENCE_RECORDS);

  const byStatement = {};
  filtered.forEach(r => {
    if (!byStatement[r.statement]) byStatement[r.statement] = { before: [], after: [] };
    byStatement[r.statement].before.push(r.before);
    byStatement[r.statement].after.push(r.after);
  });

  const beforeData = CONFIDENCE_STATEMENTS.map(s => byStatement[s] ? average(byStatement[s].before) : 0);
  const afterData = CONFIDENCE_STATEMENTS.map(s => byStatement[s] ? average(byStatement[s].after) : 0);

  confidenceChart.data.datasets[0].data = beforeData;
  confidenceChart.data.datasets[1].data = afterData;
  confidenceChart.update();

  canvasWrap.style.height = Math.max(320, CONFIDENCE_STATEMENTS.length * 55) + 'px';
}

window.registerFilterListener(renderConfidenceInsights);
renderConfidenceInsights();

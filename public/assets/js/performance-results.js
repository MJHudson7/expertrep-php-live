/**
 * performance-results.js
 *
 * Renders the two charts on the Performance Results page:
 *   - "Activities passed by Participant" (line chart)
 *   - "Grade % by Participant" (bar chart)
 * Both aggregate the currently-filtered records per participant name.
 * Registers itself with filters.js so it re-renders on any filter change.
 */

Chart.register(ChartDataLabels);

const THEME = window.APP_DATA.theme;

const lineChart = new Chart(document.getElementById('activitiesLineChart'), {
  type: 'line',
  data: { labels: [], datasets: [{
    data: [],
    borderColor: THEME.teal,
    backgroundColor: THEME.teal,
    pointBackgroundColor: THEME.teal,
    pointRadius: 4,
    borderWidth: 2,
    tension: 0.15
  }] },
  options: {
    responsive: true, maintainAspectRatio: false,
    plugins: {
      legend: { display: false },
      datalabels: { color: '#1E2B33', anchor: 'end', align: 'top', font: { weight: '700', size: 11 } }
    },
    scales: {
      x: { grid: { display: false }, ticks: { color: '#6B7B82', font: { size: 10 } } },
      y: { grid: { color: '#E2E5E7' }, ticks: { color: '#6B7B82', font: { size: 10 } }, beginAtZero: true }
    }
  }
});

const barChart = new Chart(document.getElementById('gradeBarChart'), {
  type: 'bar',
  data: { labels: [], datasets: [{
    data: [],
    backgroundColor: THEME.teal,
    borderRadius: 3,
    maxBarThickness: 70
  }] },
  options: {
    responsive: true, maintainAspectRatio: false,
    plugins: {
      legend: { display: false },
      datalabels: { color: '#1E2B33', anchor: 'end', align: 'top', font: { weight: '700', size: 11 },
        formatter: (v) => v.toFixed(2) }
    },
    scales: {
      x: { grid: { display: false }, ticks: { color: '#6B7B82', font: { size: 10 } } },
      y: { grid: { color: '#E2E5E7' }, ticks: { color: '#6B7B82', font: { size: 10 } }, beginAtZero: true, max: 100 }
    }
  }
});

function computeParticipantStats(filtered) {
  const byName = {};
  filtered.forEach(r => {
    if (!byName[r.name]) byName[r.name] = { name: r.name, grades: [], passedCount: 0 };
    byName[r.name].grades.push(r.grade);
    if (r.activity_passed) byName[r.name].passedCount += 1;
  });
  return Object.values(byName).sort((a, b) => a.name.localeCompare(b.name));
}

function renderPerformanceResults(filtered) {
  const stats = computeParticipantStats(filtered);
  const labels = stats.map(s => s.name);

  lineChart.data.labels = labels;
  lineChart.data.datasets[0].data = stats.map(s => s.passedCount);
  lineChart.update();

  barChart.data.labels = labels;
  barChart.data.datasets[0].data = stats.map(s => s.grades.reduce((a, b) => a + b, 0) / s.grades.length);
  barChart.update();
}

window.registerFilterListener(renderPerformanceResults);
renderPerformanceResults(window.getFilteredRecords());

/**
 * home.js
 *
 * Renders the Home/Overview page's KPI cards, Top 3 Performers table, and
 * three donut charts. Registers itself with filters.js so it re-renders
 * whenever any filter changes.
 */

const THEME = window.APP_DATA.theme;
const ACTIVITIES_COLORS = [THEME['teal-dark'], THEME['teal-pale']];
const KNOWLEDGE_COLORS = [THEME['teal-dark'], THEME['teal-mid'], THEME['teal-light'], THEME['teal-pale']];
const CONFIDENCE_COLORS = [THEME['teal-dark'], THEME['teal-mid'], THEME['teal-light'], THEME['teal-pale'], '#B9C3C5'];

const donutOpts = {
  responsive: true, maintainAspectRatio: false,
  plugins: { legend: { display: false }, tooltip: { enabled: true } },
  cutout: '52%'
};
const activitiesChart = new Chart(document.getElementById('donutActivities'), {
  type: 'doughnut',
  data: { labels: ['Passed', 'Remaining'], datasets: [{ data: [0, 0], backgroundColor: ACTIVITIES_COLORS, borderWidth: 0 }] },
  options: donutOpts
});
const knowledgeChart = new Chart(document.getElementById('donutKnowledge'), {
  type: 'doughnut',
  data: { labels: ['Excellent', 'Good', 'Fair', 'Poor'], datasets: [{ data: [0, 0, 0, 0], backgroundColor: KNOWLEDGE_COLORS, borderWidth: 0 }] },
  options: donutOpts
});
const confidenceChart = new Chart(document.getElementById('donutConfidence'), {
  type: 'doughnut',
  data: { labels: ['Excellent', 'Good', 'Fair', 'Poor', 'Not Relevant'], datasets: [{ data: [0, 0, 0, 0, 0], backgroundColor: CONFIDENCE_COLORS, borderWidth: 0 }] },
  options: donutOpts
});

function renderLegend(containerId, labels, colors, values) {
  const el = document.getElementById(containerId);
  el.innerHTML = '';
  labels.forEach((l, i) => {
    const total = values.reduce((a, b) => a + b, 0) || 1;
    const pct = Math.round((values[i] / total) * 100);
    const item = document.createElement('div');
    item.className = 'item';
    item.innerHTML = `<span class="sw" style="background:${colors[i]}"></span>${l} ${pct}%`;
    el.appendChild(item);
  });
}

function renderHome(filtered) {
  // KPIs
  const avgGrade = filtered.length ? (filtered.reduce((a, r) => a + r.grade, 0) / filtered.length) : 0;
  const badges = filtered.filter(r => r.badge).length;
  document.getElementById('kpiAvgGrade').textContent = filtered.length ? avgGrade.toFixed(2) + '%' : '-';
  document.getElementById('kpiBadges').textContent = filtered.length ? badges : '-';

  // Top 3 performers
  const byName = {};
  filtered.forEach(r => {
    if (!byName[r.name]) byName[r.name] = { name: r.name, grades: [], courses: new Set() };
    byName[r.name].grades.push(r.grade);
    byName[r.name].courses.add(r.course);
  });
  const ranked = Object.values(byName).map(p => ({
    name: p.name,
    courseCount: p.courses.size,
    avg: p.grades.reduce((a, b) => a + b, 0) / p.grades.length
  })).sort((a, b) => b.avg - a.avg).slice(0, 3);

  const tpBody = document.getElementById('tpBody');
  tpBody.innerHTML = '';
  if (ranked.length === 0) {
    tpBody.innerHTML = '<div class="tp-empty">No data for current filters</div>';
  } else {
    ranked.forEach((p, i) => {
      const row = document.createElement('div');
      row.className = 'tp-row';
      row.innerHTML = `
        <div class="tp-rank">${i + 1}</div>
        <div class="tp-name">${p.name}</div>
        <div class="tp-courses">${p.courseCount}</div>
        <div class="tp-pct">${p.avg.toFixed(2)}%</div>
      `;
      tpBody.appendChild(row);
    });
  }

  // Activities donut
  const passed = filtered.filter(r => r.activity_passed).length;
  const remaining = filtered.length - passed;
  activitiesChart.data.datasets[0].data = [passed, remaining];
  activitiesChart.update();
  renderLegend('legendActivities', ['Passed', 'Remaining'], ACTIVITIES_COLORS, [passed, remaining]);

  // Knowledge donut
  const kCounts = ['Excellent', 'Good', 'Fair', 'Poor'].map(cat => filtered.filter(r => r.knowledge === cat).length);
  knowledgeChart.data.datasets[0].data = kCounts;
  knowledgeChart.update();
  renderLegend('legendKnowledge', ['Excellent', 'Good', 'Fair', 'Poor'], KNOWLEDGE_COLORS, kCounts);

  // Confidence donut
  const cCounts = ['Excellent', 'Good', 'Fair', 'Poor', 'Not Relevant'].map(cat => filtered.filter(r => r.confidence === cat).length);
  confidenceChart.data.datasets[0].data = cCounts;
  confidenceChart.update();
  renderLegend('legendConfidence', ['Excellent', 'Good', 'Fair', 'Poor', 'Not Relevant'], CONFIDENCE_COLORS, cCounts);
}

window.registerFilterListener(renderHome);
renderHome(window.getFilteredRecords());

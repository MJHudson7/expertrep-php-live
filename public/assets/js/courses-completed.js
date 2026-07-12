/**
 * courses-completed.js
 *
 * Renders one card per course (badges earned + top 3 performers, scoped to
 * that course only). All 6 course cards are pre-rendered in the page
 * markup with fixed IDs; this script fills in their values and hides any
 * card whose course has zero matching records under the current filters
 * (e.g. narrowed out entirely by the Course, Team, Name, or date filter).
 */

// COURSES is already declared by filters.js (loaded before this script),
// so it's reused here as-is rather than redeclared.

function average(arr) { return arr.reduce((a, b) => a + b, 0) / arr.length; }

function renderCoursesCompleted(filtered) {
  COURSES.forEach((course, i) => {
    const card = document.querySelector(`.course-card[data-course="${CSS.escape(course)}"]`);
    if (!card) return;

    const courseRecords = filtered.filter(r => r.course === course);

    if (courseRecords.length === 0) {
      card.style.display = 'none';
      return;
    }
    card.style.display = '';

    const badges = courseRecords.filter(r => r.badge).length;
    document.getElementById(`badgesValue-${i}`).textContent = badges;

    const byName = {};
    courseRecords.forEach(r => {
      if (!byName[r.name]) byName[r.name] = [];
      byName[r.name].push(r.grade);
    });
    const ranked = Object.entries(byName)
      .map(([name, grades]) => ({ name, avg: average(grades) }))
      .sort((a, b) => b.avg - a.avg)
      .slice(0, 3);

    const tpBody = document.getElementById(`tpBody-${i}`);
    tpBody.innerHTML = '';
    if (ranked.length === 0) {
      tpBody.innerHTML = '<div class="tp-empty">No data for current filters</div>';
      return;
    }
    ranked.forEach((p, idx) => {
      const row = document.createElement('div');
      row.className = 'tp-row';
      row.innerHTML = `
        <div class="tp-rank">${idx + 1}</div>
        <div class="tp-name">${p.name}</div>
        <div class="tp-pct">${p.avg.toFixed(2)}%</div>
      `;
      tpBody.appendChild(row);
    });
  });
}

window.registerFilterListener(renderCoursesCompleted);
renderCoursesCompleted(window.getFilteredRecords());

/**
 * filters.js
 *
 * Shared across every page. Expects window.APP_DATA to already be set by
 * the page's inline <script> before this file loads:
 *   { records, courses, teams, names, roles, months, monthYears }
 *
 * Other page-specific scripts (home.js, performance-results.js, etc.)
 * call window.registerFilterListener(fn) to be notified whenever a filter
 * OR the sort order changes. fn receives the currently-filtered records
 * array. Each page script is responsible for calling its own render
 * function once on load (after registering) — filters.js itself doesn't
 * auto-render on load, only on actual filter/sort changes, so there's no
 * dependency on script loading order beyond "filters.js loads before the
 * page script".
 *
 * Filter pill interaction (Course / Team / Role / Name):
 *   - Plain click   -> selects ONLY that item, deselects everything else
 *   - Cmd+click (Mac) / Ctrl+click (Windows) -> adds/removes that item
 *     from the current selection, leaving the rest as-is
 *   - "reset" link  -> back to "all selected"
 *
 * Hierarchical / cross-filtering (Course, Team, Role, Name):
 *   Whenever any of these four filters changes, the other three are
 *   re-evaluated: any pill with zero matching records given the CURRENT
 *   EXPLICIT selections of the other filters (plus the date range) is
 *   hidden. This only hides/shows pills — it never rewrites the
 *   selection sets themselves. Only an actual click (or reset) changes
 *   what's selected.
 *
 * Sort Order (Name A-Z / Highest to Lowest):
 *   A page-agnostic toggle, independent of the record filters above.
 *   window.getSortMode() returns 'name' or 'score'. Pages that render a
 *   ranked list of people (Performance Results, Performance Detail, Self
 *   Assessment) read this at render time to decide whether to order by
 *   name or by score descending. Changing it fires the same
 *   registerFilterListener callbacks as a normal filter change.
 *
 * Optional per-page override (window.FILTER_CONFIG):
 *   A page can set, BEFORE this script loads:
 *     window.FILTER_CONFIG = { courseSingleSelectOnly: true, defaultCourse: 'Some Course' };
 *   When courseSingleSelectOnly is true, the Course filter ignores
 *   Cmd/Ctrl+click (always exclusive-select, one course at a time), starts
 *   pre-selected to defaultCourse instead of "all", and its reset link
 *   goes back to defaultCourse instead of "all". Team, Role, and Name are
 *   unaffected. Pages that don't set this behave exactly as before
 *   (Course multi-select, defaults to "all").
 */

const { records, courses: COURSES, teams: TEAMS, roles: ROLES, names: NAMES, months: MONTHS, monthYears: MONTH_YEARS } = window.APP_DATA;
const FILTER_CONFIG = window.FILTER_CONFIG || {};
const COURSE_SINGLE_SELECT_ONLY = FILTER_CONFIG.courseSingleSelectOnly === true;
const DEFAULT_COURSE = FILTER_CONFIG.defaultCourse || COURSES[0];

/* ============ FILTER STATE ============ */
let rangeMin = 0, rangeMax = 10;
const selectedCourses = new Set(COURSE_SINGLE_SELECT_ONLY ? [DEFAULT_COURSE] : COURSES);
const selectedTeams = new Set(TEAMS);
const selectedRoles = new Set(ROLES);
const selectedNames = new Set(NAMES);
let sortMode = 'name'; // 'name' | 'score'

/* ============ LISTENER SYSTEM ============ */
const filterListeners = [];
window.registerFilterListener = function (fn) {
  filterListeners.push(fn);
};
window.getFilteredRecords = function () {
  return records.filter(r =>
    r.month_index >= rangeMin && r.month_index <= rangeMax &&
    selectedCourses.has(r.course) &&
    selectedTeams.has(r.team) &&
    selectedRoles.has(r.role) &&
    selectedNames.has(r.name)
  );
};
/**
 * Applies the SAME current filter state (date range, course/team/role/
 * name selection) to any array of objects that share the month_index/
 * course/team/role/name fields — e.g. a page's own assessment_records
 * dataset, which isn't part of window.APP_DATA.records. This lets a page
 * reuse the shared filter UI without filters.js needing to know that
 * dataset exists.
 */
window.applyCurrentFilters = function (arr) {
  return arr.filter(r =>
    r.month_index >= rangeMin && r.month_index <= rangeMax &&
    selectedCourses.has(r.course) &&
    selectedTeams.has(r.team) &&
    selectedRoles.has(r.role) &&
    selectedNames.has(r.name)
  );
};
/**
 * Returns the single currently-selected course name, or null if more
 * than one (or zero) courses are selected. Useful on pages where Course
 * is single-select — lets a page know which course is "current" even
 * when other filters (Team/Role/Name/Date) have emptied the filtered
 * results, so course-level logic isn't blocked by an empty record set.
 */
window.getSelectedCourse = function () {
  return selectedCourses.size === 1 ? [...selectedCourses][0] : null;
};
/**
 * 'name' (alphabetical, default) or 'score' (highest to lowest). Pages
 * that rank people by a score should read this at render time rather
 * than caching it, since it can change independently of the filters.
 */
window.getSortMode = function () {
  return sortMode;
};
function notifyListeners() {
  const filtered = window.getFilteredRecords();
  filterListeners.forEach(fn => fn(filtered));
}

/**
 * Builds a pill-filter list with exclusive-click / cmd-or-ctrl-click-toggle
 * behavior. Returns the created pill elements (same order as `items`).
 * If forceSingleSelect is true, every click is treated as a plain
 * exclusive-select click regardless of modifier keys.
 */
function setupPillFilter(container, items, selectedSet, onChange, forceSingleSelect) {
  const pillEls = [];

  items.forEach(item => {
    const el = document.createElement('div');
    el.className = 'pill';
    el.textContent = item;

    el.addEventListener('click', (event) => {
      const isMultiSelectClick = !forceSingleSelect && (event.metaKey || event.ctrlKey);

      if (isMultiSelectClick) {
        if (selectedSet.has(item)) { selectedSet.delete(item); }
        else { selectedSet.add(item); }
        if (selectedSet.size === 0) { items.forEach(i => selectedSet.add(i)); }
      } else {
        selectedSet.clear();
        selectedSet.add(item);
      }

      onChange();
    });

    pillEls.push(el);
    container.appendChild(el);
  });

  return pillEls;
}

function syncPillClasses(pillEls, items, selectedSet) {
  pillEls.forEach((el, i) => {
    el.classList.toggle('off', !selectedSet.has(items[i]));
  });
}

/* ============ BUILD FILTER UI ============ */
const courseList = document.getElementById('courseList');
const coursePills = setupPillFilter(courseList, COURSES, selectedCourses, onFilterChanged, COURSE_SINGLE_SELECT_ONLY);

const teamList = document.getElementById('teamList');
const teamPills = setupPillFilter(teamList, TEAMS, selectedTeams, onFilterChanged, false);

const roleList = document.getElementById('roleList');
const rolePills = setupPillFilter(roleList, ROLES, selectedRoles, onFilterChanged, false);

const nameListEl = document.getElementById('nameList');
const namePills = setupPillFilter(nameListEl, NAMES, selectedNames, onFilterChanged, false);

document.querySelectorAll('.clear').forEach(btn => {
  btn.addEventListener('click', () => {
    const kind = btn.dataset.clear;
    if (kind === 'course') {
      selectedCourses.clear();
      if (COURSE_SINGLE_SELECT_ONLY) { selectedCourses.add(DEFAULT_COURSE); }
      else { COURSES.forEach(c => selectedCourses.add(c)); }
    }
    if (kind === 'team') { selectedTeams.clear(); TEAMS.forEach(t => selectedTeams.add(t)); }
    if (kind === 'role') { selectedRoles.clear(); ROLES.forEach(r => selectedRoles.add(r)); }
    if (kind === 'name') { selectedNames.clear(); NAMES.forEach(n => selectedNames.add(n)); }
    onFilterChanged();
  });
});

/* ============ SORT ORDER TOGGLE ============ */
const sortModeNameBtn = document.getElementById('sortModeName');
const sortModeScoreBtn = document.getElementById('sortModeScore');
if (sortModeNameBtn && sortModeScoreBtn) {
  sortModeNameBtn.addEventListener('click', () => {
    if (sortMode === 'name') return;
    sortMode = 'name';
    sortModeNameBtn.classList.remove('off');
    sortModeScoreBtn.classList.add('off');
    notifyListeners();
  });
  sortModeScoreBtn.addEventListener('click', () => {
    if (sortMode === 'score') return;
    sortMode = 'score';
    sortModeScoreBtn.classList.remove('off');
    sortModeNameBtn.classList.add('off');
    notifyListeners();
  });
}

/* ============ HIERARCHICAL CROSS-FILTERING (visibility only) ============ */

function getAvailableValues(dimension) {
  const available = new Set();
  records.forEach(r => {
    const dateOk = r.month_index >= rangeMin && r.month_index <= rangeMax;
    const courseOk = dimension === 'course' || selectedCourses.has(r.course);
    const teamOk = dimension === 'team' || selectedTeams.has(r.team);
    const roleOk = dimension === 'role' || selectedRoles.has(r.role);
    const nameOk = dimension === 'name' || selectedNames.has(r.name);
    if (dateOk && courseOk && teamOk && roleOk && nameOk) {
      available.add(r[dimension]);
    }
  });
  return available;
}

function refreshFilterAvailability() {
  const availableCourses = getAvailableValues('course');
  const availableTeams = getAvailableValues('team');
  const availableRoles = getAvailableValues('role');
  const availableNames = getAvailableValues('name');

  coursePills.forEach((el, i) => { el.style.display = availableCourses.has(COURSES[i]) ? '' : 'none'; });
  teamPills.forEach((el, i) => { el.style.display = availableTeams.has(TEAMS[i]) ? '' : 'none'; });
  rolePills.forEach((el, i) => { el.style.display = availableRoles.has(ROLES[i]) ? '' : 'none'; });
  namePills.forEach((el, i) => { el.style.display = availableNames.has(NAMES[i]) ? '' : 'none'; });

  syncPillClasses(coursePills, COURSES, selectedCourses);
  syncPillClasses(teamPills, TEAMS, selectedTeams);
  syncPillClasses(rolePills, ROLES, selectedRoles);
  syncPillClasses(namePills, NAMES, selectedNames);
}

function onFilterChanged() {
  refreshFilterAvailability();
  notifyListeners();
}

/* Date range slider */
const rMin = document.getElementById('rangeMin');
const rMax = document.getElementById('rangeMax');
const sliderFill = document.getElementById('sliderFill');
const periodText = document.getElementById('periodText');

function updateSlider() {
  let a = parseInt(rMin.value), b = parseInt(rMax.value);
  if (a > b) { [a, b] = [b, a]; }
  rangeMin = a; rangeMax = b;
  const pctA = (a / 10) * 100, pctB = (b / 10) * 100;
  sliderFill.style.left = pctA + '%';
  sliderFill.style.width = (pctB - pctA) + '%';
  periodText.textContent = (a === 0 && b === 10) ? 'All Periods' : `${MONTHS[a]} ${MONTH_YEARS[a]} - ${MONTHS[b]} ${MONTH_YEARS[b]}`;
}
rMin.addEventListener('input', () => { updateSlider(); onFilterChanged(); });
rMax.addEventListener('input', () => { updateSlider(); onFilterChanged(); });
document.getElementById('clearDate').addEventListener('click', () => {
  rMin.value = 0; rMax.value = 10; updateSlider(); onFilterChanged();
});
updateSlider();

refreshFilterAvailability();

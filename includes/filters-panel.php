  <!-- FILTERS -->
  <aside class="filters">
    <div class="filters-title-row">
      <div class="filters-title">Filters</div>
      <div class="guide-icon-row">
        <button type="button" class="guide-icon-btn" data-guide="dashboard">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 17v.01M12 14c0-2.5 2.5-2 2.5-4.5A2.5 2.5 0 0 0 12 7a2.5 2.5 0 0 0-2.5 2.5"/></svg>
          Dashboard Explained
        </button>
        <button type="button" class="guide-icon-btn" data-guide="filters">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 17v.01M12 14c0-2.5 2.5-2 2.5-4.5A2.5 2.5 0 0 0 12 7a2.5 2.5 0 0 0-2.5 2.5"/></svg>
          Using the Filters
        </button>
      </div>
    </div>

    <!-- CourseEndDate -->
    <div class="filter-block">
      <div class="filter-block-head">
        Time Period
        <span class="clear" id="clearDate">reset</span>
      </div>
      <div class="period-label">
        <span class="all-periods" id="periodText">All Periods</span>
        <span class="months-tag">MONTHS</span>
      </div>
      <div class="year-row"><span>2025</span><span>2026</span></div>
      <div class="slider-track-wrap">
        <div class="slider-bg"></div>
        <div class="slider-fill" id="sliderFill"></div>
        <input type="range" id="rangeMin" min="0" max="10" value="0" step="1">
        <input type="range" id="rangeMax" min="0" max="10" value="10" step="1">
      </div>
      <div class="month-ticks">
        <span>AUG</span><span>SEP</span><span>OCT</span><span>NOV</span><span>DEC</span><span>JAN</span><span>FEB</span><span>MAR</span><span>APR</span><span>MAY</span><span>JUN</span>
      </div>
    </div>

    <!-- Role -->
    <div class="filter-block">
      <div class="filter-block-head">Role <span class="clear" data-clear="role">reset</span></div>
      <div class="grid-2" id="roleList"></div>
    </div>

    <!-- Course -->
    <div class="filter-block">
      <div class="filter-block-head">Course <span class="clear" data-clear="course">reset</span></div>
      <div class="pill-list" id="courseList"></div>
    </div>

    <!-- Team -->
    <div class="filter-block">
      <div class="filter-block-head">Team <span class="clear" data-clear="team">reset</span></div>
      <div class="grid-2" id="teamList"></div>
    </div>

    <!-- Name -->
    <div class="filter-block">
      <div class="filter-block-head">Team Member <span class="clear" data-clear="name">reset</span></div>
      <div class="grid-3" id="nameList"></div>
    </div>

    <!-- Sort Order -->
    <div class="filter-block">
      <div class="filter-block-head">Sort Order</div>
      <div class="pill-list" style="flex-direction:row; gap:6px;">
        <div class="pill" id="sortModeName" style="flex:1; text-align:center;">Name (A-Z)</div>
        <div class="pill off" id="sortModeScore" style="flex:1; text-align:center;">Highest to Lowest</div>
      </div>
    </div>
  </aside>

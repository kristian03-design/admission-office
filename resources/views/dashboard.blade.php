<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>BTECH — Admissions Admin Dashboard</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="icon" type="image/png" href="{{ asset('assets/images/logo_v2.png') }}" style="border-radius:50%;width:32px;height:32px;"/>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,300&family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400;1,600&display=swap" rel="stylesheet" />
  @include('partials.iconsax')
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}?v=8" />
  <script src="{{ asset('js/api-config.js') }}?v=7"></script>
  <script src="{{ asset('js/admission-api.js') }}?v=13"></script>
  <script>
    window.ADMIN_LOGIN_URL = "{{ route('admin.login') }}";
    window.ADMISSION_API_BASE = "{{ url('/api') }}";
    sessionStorage.setItem('_at', "{{ $admissionApiToken ?? session('admission_api_token') ?? '' }}");
  </script>
  <script src="{{ asset('js/admin-dashboard.js') }}?v=35" defer></script>
</head>
<body>
  @include('partials.site-loader')

<!-- â•â•â• SIDEBAR â•â•â• -->
<aside class="sidebar" id="sidebar">
  <div class="sidebar-brand">
    <a href="#" class="brand-logo">
      <img src="{{ asset('assets/images/logo.jpg') }}" alt="BTECH" />
    </a>
    <div style="flex:1;min-width:0">
      <span class="brand-name">BTECH</span>
      <span class="brand-sub">Admissions Admin</span>
    </div>
    <button class="sidebar-close-btn" id="sidebarCloseBtn">
      <i data-iconsax="x"></i>
    </button>
  </div>

  <nav class="sidebar-nav">
    <p class="nav-section-label">Main</p>
    <a class="nav-item active" data-page="dashboard">
      <i data-iconsax="layout-grid"></i>
      <span>Dashboard</span>
    </a>
    <a class="nav-item" data-page="applications">
      <i data-iconsax="file-text"></i>
      <span>Applications</span>
      <span class="nav-badge" id="pendingBadge">0</span>
    </a>
    <a class="nav-item" data-page="interviews">
      <i data-iconsax="calendar"></i>
      <span>Interview Schedule</span>
    </a>
    <a class="nav-item" data-page="programs">
      <i data-iconsax="book-open"></i>
      <span>Programs</span>
    </a>
    <p class="nav-section-label">Website</p>
    <a class="nav-item" data-page="website-content">
      <i data-iconsax="globe"></i>
      <span>Website Content</span>
    </a>
    <p class="nav-section-label">Analytics</p>
    <a class="nav-item" data-page="reports">
      <i data-iconsax="bar-chart-3"></i>
      <span>Reports</span>
    </a>
    <p class="nav-section-label">System</p>
    <a class="nav-item" data-page="settings">
      <i data-iconsax="settings"></i>
      <span>Settings</span>
    </a>
  </nav>

  <div class="sidebar-footer">
    <div class="admin-profile">
      <div class="admin-avatar" id="sidebarUserInitials">—</div>
      <div style="flex:1;min-width:0">
        <span class="admin-name" id="sidebarUserName">—</span>
        <span class="admin-role" id="sidebarUserRole">—</span>
      </div>
      <button class="admin-logout" id="logoutBtn" title="Sign out">
        <i data-iconsax="log-out"></i>
      </button>
    </div>
  </div>
</aside>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- â•â•â• MAIN WRAP â•â•â• -->
<div class="main-wrap">

  <!-- TOPBAR -->
  <header class="topbar">
    <div class="topbar-left">
      <button class="menu-toggle" id="menuToggle">
        <i data-iconsax="menu"></i>
      </button>
      <div class="page-breadcrumb">
        <span class="breadcrumb-school">BTECH</span>
        <span class="breadcrumb-sep">/</span>
        <span class="breadcrumb-page" id="breadcrumbPage">Dashboard</span>
      </div>
    </div>
    <div class="topbar-right">
      <div class="topbar-school-year">
        <i data-iconsax="calendar-range" style="width:14px;height:14px"></i>
        <span id="topbarSY">S.Y. {{ date('Y') }}–{{ date('Y') + 1 }}</span>
      </div>
      <button class="topbar-btn" id="notifBtn">
        <i data-iconsax="bell"></i>
        <span class="notif-dot" id="notifDot"></span>
      </button>
      <div class="topbar-admin">
        <div class="topbar-avatar" id="topbarUserInitials">—</div>
        <span class="topbar-admin-name" id="topbarUserName">—</span>
      </div>
    </div>
  </header>

  <!-- â•â•â• NOTIFICATION PANEL â•â•â• -->
  <div class="notif-overlay" id="notifOverlay"></div>
  <div class="notif-panel" id="notifPanel">
    <div class="notif-header">
      <div class="notif-title">Notifications</div>
      <button class="notif-close" id="notifClose">
        <i data-iconsax="x"></i>
      </button>
    </div>
    <div class="notif-body" id="notifBody">
      <div class="notif-empty">
        <i data-iconsax="bell-off"></i>
        <p>No new notifications</p>
      </div>
    </div>
    <div class="notif-footer">
      <button class="notif-btn" id="markReadBtn" style="display:none;">Mark all as read</button>
      <button class="notif-btn notif-btn--clear" id="clearNotifBtn" style="display:none;">Clear all</button>
    </div>
  </div>

  <!-- ─── DASHBOARD ─── -->
  <div class="page-content active" id="page-dashboard">
    <div class="page-header">
      <div>
        <h1 class="page-title">Good morning, Admin!</h1>
        <p class="page-sub">Here's what's happening with BTECH admissions today.</p>
      </div>
      <div class="page-actions">
        <span class="deadline-chip">
          <i data-iconsax="clock" style="width:13px;height:13px"></i>
          Deadline: <span id="deadline"></span>
        </span>
      </div>
    </div>

    <div class="kpi-grid" id="kpiGrid"></div>

    <div class="charts-row">
      <div class="chart-card">
        <div class="chart-card-header">
          <div>
            <div class="chart-title">Application Trend</div>
            <div class="chart-sub">Monthly submissions — <span id="chartSY">{{ date('Y') }}–{{ date('Y') + 1 }}</span></div>
          </div>
        </div>
        <div class="chart-body"><canvas id="trendChart"></canvas></div>
      </div>
      <div class="chart-card">
        <div class="chart-card-header">
          <div>
            <div class="chart-title">By Applicant Type</div>
            <div class="chart-sub">Distribution breakdown</div>
          </div>
        </div>
        <div class="chart-body chart-body--donut"><canvas id="typeChart"></canvas></div>
        <div class="donut-legend" id="donutLegend"></div>
      </div>
    </div>

    <div class="bottom-row">
      <div class="chart-card">
        <div class="chart-card-header">
          <div>
            <div class="chart-title">Top Programs</div>
            <div class="chart-sub">By application count</div>
          </div>
        </div>
        <div class="chart-body"><canvas id="programChart"></canvas></div>
      </div>
      <div class="recent-card">
        <div class="chart-card-header">
          <div>
            <div class="chart-title">Recent Applications</div>
          </div>
          <button class="link-btn" id="viewAllBtn">
            View all
            <i data-iconsax="arrow-right"></i>
          </button>
        </div>
        <div class="recent-list" id="recentList"></div>
      </div>
    </div>
  </div>

  <!-- ─── APPLICATIONS ─── -->
  <div class="page-content" id="page-applications">
    <div class="page-header">
      <div>
        <h1 class="page-title">Applications</h1>
        <p class="page-sub">Manage and review all admission applications</p>
      </div>
      <div class="page-actions">
        <button class="btn-outline" id="exportCsvBtn">
          <i data-iconsax="download"></i>
          Export CSV
        </button>
      </div>
    </div>

    <div class="filter-bar">
      <div class="search-wrap">
        <span class="search-icon">
          <i data-iconsax="search"></i>
        </span>
        <input type="text" id="searchInput" placeholder="Search name or reference”¦" class="search-input" />
      </div>
      <select class="filter-select" id="filterType">
        <option value="">All Types</option>
        <option>Freshmen</option>
        <option>Transferee</option>
        <option>ALS Graduate</option>
        <option>Returnee</option>
      </select>
      <select class="filter-select" id="filterProgram">
        <option value="">All Programs</option>
      </select>
      <select class="filter-select" id="filterStatus">
        <option value="">All Status</option>
        <option>Pending</option>
        <option>Interview Scheduled</option>
        <option>Approved</option>
        <option>Rejected</option>
      </select>
      <button class="btn-ghost" id="clearFilters">Clear</button>
    </div>

    <div class="table-card">
      <div class="table-wrap">
        <table class="app-table applications-table">
          <thead>
            <tr>
              <th>Reference No.</th>
              <th>Full Name</th>
              <th>Type</th>
              <th>1st Choice Program</th>
              <th>GWA Avg.</th>
              <th>Date Filed</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="appTableBody"></tbody>
        </table>
      </div>
      <div class="table-footer">
        <span class="table-info" id="tableInfo"></span>
        <div class="pagination" id="pagination"></div>
      </div>
    </div>
  </div>

  <!-- ─── INTERVIEW SCHEDULE ─── -->
  <div class="page-content" id="page-interviews">
    <div class="page-header">
      <div>
        <h1 class="page-title">Interview Scheduling</h1>
        <p class="page-sub">Set and manage interview dates for each course</p>
      </div>
    </div>

    <div class="filter-pills-row" id="interviewFilterPills">
      <button class="filter-pill active" data-category="All">All Programs</button>
      <button class="filter-pill" data-category="Technology">Technology</button>
      <button class="filter-pill" data-category="Business">Business</button>
      <button class="filter-pill" data-category="Education">Education</button>
      <button class="filter-pill" data-category="Hospitality">Hospitality</button>
      <button class="filter-pill" data-category="Accountancy">Accountancy</button>
      <button class="filter-pill" data-category="Arts & Sciences">Arts & Sciences</button>
    </div>

    <div class="table-card">
      <div class="table-wrap">
        <table class="app-table">
          <thead>
            <tr>
              <th>Course / Program</th>
              <th>Department</th>
              <th>Interview Schedule</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="interviewTableBody"></tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- ─── STUDENT SCHEDULING (SUB-PAGE) ─── -->
  <div class="page-content" id="page-student-scheduling">
    <div class="page-header">
      <div style="flex:1;min-width:0;">
        <button class="btn-ghost" onclick="showPage('interviews')" style="margin-bottom:12px;padding-left:0;">
          <i data-iconsax="arrow-left"></i>
          Back to Courses
        </button>
        <h1 class="page-title" id="schedulingCourseName" style="white-space:normal;word-wrap:break-word;">Schedule Students</h1>
        <p class="page-sub">Assign specific interview dates and times to applicants</p>
      </div>
      <div class="page-actions">
        <div style="display:flex;gap:12px">
          <button class="btn-outline" onclick="openSelectApplicantModal()">
            <i data-iconsax="users"></i>
            Select Applicants
          </button>
          <button class="btn-outline" onclick="showAddStudentRow()">
            <i data-iconsax="plus"></i>
            Custom
          </button>
          <button class="btn-primary" onclick="saveAllStudentSchedules()">
            <i data-iconsax="save"></i>
            Save All Schedules
          </button>
        </div>
      </div>
    </div>

    <div class="filter-bar" style="margin-bottom:20px;background:white;padding:12px 16px;border-radius:12px;border:1px solid var(--navy-pale)">
      <div class="search-wrap" style="flex:1">
        <span class="search-icon">
          <i data-iconsax="search"></i>
        </span>
        <input type="text" id="schedulingSearchInput" placeholder="Search applicant name or reference..." class="search-input" oninput="applySchedulingSearch()" />
      </div>
    </div>

    <div class="table-card">
      <div class="table-wrap">
        <table class="app-table">
          <thead>
            <tr>
              <th>Applicant Name</th>
              <th>Reference No.</th>
              <th>Interview Date</th>
              <th>Interview Time</th>
              <th>Status</th>
              <th style="width:50px;">Action</th>
            </tr>
          </thead>
          <tbody id="studentSchedulingTableBody"></tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- ─── PROGRAMS ─── -->
  <div class="page-content" id="page-programs">
    <div class="page-header">
      <div>
        <h1 class="page-title">Programs</h1>
        <p class="page-sub">Manage available degree programs for admission</p>
      </div>
      <div class="page-actions" style="display:flex;gap:12px;">

        <button class="btn-primary" id="saveAllProgramSlotsBtn" type="button" onclick="saveAllProgramSlotsLeft()" disabled>
          <i data-iconsax="save"></i>
          Save All Changes
        </button>
      </div>
    </div>

    <div class="filter-pills-row" id="progFilterPills">
      <button class="filter-pill active" data-category="All">All Programs</button>
      <button class="filter-pill" data-category="Technology">Technology</button>
      <button class="filter-pill" data-category="Business">Business</button>
      <button class="filter-pill" data-category="Education">Education</button>
      <button class="filter-pill" data-category="Hospitality">Hospitality</button>
      <button class="filter-pill" data-category="Accountancy">Accountancy</button>
      <button class="filter-pill" data-category="Arts & Sciences">Arts & Sciences</button>
    </div>
    <div class="table-card">
      <div class="table-wrap">
        <table class="app-table">
          <thead>
            <tr>

              <th>Courses/Programs</th>
              <th>Department</th>
              <th>Duration</th>
              <th>Time Schedule</th>
              <th style="text-align:center;">Total Applicants</th>
              <th>Available Slots</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody id="programsTableBody"></tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- ─── REPORTS ─── -->
  <div class="page-content" id="page-reports">
    <div class="page-header">
      <div>
        <h1 class="page-title">Reports</h1>
        <p class="page-sub">Analytics and summary exports for S.Y. 2025–2026</p>
      </div>
      <div class="page-actions">
        <button class="btn-outline" id="rptCsvBtn">
          <i data-iconsax="download"></i>
          Export CSV
        </button>
        <button class="btn-outline" onclick="window.print()">
          <i data-iconsax="printer"></i>
          Print
        </button>
      </div>
    </div>

    <div class="reports-grid" id="reportsGrid"></div>

    <div class="report-charts-row">
      <div class="chart-card">
        <div class="chart-card-header">
          <div>
            <div class="chart-title">GWA Distribution by Program</div>
            <div class="chart-sub">Average GWA per degree program</div>
          </div>
        </div>
        <div class="chart-body" style="height:280px"><canvas id="gwaChart"></canvas></div>
      </div>
      <div class="chart-card">
        <div class="chart-card-header">
          <div>
            <div class="chart-title">Eligibility Summary</div>
          </div>
        </div>
        <div class="chart-body chart-body--donut" style="height:220px"><canvas id="eligChart"></canvas></div>
      </div>
    </div>

    <div class="table-card" style="margin-top:16px">
      <div class="chart-card-header" style="padding-bottom:0">
        <div class="chart-title">Program Application Summary</div>
      </div>
      <div class="table-wrap">
        <table class="app-table">
          <thead>
            <tr>
              <th>Program</th>
              <th>Category</th>
              <th>Total</th>
              <th>Approved</th>
              <th>Pending</th>
              <th>Rejected</th>
              <th>Avg GWA</th>
            </tr>
          </thead>
          <tbody id="rptTableBody"></tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- ─── WEBSITE CONTENT ─── -->
  <div class="page-content" id="page-website-content">
    <div class="page-header">
      <div>
        <h1 class="page-title">Website Content Studio</h1>
        <p class="page-sub">Design, publish, and maintain your public-facing admissions content.</p>
      </div>
    </div>

    <div class="content-studio-layout">
      <aside class="content-studio-sidebar">
        <button type="button" class="studio-nav-item active" data-target="studio-panel-hero">
          <i data-iconsax="image"></i>
          <span>Hero & CTA</span>
        </button>
        <button type="button" class="studio-nav-item" data-target="studio-panel-contact">
          <i data-iconsax="phone"></i>
          <span>Contact & Social</span>
        </button>
        <button type="button" class="studio-nav-item" data-target="studio-panel-announcements">
          <i data-iconsax="megaphone"></i>
          <span>Announcements</span>
        </button>
        <button type="button" class="studio-nav-item" data-target="studio-panel-news">
          <i data-iconsax="newspaper"></i>
          <span>News & Events</span>
        </button>
        <button type="button" class="studio-nav-item" data-target="studio-panel-testimonials">
          <i data-iconsax="messages-square"></i>
          <span>Testimonials</span>
        </button>
        <button type="button" class="studio-nav-item" data-target="studio-panel-faculty">
          <i data-iconsax="users-round"></i>
          <span>Faculty & Staff</span>
        </button>
      </aside>

      <div class="content-studio-panels">
      <div class="settings-card studio-card studio-panel active" id="studio-panel-hero">
        <div class="settings-card-title">
          <i data-iconsax="image"></i>
          Hero & CTA Section
        </div>
        <p class="studio-card-subtitle">Control the first impression of your landing page.</p>
        <div class="settings-field">
          <label class="settings-label">Hero Headline</label>
          <input type="text" class="settings-input" id="settingHeroHeadline" placeholder="e.g. Begin Your Journey Here." />
        </div>
        <div class="settings-field">
          <label class="settings-label">Hero Sub-headline</label>
          <textarea class="settings-input" id="settingHeroSubheadline" rows="3" style="height:auto;padding-top:8px;" placeholder="Landing page description..."></textarea>
        </div>
        <div class="settings-field">
          <label class="settings-label">School Year Label</label>
          <input type="text" class="settings-input" id="settingSYLabel" placeholder="e.g. Admissions Open · S.Y. 2025–2026" />
        </div>
        <div class="settings-field">
          <label class="settings-label">CTA Button Text</label>
          <input type="text" class="settings-input" id="settingCTAText" placeholder="e.g. Start Your Application" />
        </div>
      </div>

      <div class="settings-card studio-card studio-panel" id="studio-panel-contact">
        <div class="settings-card-title">
          <i data-iconsax="phone"></i>
          Contact & Social Links
        </div>
        <p class="studio-card-subtitle">Keep public contact information accurate and consistent.</p>
        <div class="settings-field">
          <label class="settings-label">Contact Phone</label>
          <input type="text" class="settings-input" id="settingContactPhone" />
        </div>
        <div class="settings-field">
          <label class="settings-label">Office Hours</label>
          <input type="text" class="settings-input" id="settingOfficeHours" placeholder="e.g. Mon-Fri, 9AM-5PM" />
        </div>
        <div class="settings-field">
          <label class="settings-label">Facebook URL</label>
          <input type="url" class="settings-input" id="settingFacebook" />
        </div>
        <div class="settings-field">
          <label class="settings-label">Instagram URL</label>
          <input type="url" class="settings-input" id="settingInstagram" />
        </div>
      </div>

      <div class="settings-card studio-table-card studio-panel" id="studio-panel-announcements">
        <div class="studio-table-card__header">
          <div class="settings-card-title">
            <i data-iconsax="megaphone"></i>
            Announcements & Popups
          </div>
          <button class="btn-ghost studio-add-btn" onclick="openAnnouncementModal()">
            <i data-iconsax="plus"></i> Add Announcement
          </button>
        </div>
        <p class="studio-card-subtitle">Manage ticker text and popup alerts shown on the public website.</p>

        <div class="table-wrap studio-table-wrap">
          <table class="app-table studio-table">
            <thead>
              <tr>
                <th>Message</th>
                <th>Type</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody id="announcementsTableBody">
              <!-- Dynamically filled -->
            </tbody>
          </table>
        </div>
        <div id="announcementsPagination" style="display:none;margin-top:14px;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;"></div>
      </div>

      <div class="settings-card studio-table-card studio-panel" id="studio-panel-news">
        <div class="studio-table-card__header">
          <div class="settings-card-title">
            <i data-iconsax="newspaper"></i>
            News & Events
          </div>
          <button class="btn-ghost studio-add-btn" onclick="openNewsEventModal()">
            <i data-iconsax="plus"></i> Add News/Event
          </button>
        </div>
        <p class="studio-card-subtitle">Publish stories and upcoming activities for applicants and visitors.</p>

        <div class="table-wrap studio-table-wrap">
          <table class="app-table studio-table">
            <thead>
              <tr>
                <th>Title</th>
                <th>Type</th>
                <th>Date</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody id="newsEventsTableBody">
              <!-- Dynamically filled -->
            </tbody>
          </table>
        </div>
        <div id="newsEventsPagination" style="display:none;margin-top:14px;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;"></div>
      </div>

      <div class="settings-card studio-table-card studio-panel" id="studio-panel-testimonials">
        <div class="studio-table-card__header">
          <div class="settings-card-title">
            <i data-iconsax="messages-square"></i>
            Testimonials
          </div>
          <button class="btn-ghost studio-add-btn" onclick="openTestimonialModal()">
            <i data-iconsax="plus"></i> Add Testimonial
          </button>
        </div>
        <p class="studio-card-subtitle">Manage student stories shown on the public homepage.</p>

        <div class="table-wrap studio-table-wrap">
          <table class="app-table studio-table">
            <thead>
              <tr>
                <th>Author</th>
                <th>Message</th>
                <th>Order</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody id="testimonialsTableBody">
              <!-- Dynamically filled -->
            </tbody>
          </table>
        </div>
        <div id="testimonialsPagination" style="display:none;margin-top:14px;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;"></div>
      </div>

      <div class="settings-card studio-table-card studio-panel" id="studio-panel-faculty">
        <div class="studio-table-card__header">
          <div class="settings-card-title">
            <i data-iconsax="users-round"></i>
            Faculty & Staff
          </div>
          <button class="btn-ghost studio-add-btn" onclick="openFacultyStaffModal()">
            <i data-iconsax="plus"></i> Add Member
          </button>
        </div>
        <p class="studio-card-subtitle">Manage the people shown on the About page. Stored in JSON for quick updates.</p>

        <div class="table-wrap studio-table-wrap">
          <table class="app-table studio-table">
            <thead>
              <tr>
                <th>Name</th>
                <th>Role</th>
                <th>Order</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody id="facultyStaffTableBody">
              <!-- Dynamically filled -->
            </tbody>
          </table>
        </div>
        <div id="facultyStaffPagination" style="display:none;margin-top:14px;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;"></div>
      </div>
      </div>
    </div>

    <div class="settings-save-row studio-save-row">
      <button class="btn-primary" id="saveWebsiteSettingsBtn">
        <i data-iconsax="save"></i>
        Save Website Changes
      </button>
    </div>
  </div>

  <!-- ─── SETTINGS ─── -->
  <div class="page-content" id="page-settings">
    <div class="page-header">
      <div>
        <h1 class="page-title">Settings</h1>
        <p class="page-sub">Configure admissions system preferences</p>
      </div>
    </div>

    <div class="settings-grid">
      <div class="settings-card">
        <div class="settings-card-title">
          <i data-iconsax="calendar-range"></i>
          Academic Year & Deadline
        </div>
        <div class="settings-field">
          <label class="settings-label">School Year</label>
          <select class="filter-select" id="settingSY" style="width:100%">
            <option>S.Y. 2026–2027</option>
            <option>S.Y. 2027–2028</option>
            <option>S.Y. 2028–2029</option>
            <option>S.Y. 2029–2030</option>
            <option>S.Y. 2030–2031</option>
            <option>S.Y. 2031–2032</option>
            <option>S.Y. 2032–2033</option>
            <option>S.Y. 2033–2034</option>
            <option>S.Y. 2034–2035</option>
            <option>S.Y. 2035–2036</option>
          </select>
        </div>
        <div class="settings-field">
          <label class="settings-label">Application Deadline</label>
          <input type="text" class="settings-input" id="settingDeadline" value="TBA" />
        </div>
        <div class="settings-field">
          <label class="settings-label">Interview Schedule</label>
          <input type="text" class="settings-input" id="settingInterviewSchedule" value="Monday – Friday, 9:00 AM – 3:00 PM" />
        </div>
        <div class="settings-field">
          <label class="settings-label">Total Applications (read-only)</label>
          <input type="text" class="settings-input" id="settingTotal" readonly style="background:#f8fafc;color:var(--text-2)" />
        </div>
      </div>

      <div class="settings-card">
        <div class="settings-card-title">
          <i data-iconsax="shield-check"></i>
          Application Controls
        </div>
        <div class="settings-toggle-row">
          <div>
            <p class="settings-toggle-label">Accept Applications</p>
            <p class="settings-toggle-sub">Allow new applications to be submitted</p>
          </div>
          <label class="toggle-switch"><input type="checkbox" id="toggleAcceptApplications" checked /><span class="toggle-slider"></span></label>
        </div>
        <div class="settings-toggle-row">
          <div>
            <p class="settings-toggle-label">Email Notifications</p>
            <p class="settings-toggle-sub">Send application and interview email updates</p>
          </div>
          <label class="toggle-switch"><input type="checkbox" id="toggleEmailNotifications" checked /><span class="toggle-slider"></span></label>
        </div>
        <div class="settings-toggle-row">
          <div>
            <p class="settings-toggle-label">Dashboard Notifications</p>
            <p class="settings-toggle-sub">Show toast alerts when new admin notifications arrive</p>
          </div>
          <label class="toggle-switch"><input type="checkbox" id="toggleDashboardNotifications" checked /><span class="toggle-slider"></span></label>
        </div>
      </div>

      <div class="settings-card">
        <div class="settings-card-title">
          <i data-iconsax="key-round"></i>
          Change Password
        </div>
        <div class="settings-field">
          <label class="settings-label">Current Password</label>
          <div style="position:relative;">
            <input type="password" class="settings-input" id="currentAdminPassword" autocomplete="current-password" style="padding-right:44px;" />
            <button type="button" class="password-toggle-btn" data-target="currentAdminPassword" aria-label="Show current password" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);border:none;background:transparent;color:#64748b;cursor:pointer;padding:4px;display:inline-flex;align-items:center;justify-content:center;">
              <i data-iconsax="eye" style="width:18px;height:18px;"></i>
            </button>
          </div>
        </div>
        <div class="settings-field" style="margin-top:12px;">
          <label class="settings-label">New Password</label>
          <div style="position:relative;">
            <input type="password" class="settings-input" id="newAdminPassword" autocomplete="new-password" style="padding-right:44px;" />
            <button type="button" class="password-toggle-btn" data-target="newAdminPassword" aria-label="Show new password" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);border:none;background:transparent;color:#64748b;cursor:pointer;padding:4px;display:inline-flex;align-items:center;justify-content:center;">
              <i data-iconsax="eye" style="width:18px;height:18px;"></i>
            </button>
          </div>
        </div>
        <div class="settings-field" style="margin-top:12px;">
          <label class="settings-label">Confirm New Password</label>
          <div style="position:relative;">
            <input type="password" class="settings-input" id="confirmAdminPassword" autocomplete="new-password" style="padding-right:44px;" />
            <button type="button" class="password-toggle-btn" data-target="confirmAdminPassword" aria-label="Show password confirmation" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);border:none;background:transparent;color:#64748b;cursor:pointer;padding:4px;display:inline-flex;align-items:center;justify-content:center;">
              <i data-iconsax="eye" style="width:18px;height:18px;"></i>
            </button>
          </div>
        </div>
        <p id="changePasswordError" style="display:none;margin-top:8px;font-size:12px;color:#b91c1c;font-weight:600;"></p>
        <button type="button" class="btn-primary" id="changePasswordBtn" style="margin-top:16px;width:100%;justify-content:center;">
          <i data-iconsax="save"></i>
          Save New Password
        </button>
      </div>

      <div class="settings-card">
        <div class="settings-card-title">
          <i data-iconsax="building-2"></i>
          Institution Details
        </div>
        <div class="settings-field">
          <label class="settings-label">Institution Name</label>
          <input type="text" class="settings-input" id="settingInstitutionName" value="Baliwag Polytechnic College" />
        </div>
        <div class="settings-field">
          <label class="settings-label">Admissions Email</label>
          <input type="email" class="settings-input" id="settingAdmissionsEmail" value="admission@btech.edu.ph" />
        </div>
        <div class="settings-field">
          <label class="settings-label">Campus Address</label>
          <input type="text" class="settings-input" id="settingCampusAddress" value="Baliwag City, Bulacan 3006" />
        </div>
      </div>

      <div class="settings-card" style="border: 1px solid var(--navy-pale); background: #f8fafc;">
        <div class="settings-card-title" style="color: var(--navy-mid);">
          <i data-iconsax="refresh"></i>
          System Maintenance
        </div>
        <p class="settings-toggle-sub" style="margin-bottom: 16px;">If your public website doesn't reflect your latest changes, click the button below to force a refresh.</p>
        <button type="button" class="btn-outline" id="manualClearCacheBtn" style="width: 100%; justify-content: center; background: white; border-color: var(--navy-pale);">
          <i data-iconsax="rotate-right"></i>
          Refresh Website Cache
        </button>
      </div>
    </div>

    <div class="settings-save-row">
      <button class="btn-primary" id="saveSettingsBtn">
        <i data-iconsax="save"></i>
        Save Changes
      </button>
    </div>
  </div>

</div><!-- /main-wrap -->

<!-- â•â•â• EDIT COURSE SCHEDULE MODAL â•â•â• -->
<div class="modal-overlay" id="editCourseModal" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(15,20,40,0.55);z-index:9999;align-items:center;justify-content:center;">
  <div style="background:#fff;border-radius:12px;width:90%;max-width:440px;box-shadow:0 10px 25px rgba(0,0,0,0.2);overflow:hidden;">

    <!-- Modal Header -->
    <div style="background:#0f1e3d;padding:20px 24px 18px;">
      <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;">
        <div>
          <p style="font-size:11px;font-weight:500;color:#7c9ec7;letter-spacing:0.06em;text-transform:uppercase;margin:0 0 4px;">Edit Course Schedule</p>
          <h2 id="editCourseLabel" style="font-size:16px;font-weight:600;color:#e8f0fb;margin:0;line-height:1.3;">Course Name</h2>
        </div>
        <button type="button" onclick="closeEditCourseModal()" style="background:rgba(255,255,255,0.08);border:none;border-radius:6px;width:28px;height:28px;display:flex;align-items:center;justify-content:center;cursor:pointer;flex-shrink:0;color:#7c9ec7;font-size:16px;line-height:1;">âœ•</button>
      </div>
      <div style="display:inline-flex;align-items:center;gap:6px;margin-top:12px;background:rgba(255,255,255,0.07);border-radius:20px;padding:3px 10px 3px 6px;">
        <span style="width:6px;height:6px;border-radius:50%;background:#5DCAA5;display:inline-block;flex-shrink:0;"></span>
        <span id="editCourseDept" style="font-size:12px;color:#9ec5e8;">—</span>
        <span style="font-size:12px;color:rgba(156,197,232,0.4);">·</span>
        <span id="editCourseCode" style="font-size:12px;color:#9ec5e8;">—</span>
      </div>
    </div>

    <div style="padding:20px 24px 0;">

      <!-- Schedule Preview -->
      <div style="background:#f8fafc;border-radius:8px;padding:11px 14px;display:flex;align-items:center;gap:10px;margin-bottom:20px;border:1px solid #e2e8f0;">
        <span style="font-size:15px;flex-shrink:0;">ðŸ•</span>
        <div>
          <p style="font-size:11px;color:#64748b;margin:0 0 1px;">Schedule preview</p>
          <p style="font-size:13px;font-weight:600;color:#0f172a;margin:0;" id="editSchedulePreview">—</p>
        </div>
      </div>

      <!-- Hidden ID -->
      <input type="hidden" id="editCourseId">

      <!-- Day Picker -->
      <div style="margin-bottom:16px;">
        <label style="display:block;font-size:12px;font-weight:600;color:#64748b;margin-bottom:8px;letter-spacing:0.03em;">Interview Days</label>
        <div style="display:flex;gap:6px;flex-wrap:wrap;" id="editDayPicker">
          <button type="button" class="edit-day-btn" data-day="Mon" onclick="toggleEditDay(this)" style="padding:6px 13px;border-radius:20px;border:1px solid #e2e8f0;font-size:12px;font-weight:500;cursor:pointer;background:transparent;color:#0f172a;transition:all 0.15s;">Mon</button>
          <button type="button" class="edit-day-btn" data-day="Tue" onclick="toggleEditDay(this)" style="padding:6px 13px;border-radius:20px;border:1px solid #e2e8f0;font-size:12px;font-weight:500;cursor:pointer;background:transparent;color:#0f172a;transition:all 0.15s;">Tue</button>
          <button type="button" class="edit-day-btn" data-day="Wed" onclick="toggleEditDay(this)" style="padding:6px 13px;border-radius:20px;border:1px solid #e2e8f0;font-size:12px;font-weight:500;cursor:pointer;background:transparent;color:#0f172a;transition:all 0.15s;">Wed</button>
          <button type="button" class="edit-day-btn" data-day="Thu" onclick="toggleEditDay(this)" style="padding:6px 13px;border-radius:20px;border:1px solid #e2e8f0;font-size:12px;font-weight:500;cursor:pointer;background:transparent;color:#0f172a;transition:all 0.15s;">Thu</button>
          <button type="button" class="edit-day-btn" data-day="Fri" onclick="toggleEditDay(this)" style="padding:6px 13px;border-radius:20px;border:1px solid #e2e8f0;font-size:12px;font-weight:500;cursor:pointer;background:transparent;color:#0f172a;transition:all 0.15s;">Fri</button>
          <button type="button" class="edit-day-btn" data-day="Sat" onclick="toggleEditDay(this)" style="padding:6px 13px;border-radius:20px;border:1px solid #e2e8f0;font-size:12px;font-weight:500;cursor:pointer;background:transparent;color:#0f172a;transition:all 0.15s;">Sat</button>
        </div>
      </div>

      <!-- Time Pickers -->
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:18px;">
        <div>
          <label style="display:block;font-size:12px;font-weight:600;color:#64748b;margin-bottom:6px;letter-spacing:0.03em;">Start Time</label>
          <select id="editStartTime" onchange="updateEditPreview()" style="width:100%;box-sizing:border-box;font-size:13px;padding:9px 10px;border-radius:8px;border:1px solid #e2e8f0;background:#fff;color:#0f172a;cursor:pointer;">
            <option>6:00 AM</option><option>6:30 AM</option>
            <option>7:00 AM</option><option>7:30 AM</option>
            <option>8:00 AM</option><option>8:30 AM</option>
            <option>9:00 AM</option><option>9:30 AM</option>
            <option>10:00 AM</option><option>10:30 AM</option>
            <option>11:00 AM</option><option>11:30 AM</option>
            <option>12:00 PM</option><option>12:30 PM</option>
            <option>1:00 PM</option><option>1:30 PM</option>
            <option>2:00 PM</option><option>2:30 PM</option>
            <option>3:00 PM</option><option>3:30 PM</option>
            <option>4:00 PM</option><option>4:30 PM</option>
            <option>5:00 PM</option>
          </select>
        </div>
        <div>
          <label style="display:block;font-size:12px;font-weight:600;color:#64748b;margin-bottom:6px;letter-spacing:0.03em;">End Time</label>
          <select id="editEndTime" onchange="updateEditPreview()" style="width:100%;box-sizing:border-box;font-size:13px;padding:9px 10px;border-radius:8px;border:1px solid #e2e8f0;background:#fff;color:#0f172a;cursor:pointer;">
            <option>6:00 AM</option><option>6:30 AM</option>
            <option>7:00 AM</option><option>7:30 AM</option>
            <option>8:00 AM</option><option>8:30 AM</option>
            <option>9:00 AM</option><option>9:30 AM</option>
            <option>10:00 AM</option><option>10:30 AM</option>
            <option>11:00 AM</option><option>11:30 AM</option>
            <option>12:00 PM</option><option>12:30 PM</option>
            <option>1:00 PM</option><option>1:30 PM</option>
            <option>2:00 PM</option><option>2:30 PM</option>
            <option>3:00 PM</option><option>3:30 PM</option>
            <option>4:00 PM</option><option>4:30 PM</option>
            <option>5:00 PM</option>
          </select>
        </div>
      </div>

      <!-- Status Picker -->
      <div style="margin-bottom:20px;">
        <label style="display:block;font-size:12px;font-weight:600;color:#64748b;margin-bottom:8px;letter-spacing:0.03em;">Interview Status</label>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;">
          <div class="edit-status-opt" data-val="Ongoing" onclick="selectEditStatus(this)" style="border:1px solid #e2e8f0;border-radius:8px;padding:10px 8px;text-align:center;cursor:pointer;transition:all 0.15s;">
            <div style="font-size:15px;margin-bottom:4px;">â–¶</div>
            <div style="font-size:12px;font-weight:500;color:#0f172a;">Ongoing</div>
          </div>
          <div class="edit-status-opt" data-val="Paused" onclick="selectEditStatus(this)" style="border:1px solid #e2e8f0;border-radius:8px;padding:10px 8px;text-align:center;cursor:pointer;transition:all 0.15s;">
            <div style="font-size:15px;margin-bottom:4px;">â¸</div>
            <div style="font-size:12px;font-weight:500;color:#0f172a;">Paused</div>
          </div>
          <div class="edit-status-opt" data-val="Completed" onclick="selectEditStatus(this)" style="border:1px solid #e2e8f0;border-radius:8px;padding:10px 8px;text-align:center;cursor:pointer;transition:all 0.15s;">
            <div style="font-size:15px;margin-bottom:4px;">âœ“</div>
            <div style="font-size:12px;font-weight:500;color:#0f172a;">Completed</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Footer -->
    <div style="padding:16px 24px 20px;display:flex;align-items:center;justify-content:flex-end;gap:10px;border-top:1px solid #e2e8f0;">
      <button type="button" class="btn-outline" onclick="closeEditCourseModal()">Cancel</button>
      <button type="button" class="btn-primary" onclick="saveCourseSchedule()">Save Changes</button>
    </div>

  </div>
</div>

<!-- â•â•â• LOGOUT MODAL â•â•â• -->
<!-- FACULTY & STAFF MODAL -->
<div class="modal-overlay" id="facultyStaffModal" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(15,20,40,0.55);z-index:9999;align-items:center;justify-content:center;">
  <div style="background:#fff;border-radius:12px;width:90%;max-width:520px;box-shadow:0 10px 25px rgba(0,0,0,0.2);overflow:hidden;">
    <div style="background:#0f1e3d;padding:20px 24px 18px;display:flex;justify-content:space-between;align-items:center;">
      <h2 id="facultyStaffModalTitle" style="font-size:16px;font-weight:600;color:#e8f0fb;margin:0;">Add Faculty/Staff</h2>
      <button type="button" onclick="closeFacultyStaffModal()" style="background:transparent;border:none;color:#7c9ec7;font-size:20px;cursor:pointer;">&times;</button>
    </div>
    <div style="padding:24px;max-height:70vh;overflow-y:auto;">
      <input type="hidden" id="editFacultyStaffId">
      <div class="settings-field">
        <label class="settings-label">Name</label>
        <input type="text" class="settings-input" id="facultyStaffName" placeholder="e.g. Dr. Maria Santos">
      </div>
      <div class="settings-field" style="margin-top:12px;">
        <label class="settings-label">Role</label>
        <input type="text" class="settings-input" id="facultyStaffRole" placeholder="e.g. Admissions Director">
      </div>
      <div class="settings-field" style="margin-top:12px;">
        <label class="settings-label">Note</label>
        <textarea class="settings-input" id="facultyStaffNote" rows="4" style="height:auto;" placeholder="Short description shown on the About page"></textarea>
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:12px;">
        <div class="settings-field">
          <label class="settings-label">Icon</label>
          <input type="text" class="settings-input" id="facultyStaffIcon" placeholder="user-round">
        </div>
        <div class="settings-field">
          <label class="settings-label">Display Order</label>
          <input type="number" class="settings-input" id="facultyStaffOrder" min="0" step="1" value="0">
        </div>
      </div>
      <div class="settings-field" style="margin-top:12px;">
        <label class="settings-label">Image</label>
        <div id="facultyStaffImageDropzone" style="border:2px dashed #cbd5e1;border-radius:10px;padding:14px;background:#f8fafc;cursor:pointer;transition:all .2s ease;">
          <input type="file" class="settings-input" id="facultyStaffImageFile" accept="image/jpeg,image/png,image/webp,image/jpg" style="display:none;">
          <div style="display:flex;align-items:center;gap:10px;">
            <i data-iconsax="upload-cloud" style="width:18px;height:18px;color:#64748b;"></i>
            <p style="margin:0;font-size:13px;color:#334155;">
              Drag and drop an image here, or <span style="color:#0f1e3d;font-weight:600;">click to browse</span>
            </p>
          </div>
        </div>
        <p style="margin-top:6px;font-size:12px;color:var(--text-3);">Allowed: JPG, PNG, WEBP. Max size: 2MB.</p>
        <p id="facultyStaffImageError" style="display:none;margin-top:6px;font-size:12px;color:#b91c1c;font-weight:500;"></p>
        <div id="facultyStaffImagePreview" style="display:none;margin-top:10px;align-items:center;gap:10px;">
          <img id="facultyStaffImagePreviewImg" src="" alt="Faculty/staff preview" style="width:64px;height:64px;border-radius:50%;object-fit:cover;border:1px solid #e2e8f0;">
          <button type="button" class="btn-view-label" id="removeFacultyStaffImageBtn" style="background:#fee2e2;color:#b91c1c;">Remove</button>
        </div>
      </div>
      <div class="settings-toggle-row" style="margin-top:12px;">
        <p class="settings-toggle-label" style="font-size:13px;">Active</p>
        <label class="toggle-switch"><input type="checkbox" id="facultyStaffIsActive" checked /><span class="toggle-slider"></span></label>
      </div>
    </div>
    <div style="padding:16px 24px 20px;display:flex;justify-content:flex-end;gap:10px;border-top:1px solid #e2e8f0;">
      <button type="button" class="btn-outline" onclick="closeFacultyStaffModal()">Cancel</button>
      <button type="button" class="btn-primary" id="saveFacultyStaffBtn">Save Member</button>
    </div>
  </div>
</div>

<div class="modal-overlay" id="passwordUpdatedModal" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);z-index:9999;align-items:center;justify-content:center;">
  <div class="modal-box" style="background:#fff;padding:24px;border-radius:12px;width:90%;max-width:430px;box-shadow:0 10px 25px rgba(0,0,0,0.2);">
    <h3 style="margin-top:0;font-size:18px;font-weight:700;color:var(--navy);">Password Updated</h3>
    <p style="color:#4b5563;margin:16px 0;line-height:1.6;">Your admin password has been changed. Do you want to log out now and sign in with the new password?</p>
    <div style="display:flex;justify-content:flex-end;gap:12px;margin-top:24px;flex-wrap:wrap;">
      <button type="button" class="btn-outline" id="stayAfterPasswordBtn" style="padding:8px 16px;">Stay on Dashboard</button>
      <button type="button" class="btn-primary" id="logoutAfterPasswordBtn" style="padding:8px 16px;">Log Out &amp; Login</button>
    </div>
  </div>
</div>

<div class="modal-overlay" id="logoutModal" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);z-index:9999;align-items:center;justify-content:center;">
  <div class="modal-box" style="background:#fff;padding:24px;border-radius:12px;width:90%;max-width:400px;box-shadow:0 10px 25px rgba(0,0,0,0.2);">
    <h3 style="margin-top:0;font-size:18px;font-weight:700;color:var(--navy);">Confirm Logout</h3>
    <p style="color:#4b5563;margin:16px 0;">Are you sure you want to sign out of the dashboard?</p>
    <div style="display:flex;justify-content:flex-end;gap:12px;margin-top:24px;">
      <button class="btn-outline" id="cancelLogoutBtn" style="padding:8px 16px;">Cancel</button>
      <form method="POST" action="{{ route('logout') }}" id="logoutForm" style="margin:0;">
        @csrf
        <button type="submit" class="btn-primary" style="background-color:var(--red);border-color:var(--red);padding:8px 16px;">Yes, log out</button>
      </form>
    </div>
  </div>
</div>

<!-- â•â•â• SELECT APPLICANT MODAL â•â•â• -->
<div class="modal-overlay" id="selectApplicantModal" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);z-index:9999;align-items:center;justify-content:center;">
  <div class="modal-box" style="background:#fff;padding:24px;border-radius:12px;width:90%;max-width:600px;box-shadow:0 10px 25px rgba(0,0,0,0.2);max-height:80vh;display:flex;flex-direction:column;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
      <h3 style="margin:0;font-size:18px;font-weight:700;color:var(--navy);">Select Students to Schedule</h3>
      <button class="btn-ghost" onclick="closeSelectApplicantModal()" style="padding:4px;">
        <i data-iconsax="x"></i>
      </button>
    </div>
    <div style="margin-bottom:16px;">
      <input type="text" id="applicantSearchInput" placeholder="Search applicant by name or reference..." class="fi" style="width:100%;padding:8px 12px;font-size:14px;" oninput="filterApplicantModal()">
    </div>
    <div style="overflow-y:auto;flex:1;border:1px solid #e2e8f0;border-radius:8px;">
      <table class="app-table" style="margin:0;">
        <thead style="position:sticky;top:0;background:#f8fafc;z-index:1;">
          <tr>
            <th>Applicant Details</th>
            <th>GWA</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody id="applicantModalList"></tbody>
      </table>
    </div>
  </div>
</div>

<!-- â•â•â• ANNOUNCEMENT MODAL â•â•â• -->
<div class="modal-overlay" id="announcementModal" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(15,20,40,0.55);z-index:9999;align-items:center;justify-content:center;">
  <div style="background:#fff;border-radius:12px;width:90%;max-width:500px;box-shadow:0 10px 25px rgba(0,0,0,0.2);overflow:hidden;">
    <div style="background:#0f1e3d;padding:20px 24px 18px;display:flex;justify-content:space-between;align-items:center;">
      <h2 id="announcementModalTitle" style="font-size:16px;font-weight:600;color:#e8f0fb;margin:0;">Add Announcement</h2>
      <button type="button" onclick="closeAnnouncementModal()" style="background:transparent;border:none;color:#7c9ec7;font-size:20px;cursor:pointer;">âœ•</button>
    </div>
    <div style="padding:24px;max-height:70vh;overflow-y:auto;">
      <input type="hidden" id="editAnnouncementId">
      <div class="settings-field">
        <label class="settings-label">Announcement Message</label>
        <textarea class="settings-input" id="annMessage" rows="3" style="height:auto;" required></textarea>
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:16px;">
        <div class="settings-field">
          <label class="settings-label">Starts At</label>
          <input type="date" class="settings-input" id="annStartsAt">
        </div>
        <div class="settings-field">
          <label class="settings-label">Ends At</label>
          <input type="date" class="settings-input" id="annEndsAt">
        </div>
      </div>
      <div class="settings-field" style="margin-top:16px;">
        <label class="settings-label">Announcement Image</label>
        <input type="file" class="settings-input" id="annPopupImageFile" accept="image/jpeg,image/png,image/webp,image/jpg">
        <p style="margin-top:6px;font-size:12px;color:var(--text-3);">Shown in ticker and popup announcements. Allowed: JPG, PNG, WEBP. Max size: 2MB.</p>
        <p id="annPopupImageError" style="display:none;margin-top:6px;font-size:12px;color:#b91c1c;font-weight:500;"></p>
        <div id="annPopupImagePreview" style="display:none;margin-top:10px;position:relative;width:160px;">
          <img id="annPopupImagePreviewImg" src="" alt="Announcement preview" style="width:160px;height:96px;object-fit:cover;border-radius:8px;border:1px solid #e2e8f0;">
          <button type="button" id="annPopupImageRemoveBtn" style="position:absolute;right:6px;bottom:6px;border:1px solid rgba(255,255,255,.65);background:rgba(185,28,28,.88);color:#fff;border-radius:999px;padding:2px 8px;font-size:10px;font-weight:700;cursor:pointer;">Remove</button>
        </div>
      </div>
      <div class="settings-toggle-row" style="margin-top:16px;padding:12px;background:#f8fafc;border-radius:8px;">
        <div>
          <p class="settings-toggle-label" style="font-size:13px;">Show as Popup</p>
          <p class="settings-toggle-sub" style="font-size:11px;">Display as modal when guests visit the site</p>
        </div>
        <label class="toggle-switch"><input type="checkbox" id="annIsPopup" /><span class="toggle-slider"></span></label>
      </div>
      <div id="popupFields" style="display:none;margin-top:12px;padding-left:12px;border-left:2px solid var(--navy-pale);">
        <div class="settings-field">
          <label class="settings-label">Popup Button Text</label>
          <input type="text" class="settings-input" id="annPopupButtonText" placeholder="e.g. Learn More">
        </div>
        <div class="settings-field">
          <label class="settings-label">Popup Button Link</label>
          <input type="text" class="settings-input" id="annPopupButtonLink" placeholder="e.g. /apply or https://...">
        </div>
      </div>
      <div class="settings-toggle-row" style="margin-top:12px;">
        <p class="settings-toggle-label" style="font-size:13px;">Active</p>
        <label class="toggle-switch"><input type="checkbox" id="annIsActive" checked /><span class="toggle-slider"></span></label>
      </div>
    </div>
    <div style="padding:16px 24px 20px;display:flex;justify-content:flex-end;gap:10px;border-top:1px solid #e2e8f0;">
      <button type="button" class="btn-outline" onclick="closeAnnouncementModal()">Cancel</button>
      <button type="button" class="btn-primary" id="saveAnnouncementBtn">Save Announcement</button>
    </div>
  </div>
</div>

<!-- â•â•â• NEWS & EVENTS MODAL â•â•â• -->
<div class="modal-overlay" id="newsEventModal" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(15,20,40,0.55);z-index:9999;align-items:center;justify-content:center;">
  <div style="background:#fff;border-radius:12px;width:90%;max-width:560px;box-shadow:0 10px 25px rgba(0,0,0,0.2);overflow:hidden;">
    <div style="background:#0f1e3d;padding:20px 24px 18px;display:flex;justify-content:space-between;align-items:center;">
      <h2 id="newsEventModalTitle" style="font-size:16px;font-weight:600;color:#e8f0fb;margin:0;">Add News/Event</h2>
      <button type="button" onclick="closeNewsEventModal()" style="background:transparent;border:none;color:#7c9ec7;font-size:20px;cursor:pointer;">âœ•</button>
    </div>
    <div style="padding:24px;max-height:70vh;overflow-y:auto;">
      <input type="hidden" id="editNewsEventId">
      <div class="settings-field">
        <label class="settings-label">Title</label>
        <input type="text" class="settings-input" id="newsEventTitle" required>
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:12px;">
        <div class="settings-field">
          <label class="settings-label">Type</label>
          <select class="filter-select" id="newsEventType" style="width:100%;">
            <option value="news">News</option>
            <option value="event">Event</option>
          </select>
        </div>
        <div class="settings-field">
          <label class="settings-label">Date</label>
          <input type="date" class="settings-input" id="newsEventDate">
        </div>
      </div>
      <div class="settings-field" style="margin-top:12px;">
        <label class="settings-label">Location (for events)</label>
        <input type="text" class="settings-input" id="newsEventLocation" placeholder="e.g. BTECH Main Campus">
      </div>
      <div class="settings-field" style="margin-top:12px;">
        <label class="settings-label">Images (optional)</label>
        <div id="newsEventDropzone" style="border:2px dashed #cbd5e1;border-radius:10px;padding:14px;background:#f8fafc;cursor:pointer;transition:all .2s ease;">
          <input type="file" class="settings-input" id="newsEventImageFile" accept="image/jpeg,image/png,image/webp,image/jpg" style="display:none;" multiple>
          <div style="display:flex;align-items:center;gap:10px;">
            <i data-iconsax="upload-cloud" style="width:18px;height:18px;color:#64748b;"></i>
            <p style="margin:0;font-size:13px;color:#334155;">
              Drag and drop image/s here, or <span style="color:#0f1e3d;font-weight:600;">click to browse</span>
            </p>
          </div>
        </div>
        <p style="margin-top:6px;font-size:12px;color:var(--text-3);">Allowed: JPG, PNG, WEBP. Max size: 2MB each.</p>
        <p style="margin-top:4px;font-size:12px;color:#475569;">Tip: Drag thumbnails to reorder. The first image will be used as the cover image.</p>
        <p id="newsEventImageError" style="display:none;margin-top:6px;font-size:12px;color:#b91c1c;font-weight:500;"></p>
        <div id="newsEventImagePreviewGrid" style="display:none;margin-top:10px;grid-template-columns:repeat(auto-fill,minmax(110px,1fr));gap:8px;"></div>
      </div>
      <div class="settings-field" style="margin-top:12px;">
        <label class="settings-label">Summary</label>
        <textarea class="settings-input" id="newsEventSummary" rows="3" style="height:auto;"></textarea>
      </div>
      <div class="settings-field" style="margin-top:12px;">
        <label class="settings-label">Content</label>
        <textarea class="settings-input" id="newsEventContent" rows="6" style="height:auto;"></textarea>
      </div>
      <div class="settings-toggle-row" style="margin-top:12px;">
        <p class="settings-toggle-label" style="font-size:13px;">Active</p>
        <label class="toggle-switch"><input type="checkbox" id="newsEventIsActive" checked /><span class="toggle-slider"></span></label>
      </div>
    </div>
    <div style="padding:16px 24px 20px;display:flex;justify-content:flex-end;gap:10px;border-top:1px solid #e2e8f0;">
      <button type="button" class="btn-outline" onclick="closeNewsEventModal()">Cancel</button>
      <button type="button" class="btn-primary" id="saveNewsEventBtn">Save</button>
    </div>
  </div>
</div>

<!-- TESTIMONIAL MODAL -->
<div class="modal-overlay" id="testimonialModal" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(15,20,40,0.55);z-index:9999;align-items:center;justify-content:center;">
  <div style="background:#fff;border-radius:12px;width:90%;max-width:520px;box-shadow:0 10px 25px rgba(0,0,0,0.2);overflow:hidden;">
    <div style="background:#0f1e3d;padding:20px 24px 18px;display:flex;justify-content:space-between;align-items:center;">
      <h2 id="testimonialModalTitle" style="font-size:16px;font-weight:600;color:#e8f0fb;margin:0;">Add Testimonial</h2>
      <button type="button" onclick="closeTestimonialModal()" style="background:transparent;border:none;color:#7c9ec7;font-size:20px;cursor:pointer;">&times;</button>
    </div>
    <div style="padding:24px;max-height:70vh;overflow-y:auto;">
      <input type="hidden" id="editTestimonialId">
      <div class="settings-field">
        <label class="settings-label">Author Name</label>
        <input type="text" class="settings-input" id="testimonialAuthorName" placeholder="e.g. Maria Santos">
      </div>
      <div class="settings-field" style="margin-top:12px;">
        <label class="settings-label">Author Role</label>
        <input type="text" class="settings-input" id="testimonialAuthorRole" placeholder="e.g. BSIT Graduate, 2025">
      </div>
      <div class="settings-field" style="margin-top:12px;">
        <label class="settings-label">Avatar Image</label>
        <div id="testimonialAvatarDropzone" style="border:2px dashed #cbd5e1;border-radius:10px;padding:14px;background:#f8fafc;cursor:pointer;transition:all .2s ease;">
          <input type="file" class="settings-input" id="testimonialAuthorAvatarFile" accept="image/jpeg,image/png,image/webp,image/jpg" style="display:none;">
          <div style="display:flex;align-items:center;gap:10px;">
            <i data-iconsax="upload-cloud" style="width:18px;height:18px;color:#64748b;"></i>
            <p style="margin:0;font-size:13px;color:#334155;">
              Drag and drop an image here, or <span style="color:#0f1e3d;font-weight:600;">click to browse</span>
            </p>
          </div>
        </div>
        <p style="margin-top:6px;font-size:12px;color:var(--text-3);">Allowed: JPG, PNG, WEBP. Max size: 2MB.</p>
        <p id="testimonialAvatarError" style="display:none;margin-top:6px;font-size:12px;color:#b91c1c;font-weight:500;"></p>
        <div id="testimonialAvatarPreview" style="display:none;margin-top:10px;align-items:center;gap:10px;">
          <img id="testimonialAvatarPreviewImage" src="" alt="Avatar preview" style="width:54px;height:54px;border-radius:50%;object-fit:cover;border:1px solid #e2e8f0;">
          <button type="button" class="btn-view-label" id="removeTestimonialAvatarBtn" style="background:#fee2e2;color:#b91c1c;">Remove</button>
        </div>
      </div>
      <div class="settings-field" style="margin-top:12px;">
        <label class="settings-label">Message</label>
        <textarea class="settings-input" id="testimonialMessage" rows="5" style="height:auto;" placeholder="Write the student story..."></textarea>
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:12px;">
        <div class="settings-field">
          <label class="settings-label">Display Order</label>
          <input type="number" class="settings-input" id="testimonialOrder" min="0" step="1" value="0">
        </div>
        <div class="settings-toggle-row" style="align-self:end;">
          <p class="settings-toggle-label" style="font-size:13px;">Active</p>
          <label class="toggle-switch"><input type="checkbox" id="testimonialIsActive" checked /><span class="toggle-slider"></span></label>
        </div>
      </div>
    </div>
    <div style="padding:16px 24px 20px;display:flex;justify-content:flex-end;gap:10px;border-top:1px solid #e2e8f0;">
      <button type="button" class="btn-outline" onclick="closeTestimonialModal()">Cancel</button>
      <button type="button" class="btn-primary" id="saveTestimonialBtn">Save Testimonial</button>
    </div>
  </div>
</div>

<!-- â•â•â• SLIDE-OVER â•â•â• -->
<div class="slideover-overlay" id="slideoverOverlay"></div>
<div class="slideover" id="slideover">
  <div class="slideover-header">
    <div>
      <div class="slideover-title" id="slideoverTitle">Applicant Details</div>
      <div class="slideover-ref" id="slideoverRef">—</div>
    </div>
    <button class="slideover-close" id="slideoverClose">
      <i data-iconsax="x"></i>
    </button>
  </div>
  <div class="slideover-body" id="slideoverBody"></div>
</div>

<!-- â•â•â• TOAST â•â•â• -->
<div class="toast" id="toast"></div>

<script>
  // ── Logout Modal ──────────────────────────────────────────────────
  document.addEventListener('DOMContentLoaded', function () {
    const logoutBtn      = document.getElementById('logoutBtn');
    const logoutModal    = document.getElementById('logoutModal');
    const cancelLogoutBtn = document.getElementById('cancelLogoutBtn');

    if (logoutBtn && logoutModal) {
      logoutBtn.addEventListener('click', function (e) {
        e.preventDefault();
        logoutModal.style.display = 'flex';
      });
    }
    if (cancelLogoutBtn && logoutModal) {
      cancelLogoutBtn.addEventListener('click', function () {
        logoutModal.style.display = 'none';
      });
    }
    if (logoutModal) {
      logoutModal.addEventListener('click', function (e) {
        if (e.target === logoutModal) logoutModal.style.display = 'none';
      });
    }

    const logoutForm = document.getElementById('logoutForm');
    if (logoutForm) {
      logoutForm.addEventListener('submit', function () {
        if (typeof AdmissionAPI !== 'undefined') AdmissionAPI.clearToken();
      });
    }

    if (typeof iconsax !== 'undefined') iconsax.createIcons();
  });

  // ── Edit Course Modal ─────────────────────────────────────────────
  const editSelectedDays = new Set();
  const DAY_ORDER = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

  function openEditCourseModal(courseId, courseName, dept, code, schedule, status) {
    document.getElementById('editCourseId').value          = courseId;
    document.getElementById('editCourseLabel').textContent = courseName;
    document.getElementById('editCourseDept').textContent  = dept || '—';
    document.getElementById('editCourseCode').textContent  = code || '—';

    // Reset days
    editSelectedDays.clear();
    document.querySelectorAll('.edit-day-btn').forEach(btn => {
      btn.style.background = 'transparent';
      btn.style.color      = '#0f172a';
      btn.style.border     = '1px solid #e2e8f0';
    });

    if (schedule) {
      const parts    = schedule.split(',');
      const dayPart  = (parts[0] || '').trim();
      const timePart = (parts[1] || '').trim();

      // Parse day ranges e.g. "Mon-Wed" or "Mon-Fri"
      dayPart.split('/').forEach(seg => {
        seg = seg.trim();
        if (seg.includes('-')) {
          const [from, to] = seg.split('-').map(s => s.trim());
          const si = DAY_ORDER.indexOf(from);
          const ei = DAY_ORDER.indexOf(to);
          if (si !== -1 && ei !== -1) {
            for (let i = si; i <= ei; i++) editSelectedDays.add(DAY_ORDER[i]);
          }
        } else if (DAY_ORDER.includes(seg)) {
          editSelectedDays.add(seg);
        }
      });

      editSelectedDays.forEach(day => {
        const btn = document.querySelector(`.edit-day-btn[data-day="${day}"]`);
        if (btn) activateEditDay(btn);
      });

      // Parse times e.g. "9AM-2PM"
      if (timePart) {
        const timeSplit = timePart.split('-');
        setEditSelect('editStartTime', normalizeTime((timeSplit[0] || '').trim()));
        setEditSelect('editEndTime',   normalizeTime((timeSplit[1] || '').trim()));
      }
    }

    // Reset status cards
    document.querySelectorAll('.edit-status-opt').forEach(o => {
      o.style.border     = '1px solid #e2e8f0';
      o.style.background = 'transparent';
    });
    const statusEl = document.querySelector(`.edit-status-opt[data-val="${status}"]`);
    if (statusEl) {
      statusEl.style.border     = '2px solid #0f1e3d';
      statusEl.style.background = 'rgba(15,30,61,0.06)';
    }

    updateEditPreview();
    document.getElementById('editCourseModal').style.display = 'flex';
  }

  function closeEditCourseModal() {
    document.getElementById('editCourseModal').style.display = 'none';
  }

  function toggleEditDay(btn) {
    const day = btn.dataset.day;
    if (editSelectedDays.has(day)) {
      if (editSelectedDays.size === 1) return;
      editSelectedDays.delete(day);
      btn.style.background = 'transparent';
      btn.style.color      = '#0f172a';
      btn.style.border     = '1px solid #e2e8f0';
    } else {
      editSelectedDays.add(day);
      activateEditDay(btn);
    }
    updateEditPreview();
  }

  function activateEditDay(btn) {
    btn.style.background = '#0f1e3d';
    btn.style.color      = '#e8f0fb';
    btn.style.border     = '1px solid #0f1e3d';
  }

  function selectEditStatus(el) {
    document.querySelectorAll('.edit-status-opt').forEach(o => {
      o.style.border     = '1px solid #e2e8f0';
      o.style.background = 'transparent';
    });
    el.style.border     = '2px solid #0f1e3d';
    el.style.background = 'rgba(15,30,61,0.06)';
  }

  function updateEditPreview() {
    const days = DAY_ORDER.filter(d => editSelectedDays.has(d));
    let dayStr = '—';
    if (days.length === 1) {
      dayStr = days[0];
    } else if (days.length > 1) {
      const ranges = [];
      let start = days[0], prev = days[0];
      for (let i = 1; i < days.length; i++) {
        if (DAY_ORDER.indexOf(days[i]) === DAY_ORDER.indexOf(prev) + 1) {
          prev = days[i];
        } else {
          ranges.push(start === prev ? start : start + '–' + prev);
          start = prev = days[i];
        }
      }
      ranges.push(start === prev ? start : start + '–' + prev);
      dayStr = ranges.join(', ');
    }
    const s = document.getElementById('editStartTime').value;
    const e = document.getElementById('editEndTime').value;
    document.getElementById('editSchedulePreview').textContent = `${dayStr}, ${s} – ${e}`;
  }

  function saveCourseSchedule() {
    const courseId = document.getElementById('editCourseId').value;
    const schedule = document.getElementById('editSchedulePreview').textContent;
    const statusEl = document.querySelector('.edit-status-opt[style*="2px solid"]');
    const status   = statusEl ? statusEl.dataset.val : 'Ongoing';

    AdmissionAPI.request(`/programs/${courseId}/schedule`, {
      method: 'PATCH',
      body: JSON.stringify({
        interview_schedule: schedule,
        interview_status: status
      })
    })
    .then(() => {
      if (typeof showToast === 'function') showToast('Schedule updated successfully!');
      if (typeof refreshData === 'function') refreshData(false);
      closeEditCourseModal();
    })
    .catch(err => {
      console.error(err);
      if (typeof showToast === 'function') showToast('Error: ' + err.message, 'error');
    });
  }

  // ── Helpers ───────────────────────────────────────────────────────
  function setEditSelect(selectId, value) {
    const sel = document.getElementById(selectId);
    if (!sel || !value) return;
    for (const opt of sel.options) {
      if (opt.value === value || opt.text === value) {
        sel.value = opt.value;
        return;
      }
    }
  }

  function normalizeTime(str) {
    if (!str) return '';
    str = str.toUpperCase().replace(/\s/g, '');
    const match = str.match(/^(\d{1,2})(?::(\d{2}))?(AM|PM)$/);
    if (!match) return str;
    return `${match[1]}:${match[2] || '00'} ${match[3]}`;
  }

  // Close modal when clicking outside the box
  document.getElementById('editCourseModal').addEventListener('click', function (e) {
    if (e.target === this) closeEditCourseModal();
  });
</script>

<!-- GLOBAL CONFIRMATION MODAL -->
<div class="modal-overlay" id="confirmModal" style="display:none; align-items:center; justify-content:center; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(15,23,42,0.6); backdrop-filter:blur(4px); z-index:9999;">
  <div class="modal-box" style="width:100%; max-width:400px; background:white; border-radius:16px; padding:24px; box-shadow:0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04); animation:modalSlideIn 0.3s ease-out;">
    <div style="display:flex; flex-direction:column; align-items:center; text-align:center; gap:16px;">
      <div id="confirmModalIcon" style="width:56px; height:56px; background:#fee2e2; color:#ef4444; border-radius:50%; display:flex; align-items:center; justify-content:center;">
        <i id="confirmModalIconInner" data-iconsax="trash" style="width:28px; height:28px;"></i>
      </div>
      <div>
        <h3 id="confirmModalTitle" style="font-size:18px; font-weight:700; color:var(--navy); margin-bottom:8px;">Confirm Action</h3>
        <p id="confirmModalMessage" style="font-size:14px; color:var(--text-3); line-height:1.5;"></p>
      </div>
      <div style="display:flex; width:100%; gap:12px; margin-top:8px;">
        <button type="button" class="btn-ghost" id="confirmModalCancelBtn" style="flex:1; padding:12px; border:1px solid var(--navy-pale); border-radius:10px; font-weight:600; text-align:center;">Cancel</button>
        <button type="button" id="confirmModalConfirmBtn" style="flex:1; padding:12px; background:#ef4444; color:white; border:none; border-radius:10px; font-weight:600; cursor:pointer; text-align:center;">Confirm</button>
      </div>
    </div>
  </div>
</div>

</body>
</html>


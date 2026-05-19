<section id="dashboardStep" class="portal-step max-w-7xl mx-auto px-8 py-10">
  <div class="portal-card p-5 md:p-7 mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
      <div>
        <p class="text-sm text-slate-500">Welcome back</p>
        <h2 id="applicantName" class="text-3xl font-bold text-[#0f1e3d]"></h2>
        <p class="text-sm text-slate-500 mt-1">Reference: <span id="applicantReference" class="font-bold text-slate-700"></span></p>
      </div>
      <div class="flex flex-wrap items-center gap-2">
        <span id="statusBadge" class="status-badge"></span>
        <span id="lastUpdated" class="text-xs font-semibold text-slate-500"></span>
      </div>
    </div>
  </div>

  <div id="lockedNotice" class="portal-alert portal-alert-info mb-6 hidden">Your application is currently locked. Please contact the admissions office if you need to request another change.</div>
  <div id="pendingDocsNotice" class="portal-alert portal-alert-warn mb-6 hidden">Please review your document checklist and upload any missing requirements.</div>

  <div class="portal-card">
    <div class="portal-tabs px-4" id="portalTabs">
      <button class="portal-tab active" data-tab="status" type="button">Status</button>
      <button class="portal-tab" data-tab="details" type="button">Application Details</button>
      <button class="portal-tab" data-tab="documents" type="button">Documents</button>
      <button class="portal-tab" data-tab="interview" type="button">Interview</button>
    </div>

    <div class="p-5 md:p-7">
      <section id="panel-status" class="portal-panel active">
        <h3 class="text-xl font-bold text-[#0f1e3d] mb-4">Application Timeline</h3>
        <div id="statusTimeline" class="timeline"></div>
      </section>

      <section id="panel-details" class="portal-panel">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-5">
          <div>
            <h3 class="text-xl font-bold text-[#0f1e3d]">Application Details</h3>
            <p class="text-sm text-slate-500">You can edit while your application is still open for changes.</p>
          </div>
          <div class="flex gap-2">
            <button id="editDetailsBtn" class="portal-btn portal-btn-ghost" type="button">Edit</button>
            <button id="saveDetailsBtn" class="portal-btn portal-btn-primary hidden" type="button">Save Changes</button>
            <button id="cancelEditBtn" class="portal-btn portal-btn-ghost hidden" type="button">Cancel</button>
          </div>
        </div>
        <div id="choiceWarning" class="portal-alert portal-alert-warn mb-5 hidden">Changing your first choice may affect your admission processing. Please review carefully before submitting.</div>
        <form id="detailsForm" class="space-y-7"></form>
      </section>

      <section id="panel-documents" class="portal-panel">
        <h3 class="text-xl font-bold text-[#0f1e3d] mb-1">Documents</h3>
        <p class="text-sm text-slate-500 mb-5">Upload or replace documents while your application is editable.</p>
        <div id="documentList" class="grid gap-3"></div>
      </section>

      <section id="panel-interview" class="portal-panel">
        <h3 class="text-xl font-bold text-[#0f1e3d] mb-4">Interview Schedule</h3>
        <div id="interviewCard"></div>
      </section>
    </div>
  </div>
</section>

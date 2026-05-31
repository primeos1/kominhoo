<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Admin Panel') — Kominhoo Beauty</title>
  <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
  <link rel="alternate icon" href="{{ asset('favicon.ico') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  {{-- Load fonts asynchronously so they don't block first paint --}}
  <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;0,9..40,800;1,9..40,300;1,9..40,400;1,9..40,500;1,9..40,600;1,9..40,700&display=swap" onload="this.onload=null;this.rel='stylesheet'" />
  <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;0,9..40,800;1,9..40,300;1,9..40,400;1,9..40,500;1,9..40,600;1,9..40,700&display=swap" /></noscript>
  <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
  <style>
    /* ── Admin Layout ──────────────────────────────────────── */
    body { background: var(--bg-primary); font-family: var(--font-body); overflow: hidden; }

    /* Lock wrapper to viewport so only admin-main scrolls, not the page */
    .admin-wrapper { display: flex; height: 100vh; overflow: hidden; }

    /* Sidebar */
    .admin-sidebar {
      width: 260px;
      background: var(--black);
      display: flex; flex-direction: column;
      position: fixed; top: 0; left: 0; bottom: 0;
      z-index: 100; overflow-y: auto;
    }
    .admin-brand {
      padding: 24px 20px 20px;
      border-bottom: 1px solid rgba(255,255,255,.08);
    }
    .admin-brand .logo-text { font-family: var(--font-display); font-size: 1.4rem; color: #fff; letter-spacing: .5px; }
    .admin-brand .logo-text span { color: var(--lime); }
    .admin-brand .admin-badge {
      display: inline-block; background: var(--red); color: #fff;
      font-size: .6rem; font-weight: 700; letter-spacing: 1px;
      text-transform: uppercase; padding: 2px 8px; border-radius: 20px;
      margin-left: 6px; vertical-align: middle;
    }
    .admin-nav { padding: 16px 0; flex: 1; }
    .admin-nav-section {
      padding: 6px 20px 4px; font-size: .65rem; font-weight: 700;
      letter-spacing: 1.5px; text-transform: uppercase;
      color: rgba(255,255,255,.3); margin-top: 8px;
    }
    .admin-nav-item {
      display: flex; align-items: center; gap: 12px;
      padding: 11px 20px; color: rgba(255,255,255,.6);
      font-size: .88rem; font-weight: 500; cursor: pointer;
      transition: all .2s; border-left: 3px solid transparent;
      text-decoration: none;
    }
    .admin-nav-item:hover { color: #fff; background: rgba(255,255,255,.05); }
    .admin-nav-item.active { color: var(--lime); border-left-color: var(--lime); background: rgba(212,217,148,.06); }
    .admin-nav-item .nav-icon { font-size: 1rem; width: 20px; text-align: center; }
    .admin-nav-item .nav-badge {
      margin-left: auto; background: var(--red); color: #fff;
      font-size: .6rem; font-weight: 700; padding: 2px 7px; border-radius: 20px;
    }
    .admin-sidebar-footer { padding: 16px 20px; border-top: 1px solid rgba(255,255,255,.08); }
    .admin-user-row { display: flex; align-items: center; gap: 10px; }
    .admin-avatar {
      width: 36px; height: 36px; border-radius: 50%;
      background: var(--lime); display: flex; align-items: center;
      justify-content: center; font-weight: 700; font-size: .85rem;
      color: var(--black); flex-shrink: 0;
    }
    .admin-user-info { flex: 1; min-width: 0; }
    .admin-user-name { font-size: .82rem; font-weight: 600; color: #fff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .admin-user-role { font-size: .7rem; color: rgba(255,255,255,.4); }

    /* Main content */
    .admin-main { margin-left: 260px; flex: 1; display: flex; flex-direction: column; overflow: hidden; }

    /* Topbar */
    .admin-topbar {
      background: #fff; border-bottom: 1px solid #e8eaed;
      padding: 14px 32px; display: flex; align-items: center; gap: 16px;
      flex-shrink: 0;
    }
    .admin-topbar-title { font-size: 1.1rem; font-weight: 700; color: var(--black); flex: 1; }
    .admin-topbar-title span { color: rgba(10,10,10,.4); font-weight: 400; font-size: .9rem; margin-left: 8px; }
    .admin-search-bar {
      display: flex; align-items: center; gap: 8px;
      background: #f4f5f7; border: 1.5px solid #e8eaed;
      border-radius: 10px; padding: 8px 14px; font-size: .85rem;
      color: rgba(10,10,10,.5); width: 240px;
    }
    .topbar-actions { display: flex; align-items: center; gap: 8px; }
    .topbar-icon-btn {
      width: 38px; height: 38px; border-radius: 10px;
      background: #f4f5f7; border: 1.5px solid #e8eaed;
      display: flex; align-items: center; justify-content: center;
      font-size: 1rem; cursor: pointer; position: relative; transition: background .2s;
    }
    .topbar-icon-btn:hover { background: #eaedf0; }
    .topbar-icon-btn .notif-dot {
      position: absolute; top: 6px; right: 6px;
      width: 8px; height: 8px; background: var(--red);
      border-radius: 50%; border: 2px solid #fff;
    }

    /* Panel container */
    .admin-panel { display: none; padding: 28px 32px; }
    /* Active panels fill remaining height below the sticky topbar and scroll */
    .admin-panel.active {
      display: block;
      height: calc(100vh - 57px);
      overflow-y: auto;
      overflow-x: hidden;
    }

    /* ── Admin loading screen ──────────────────────────────── */
    #admin-loader {
      position: fixed; inset: 0; z-index: 9999;
      background: #0a0a0a;
      display: flex; flex-direction: column; align-items: center; justify-content: center;
      gap: 20px;
      transition: opacity .35s ease, visibility .35s ease;
    }
    #admin-loader.fade-out { opacity: 0; visibility: hidden; }
    .admin-loader-brand {
      font-family: var(--font-display);
      font-size: 1.8rem; color: #fff; letter-spacing: .5px;
    }
    .admin-loader-brand span { color: var(--lime); }
    .admin-loader-bar-wrap {
      width: 180px; height: 3px; background: rgba(255,255,255,.12);
      border-radius: 3px; overflow: hidden;
    }
    .admin-loader-bar {
      height: 100%; width: 0%;
      background: linear-gradient(90deg, var(--lime), var(--lime-dark));
      border-radius: 3px;
      animation: admin-load-fill 1.6s cubic-bezier(.4,0,.2,1) forwards;
    }
  
    @keyframes admin-load-fill {
      0%   { width: 0%; }
      40%  { width: 60%; }
      70%  { width: 80%; }
      100% { width: 100%; }
    }
    .admin-loader-label {
      font-size: .72rem; font-weight: 600; letter-spacing: 2px;
      text-transform: uppercase; color: rgba(255,255,255,.35);
    }
    /* Skeleton shimmer for the overview panel while data populates */
    @keyframes shimmer {
      0%   { background-position: -600px 0; }
      100% { background-position: 600px 0; }
    }
    .sk {
      background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
      background-size: 600px 100%;
      animation: shimmer 1.4s infinite linear;
      border-radius: 8px;
    }

    /* Page header */
    .panel-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; gap: 12px; flex-wrap: wrap; }
    .panel-header h1 { font-family: var(--font-display); font-size: 1.7rem; color: var(--black); }
    .panel-header p { font-size: .85rem; color: rgba(10,10,10,.5); margin-top: 2px; }

    /* KPI cards */
    .kpi-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 16px; margin-bottom: 28px; }
    .kpi-card {
      background: #fff; border-radius: 16px; padding: 22px 20px;
      border: 1.5px solid #e8eaed; position: relative; overflow: hidden;
    }
    .kpi-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; }
    .kpi-card.lime::before { background: var(--lime); }
    .kpi-card.red::before  { background: var(--red); }
    .kpi-card.blue::before { background: #4f94ea; }
    .kpi-card.amber::before{ background: #f59e0b; }
    .kpi-label { font-size: .75rem; font-weight: 600; letter-spacing: .5px; text-transform: uppercase; color: rgba(10,10,10,.4); margin-bottom: 8px; }
    .kpi-value { font-size: 1.8rem; font-weight: 700; color: var(--black); line-height: 1; }
    .kpi-sub { font-size: .78rem; color: rgba(10,10,10,.5); margin-top: 6px; display: flex; align-items: center; gap: 4px; }
    .kpi-sub .kpi-up   { color: #16a34a; font-weight: 600; }
    .kpi-sub .kpi-down { color: var(--red); font-weight: 600; }
    .kpi-icon { position: absolute; top: 20px; right: 18px; font-size: 1.8rem; opacity: .12; }

    /* Section card */
    .section-card { background: #fff; border-radius: 16px; border: 1.5px solid #e8eaed; overflow: hidden; margin-bottom: 24px; }
    .section-card-header {
      padding: 18px 22px; border-bottom: 1px solid #f0f2f4;
      display: flex; align-items: center; justify-content: space-between;
    }
    .section-card-header h3 { font-size: 1rem; font-weight: 700; color: var(--black); }
    .section-card-header p  { font-size: .78rem; color: rgba(10,10,10,.45); margin-top: 1px; }

    /* Tables */
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table th {
      padding: 11px 20px; text-align: left; font-size: .72rem;
      font-weight: 700; letter-spacing: .5px; text-transform: uppercase;
      color: rgba(10,10,10,.4); background: #fafbfc; border-bottom: 1px solid #f0f2f4;
    }
    .data-table td { padding: 14px 20px; font-size: .85rem; color: var(--black); border-bottom: 1px solid #f4f5f7; vertical-align: middle; }
    .data-table tr:last-child td { border-bottom: none; }
    .data-table tr:hover td { background: #fafbfc; }

    .product-thumb { width: 46px; height: 46px; border-radius: 10px; object-fit: cover; background: #eee; }
    .product-name-cell { font-weight: 600; }
    .product-brand-cell { color: rgba(10,10,10,.45); font-size: .8rem; }

    /* Tags */
    .tag-chip { display: inline-block; padding: 2px 8px; border-radius: 20px; font-size: .7rem; font-weight: 600; background: #f0f2f4; color: rgba(10,10,10,.6); margin: 2px; }
    .tag-chip.skin    { background: #d1fae5; color: #065f46; }
    .tag-chip.concern { background: #fee2e2; color: #991b1b; }
    .tag-chip.routine { background: #dbeafe; color: #1e40af; }
    .tag-chip.new     { background: var(--lime); color: var(--black); }

    /* Status badges */
    .status-badge { display: inline-flex; align-items: center; gap: 5px; padding: 3px 10px; border-radius: 20px; font-size: .72rem; font-weight: 600; }
    .status-badge::before { content: ''; width: 6px; height: 6px; border-radius: 50%; }
    .status-badge.active       { background: #d1fae5; color: #065f46; }
    .status-badge.active::before { background: #16a34a; }
    .status-badge.pending      { background: #fef3c7; color: #92400e; }
    .status-badge.pending::before { background: #f59e0b; }
    .status-badge.shipped      { background: #dbeafe; color: #1e40af; }
    .status-badge.shipped::before { background: #3b82f6; }
    .status-badge.cancelled    { background: #fee2e2; color: #991b1b; }
    .status-badge.cancelled::before { background: var(--red); }
    .status-badge.out-of-stock { background: #f3f4f6; color: #6b7280; }
    .status-badge.out-of-stock::before { background: #9ca3af; }

    /* Buttons */
    .action-btn {
      display: inline-flex; align-items: center; gap: 5px;
      padding: 5px 12px; border-radius: 8px; font-size: .78rem; font-weight: 600;
      cursor: pointer; border: 1.5px solid; background: transparent; transition: all .15s;
      font-family: inherit;
    }
    .action-btn.edit    { border-color: #e8eaed; color: var(--black); }
    .action-btn.edit:hover { background: #f4f5f7; }
    .action-btn.tag-btn { border-color: var(--lime); color: #555; }
    .action-btn.tag-btn:hover { background: var(--lime); color: var(--black); }
    .action-btn.danger  { border-color: #fee2e2; color: var(--red); }
    .action-btn.danger:hover { background: #fee2e2; }
    .action-btn.primary { background: var(--rose-dark); border-color: var(--rose-dark); color: #fff; }
    .action-btn.primary:hover { background: var(--rose); border-color: var(--rose); }
    .action-btn.delete  { border-color: #fee2e2; color: var(--red); }
    .action-btn.delete:hover { background: #fee2e2; }

    /* Grids */
    .dash-row   { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px; }
    .dash-row-3 { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 24px; }

    /* Chart placeholder */
    .chart-placeholder { padding: 24px 22px; min-height: 220px; display: flex; flex-direction: column; }
    .chart-bars { display: flex; align-items: flex-end; gap: 8px; flex: 1; padding-bottom: 8px; border-bottom: 1.5px solid #f0f2f4; }
    .chart-bar-group { flex: 1; display: flex; flex-direction: column; align-items: center; gap: 6px; }
    .chart-bar { width: 100%; border-radius: 6px 6px 0 0; min-height: 4px; }
    .chart-bar.lime { background: var(--lime); }
    .chart-bar.red  { background: var(--red); opacity: .7; }
    .chart-bar-label { font-size: .65rem; color: rgba(10,10,10,.4); }
    .chart-legend { display: flex; gap: 16px; margin-top: 12px; }
    .chart-legend-item { display: flex; align-items: center; gap: 6px; font-size: .75rem; color: rgba(10,10,10,.5); }
    .chart-legend-dot  { width: 8px; height: 8px; border-radius: 50%; }

    /* Donut */
    .donut-wrap { padding: 24px 22px; display: flex; flex-direction: column; align-items: center; }
    .donut-circle {
      width: 120px; height: 120px; border-radius: 50%;
      background: conic-gradient(var(--lime) 0% 38%, var(--red) 38% 62%, #4f94ea 62% 79%, #f59e0b 79% 100%);
      position: relative; margin: 12px 0;
    }
    .donut-circle::after { content: ''; position: absolute; top: 50%; left: 50%; transform: translate(-50%,-50%); width: 70px; height: 70px; background: #fff; border-radius: 50%; }
    .donut-labels { width: 100%; }
    .donut-row { display: flex; align-items: center; justify-content: space-between; padding: 5px 0; font-size: .78rem; }
    .donut-label-left { display: flex; align-items: center; gap: 8px; color: rgba(10,10,10,.6); }
    .donut-dot { width: 8px; height: 8px; border-radius: 50%; }
    .donut-pct { font-weight: 700; color: var(--black); }

    /* Modals */
    .modal-overlay {
      position: fixed; inset: 0; background: rgba(0,0,0,.55);
      z-index: 900; display: none; align-items: center;
      justify-content: center; padding: 24px;
    }
    .modal-overlay.open { display: flex; }
    .tag-modal { background: #fff; border-radius: 20px; width: 100%; max-width: 760px; max-height: 90vh; overflow-y: auto; }
    .tag-modal-header { padding: 22px 26px 18px; border-bottom: 1px solid #f0f2f4; display: flex; align-items: flex-start; gap: 14px; }
    .tag-modal-header img  { width: 60px; height: 60px; border-radius: 12px; object-fit: cover; background: #eee; }
    .tag-modal-header h2   { font-size: 1.1rem; font-weight: 700; }
    .tag-modal-header p    { font-size: .82rem; color: rgba(10,10,10,.45); }
    .tag-modal-close { margin-left: auto; width: 32px; height: 32px; border-radius: 8px; background: #f4f5f7; border: none; cursor: pointer; font-size: 1.1rem; display: flex; align-items: center; justify-content: center; }
    .tag-modal-body  { padding: 22px 26px; }
    .tag-section { margin-bottom: 22px; }
    .tag-section-title { font-size: .72rem; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: rgba(10,10,10,.4); margin-bottom: 10px; display: flex; align-items: center; gap: 8px; }
    .tag-section-title::after { content: ''; flex: 1; height: 1px; background: #f0f2f4; }
    .tag-checkboxes { display: flex; flex-wrap: wrap; gap: 8px; }
    .tag-check-label { display: flex; align-items: center; gap: 6px; padding: 6px 14px; border-radius: 20px; border: 1.5px solid #e8eaed; font-size: .8rem; font-weight: 500; cursor: pointer; transition: all .15s; user-select: none; }
    .tag-check-label:hover { border-color: var(--lime); }
    .tag-check-label input[type=checkbox] { display: none; }
    .tag-check-label.checked                { background: var(--rose-dark); border-color: var(--rose-dark); color: #fff; }
    .tag-check-label.checked.skin           { background: #065f46; border-color: #065f46; }
    .tag-check-label.checked.concern        { background: var(--red); border-color: var(--red); }
    .tag-check-label.checked.routine        { background: #1e40af; border-color: #1e40af; }
    .tag-check-label.checked.ingred         { background: #7c3aed; border-color: #7c3aed; }
    .tag-check-label.checked.climate        { background: #0891b2; border-color: #0891b2; }
    .tag-modal-footer { padding: 16px 26px; border-top: 1px solid #f0f2f4; display: flex; justify-content: flex-end; gap: 10px; }

    /* Forms */
    .form-grid  { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .form-grid.gap-14 { gap: 14px; }
    .form-grid.cols-3 { grid-template-columns: 1fr 1fr 1fr; }
    .form-grid.ratio-13-07 { grid-template-columns: 1.3fr .7fr; }
    .form-group { margin-bottom: 16px; }
    .form-group label { display: block; font-size: .8rem; font-weight: 600; margin-bottom: 6px; color: rgba(10,10,10,.6); }
    .form-input {
      width: 100%; padding: 10px 14px; border-radius: 10px; border: 1.5px solid #e8eaed;
      font-family: inherit; font-size: .88rem; color: var(--black); outline: none; transition: border .15s;
    }
    .form-input:focus { border-color: var(--lime); box-shadow: 0 0 0 3px rgba(212,217,148,.18); }
    select.form-input {
      appearance: none;
      background-image: url("data:image/svg+xml,%3Csvg width='12' height='8' viewBox='0 0 12 8' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1L6 6L11 1' stroke='%230A0A0A' stroke-width='1.5' stroke-linecap='round'/%3E%3C/svg%3E");
      background-repeat: no-repeat; background-position: right 14px center;
    }

    /* Mini stats */
    .mini-stats { display: grid; grid-template-columns: repeat(4,1fr); gap: 1px; background: #f0f2f4; border-radius: 14px; overflow: hidden; margin-bottom: 24px; }
    .mini-stats.cols-3 { grid-template-columns: repeat(3,1fr); }
    .mini-stats.cols-5 { grid-template-columns: repeat(5,1fr); }
    .mini-stat  { background: #fff; padding: 16px 20px; text-align: center; }
    .mini-stat-val   { font-size: 1.4rem; font-weight: 700; color: var(--black); }
    .mini-stat-label { font-size: .72rem; color: rgba(10,10,10,.4); margin-top: 2px; }

    /* Activity */
    .activity-list { padding: 4px 0; }
    .activity-item { display: flex; align-items: flex-start; gap: 12px; padding: 12px 22px; border-bottom: 1px solid #f4f5f7; }
    .activity-item:last-child { border-bottom: none; }
    .activity-dot { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: .9rem; flex-shrink: 0; margin-top: 1px; }
    .activity-dot.order  { background: #d1fae5; }
    .activity-dot.sub    { background: #dbeafe; }
    .activity-dot.review { background: #fef3c7; }
    .activity-dot.quiz   { background: #ede9fe; }
    .activity-dot.stock  { background: #fee2e2; }
    .activity-text { flex: 1; }
    .activity-text strong { font-size: .85rem; font-weight: 600; }
    .activity-text p      { font-size: .78rem; color: rgba(10,10,10,.45); margin-top: 1px; }
    .activity-time { font-size: .72rem; color: rgba(10,10,10,.35); white-space: nowrap; }

    /* Tier bars */
    .tier-progress-wrap { padding: 16px 22px; }
    .tier-row { display: flex; align-items: center; gap: 12px; margin-bottom: 14px; }
    .tier-name { font-size: .82rem; font-weight: 600; width: 110px; flex-shrink: 0; }
    .tier-bar-track { flex: 1; height: 8px; background: #f0f2f4; border-radius: 20px; overflow: hidden; }
    .tier-bar-fill  { height: 100%; border-radius: 20px; transition: width 1s cubic-bezier(.4,0,.2,1); }
    .tier-bar-fill.lime  { background: var(--lime); }
    .tier-bar-fill.red   { background: var(--red); }
    .tier-bar-fill.blue  { background: #4f94ea; }
    .tier-bar-fill.amber { background: #f59e0b; }
    .tier-count { font-size: .82rem; font-weight: 700; width: 30px; text-align: right; }

    /* Bundle builder */
    .bundle-builder { padding: 20px 22px; }
    .bundle-row { display: grid; grid-template-columns: 1fr auto; align-items: center; gap: 16px; padding: 14px 0; border-bottom: 1px solid #f4f5f7; }
    .bundle-row:last-child { border-bottom: none; }
    .bundle-meta strong { font-size: .9rem; font-weight: 700; }
    .bundle-meta p { font-size: .78rem; color: rgba(10,10,10,.45); margin-top: 2px; }
    .bundle-items-list { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 8px; }
    .bundle-item-chip  { padding: 3px 10px; background: #f4f5f7; border-radius: 20px; font-size: .72rem; color: rgba(10,10,10,.6); }
    .bundle-price { font-size: 1.1rem; font-weight: 700; white-space: nowrap; }
    .bundle-actions { display: flex; gap: 8px; margin-top: 8px; flex-wrap: wrap; align-items: center; }
    .bundle-summary { text-align: right; flex-shrink: 0; }
    .bundle-summary-top { display: flex; flex-direction: column; align-items: flex-end; gap: 2px; }
    .bundle-summary-sub { font-size: .75rem; color: rgba(10,10,10,.45); margin-top: 8px; }

    /* Choice grids (bundle/guide product pickers, etc.) */
    .choice-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 6px; max-height: 260px; overflow-y: auto; padding: 2px; }

    /* Subscriber */
    .subscriber-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 16px; padding: 20px 22px; }
    .sub-card { border: 1.5px solid #e8eaed; border-radius: 14px; padding: 16px; }
    .sub-card-top { display: flex; align-items: center; gap: 10px; margin-bottom: 12px; }
    .sub-avatar { width: 38px; height: 38px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: .85rem; }
    .sub-avatar.a { background: #d1fae5; color: #065f46; }
    .sub-avatar.b { background: #dbeafe; color: #1e40af; }
    .sub-avatar.c { background: #fef3c7; color: #92400e; }
    .sub-avatar.d { background: #ede9fe; color: #5b21b6; }
    .sub-avatar.e { background: #fee2e2; color: #991b1b; }
    .sub-avatar.f { background: #f0fdf4; color: #166534; }
    .sub-name { font-size: .88rem; font-weight: 700; }
    .sub-plan { font-size: .72rem; color: rgba(10,10,10,.45); }
    .sub-info-row { display: flex; justify-content: space-between; font-size: .78rem; color: rgba(10,10,10,.5); margin-bottom: 6px; }
    .sub-info-row strong { color: var(--black); font-size: .82rem; }

    /* Top products */
    .top-product-row  { display: flex; align-items: center; gap: 12px; padding: 12px 22px; border-bottom: 1px solid #f4f5f7; }
    .top-product-row:last-child { border-bottom: none; }
    .top-product-rank { width: 24px; font-size: .8rem; font-weight: 700; color: rgba(10,10,10,.35); text-align: center; }
    .top-product-thumb{ width: 40px; height: 40px; border-radius: 8px; object-fit: cover; }
    .top-product-info { flex: 1; }
    .top-product-info strong { font-size: .85rem; font-weight: 600; display: block; }
    .top-product-info span   { font-size: .75rem; color: rgba(10,10,10,.45); }
    .top-product-rev { font-size: .88rem; font-weight: 700; white-space: nowrap; }

    /* Add product modal */
    .add-modal { background: #fff; border-radius: 20px; width: 100%; max-width: 620px; max-height: 90vh; overflow-y: auto; }
    .add-modal-header { padding: 22px 26px 18px; border-bottom: 1px solid #f0f2f4; display: flex; align-items: center; justify-content: space-between; }
    .add-modal-header h2 { font-size: 1.1rem; font-weight: 700; }
    .add-modal-body  { padding: 22px 26px; }
    .add-modal-footer{ padding: 16px 26px; border-top: 1px solid #f0f2f4; display: flex; justify-content: flex-end; gap: 10px; }

    /* Upload area */
    .upload-area { border: 2px dashed #e8eaed; border-radius: 12px; padding: 32px; text-align: center; cursor: pointer; transition: border .2s, background .2s; margin-bottom: 16px; }
    .upload-area:hover { border-color: var(--rose); background: var(--blush-pale); }
    .upload-icon { font-size: 2rem; margin-bottom: 8px; }
    .upload-text { font-size: .85rem; color: rgba(10,10,10,.5); }
    .upload-text strong { color: var(--black); }

    /* Panel tabs */
    .panel-tabs { display: flex; gap: 0; border-bottom: 2px solid #e8eaed; margin-bottom: 24px; }
    .panel-tab  { padding: 10px 20px; font-size: .85rem; font-weight: 600; cursor: pointer; color: rgba(10,10,10,.45); border-bottom: 2px solid transparent; margin-bottom: -2px; transition: all .2s; white-space: nowrap; }
    .panel-tab:hover { color: var(--black); }
    .panel-tab.active { color: var(--rose); border-bottom-color: var(--rose); }
    .panel-tab-content { display: none; }
    .panel-tab-content.active { display: block; }

    /* Toggle */
    .toggle-wrap { display: flex; align-items: center; justify-content: space-between; padding: 14px 0; border-bottom: 1px solid #f4f5f7; }
    .toggle-wrap:last-child { border-bottom: none; }
    .toggle-info strong { font-size: .88rem; font-weight: 600; color: var(--black); display: block; }
    .toggle-info span   { font-size: .77rem; color: rgba(10,10,10,.45); margin-top: 1px; display: block; }
    .toggle { position: relative; width: 44px; height: 24px; flex-shrink: 0; }
    .toggle input { opacity: 0; width: 0; height: 0; }
    .toggle-slider { position: absolute; inset: 0; background: #e8eaed; border-radius: 12px; transition: .2s; cursor: pointer; }
    .toggle-slider::before { content: ''; position: absolute; width: 18px; height: 18px; left: 3px; bottom: 3px; background: #fff; border-radius: 50%; transition: .2s; box-shadow: 0 1px 3px rgba(0,0,0,.15); }
    .toggle input:checked + .toggle-slider { background: var(--rose); }
    .toggle input:checked + .toggle-slider::before { transform: translateX(20px); }

    /* Stars */
    .stars { color: #f59e0b; letter-spacing: 1px; }

    /* Announcements */
    .ann-item { display: flex; align-items: center; gap: 8px; padding: 10px 0; border-bottom: 1px solid #f4f5f7; }
    .ann-item:last-child { border-bottom: none; }
    .ann-drag { color: rgba(10,10,10,.22); cursor: grab; font-size: 1.1rem; flex-shrink: 0; }
    .ann-emoji-pick { width: 52px; padding: 8px 6px; text-align: center; font-size: 1.1rem; flex-shrink: 0; }
    .ann-text-field { flex: 1; }
    .ann-link-field { width: 160px; flex-shrink: 0; }

    /* Hero preview */
    .hero-preview-bar { background: linear-gradient(135deg,#1a1a1a,#2d2d2d); border-radius: 12px; padding: 20px 24px; color: #fff; margin-bottom: 20px; position: relative; overflow: hidden; }
    .hero-preview-bar::before { content: 'LIVE PREVIEW'; position: absolute; top: 10px; right: 12px; font-size: .58rem; font-weight: 700; letter-spacing: 1.5px; color: rgba(255,255,255,.2); }

    /* Content blocks */
    .content-block { border: 1.5px solid #e8eaed; border-radius: 14px; padding: 20px; margin-bottom: 20px; }
    .content-block-header { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; margin-bottom: 18px; }
    .content-block-header strong { font-size: .95rem; font-weight: 700; }
    .content-block-header span   { font-size: .78rem; color: rgba(10,10,10,.4); }

    /* Reviews */
    .review-row { display: flex; align-items: flex-start; gap: 14px; padding: 16px 22px; border-bottom: 1px solid #f4f5f7; }
    .review-row:last-child { border-bottom: none; }
    .review-body  { flex: 1; min-width: 0; }
    .review-title { font-size: .85rem; font-weight: 600; display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-bottom: 3px; }
    .review-text  { font-size: .82rem; color: rgba(10,10,10,.6); line-height: 1.5; margin-top: 4px; }
    .review-meta  { font-size: .72rem; color: rgba(10,10,10,.38); margin-top: 5px; }
    .review-actions { display: flex; flex-direction: column; gap: 6px; flex-shrink: 0; }

    /* Coupons */
    .coupon-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 16px; padding: 20px 22px; }
    .coupon-card { border: 2px dashed #e8eaed; border-radius: 14px; padding: 18px; position: relative; transition: border-color .2s; }
    .coupon-card.active-coupon { border-color: var(--lime); background: rgba(212,217,148,.03); }
    .coupon-code   { font-size: 1.15rem; font-weight: 700; letter-spacing: .08em; font-family: 'DM Sans',system-ui,sans-serif; margin-bottom: 10px; color: var(--black); }
    .coupon-detail { font-size: .78rem; color: rgba(10,10,10,.5); margin-bottom: 3px; }
    .coupon-badge  { position: absolute; top: 12px; right: 12px; }

    /* Flash sale */
    .flash-row { display: flex; align-items: center; gap: 12px; padding: 14px 0; border-bottom: 1px solid #f4f5f7; flex-wrap: wrap; }
    .flash-row:last-child { border-bottom: none; }
    .flash-discount-pill { background: var(--red); color: #fff; font-size: .72rem; font-weight: 700; padding: 3px 10px; border-radius: 20px; white-space: nowrap; }
    .flash-ends { font-size: .75rem; color: rgba(10,10,10,.4); white-space: nowrap; }

    /* Inventory */
    .inv-qty-input { width: 72px; padding: 6px 8px; border: 1.5px solid #e8eaed; border-radius: 8px; font-size: .88rem; font-weight: 700; text-align: center; transition: border .15s; }
    .inv-qty-input:focus { border-color: var(--black); outline: none; }
    .stock-bar-mini  { height: 5px; border-radius: 3px; background: #f0f2f4; width: 64px; overflow: hidden; display: inline-block; vertical-align: middle; }
    .stock-fill-mini { height: 100%; border-radius: 3px; }
    .stock-fill-mini.green { background: #16a34a; }
    .stock-fill-mini.amber { background: #f59e0b; }
    .stock-fill-mini.red   { background: var(--red); }

    /* Community */
    .community-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 16px; padding: 20px 22px; }
    .community-card { border-radius: 14px; border: 1.5px solid #e8eaed; overflow: hidden; transition: border-color .2s; }
    .community-card:hover { border-color: rgba(10,10,10,.25); }
    .community-card-img  { width: 100%; aspect-ratio: 1; object-fit: cover; background: #f0f2f4; display: block; }
    .community-card-body { padding: 12px; }
    .community-card-user    { font-size: .8rem; font-weight: 700; margin-bottom: 3px; color: var(--black); }
    .community-card-caption { font-size: .75rem; color: rgba(10,10,10,.5); line-height: 1.4; margin-bottom: 10px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .community-card-actions { display: flex; gap: 6px; }

    /* Alert banner */
    .alert-banner { background: #fff7ed; border: 1.5px solid #fed7aa; border-radius: 14px; padding: 14px 20px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px; }
    .alert-banner-icon { font-size: 1.3rem; flex-shrink: 0; }
    .alert-banner-text { flex: 1; }
    .alert-banner-text strong { font-size: .88rem; color: #c2410c; display: block; }
    .alert-banner-text span   { font-size: .78rem; color: rgba(10,10,10,.5); margin-top: 2px; display: block; }

    /* Routine */
    .routine-card { border: 1.5px solid #e8eaed; border-radius: 14px; padding: 18px; margin-bottom: 14px; display: flex; align-items: flex-start; gap: 14px; }
    .routine-card-avatar { width: 44px; height: 44px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: .9rem; flex-shrink: 0; }
    .routine-card-body   { flex: 1; min-width: 0; }
    .routine-card-name   { font-size: .9rem; font-weight: 700; color: var(--black); margin-bottom: 3px; }
    .routine-card-meta   { font-size: .75rem; color: rgba(10,10,10,.45); margin-bottom: 8px; }
    .routine-steps       { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 8px; }
    .routine-step-chip   { background: #f4f5f7; border-radius: 20px; padding: 3px 10px; font-size: .72rem; color: rgba(10,10,10,.6); display: flex; align-items: center; gap: 4px; }
    .routine-step-chip .step-num { background: var(--black); color: #fff; border-radius: 50%; width: 16px; height: 16px; display: inline-flex; align-items: center; justify-content: center; font-size: .6rem; font-weight: 700; }
    .routine-card-actions { display: flex; gap: 6px; }

    /* Loyalty */
    .loyalty-leaderboard-row { display: flex; align-items: center; gap: 12px; padding: 12px 22px; border-bottom: 1px solid #f4f5f7; }
    .loyalty-leaderboard-row:last-child { border-bottom: none; }
    .loyalty-rank { width: 28px; font-size: .82rem; font-weight: 700; color: rgba(10,10,10,.35); text-align: center; }
    .loyalty-rank.gold   { color: #f59e0b; }
    .loyalty-rank.silver { color: #94a3b8; }
    .loyalty-rank.bronze { color: #b45309; }
    .loyalty-user-info  { flex: 1; }
    .loyalty-user-info strong { font-size: .85rem; font-weight: 600; display: block; }
    .loyalty-user-info span   { font-size: .75rem; color: rgba(10,10,10,.45); }
    .loyalty-pts { font-size: .88rem; font-weight: 700; white-space: nowrap; }
    .loyalty-pts-adj { display: flex; align-items: center; gap: 6px; }
    .pts-adj-btn { width: 28px; height: 28px; border-radius: 7px; border: 1.5px solid #e8eaed; background: #fff; font-size: .85rem; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all .15s; }
    .pts-adj-btn:hover        { background: #f4f5f7; }
    .pts-adj-btn.plus:hover   { background: #d1fae5; border-color: #16a34a; color: #16a34a; }
    .pts-adj-btn.minus:hover  { background: #fee2e2; border-color: var(--red); color: var(--red); }
    .loyalty-tier-chip         { padding: 2px 8px; border-radius: 20px; font-size: .7rem; font-weight: 700; }
    .loyalty-tier-chip.starter { background: #f0f2f4; color: rgba(10,10,10,.6); }
    .loyalty-tier-chip.insider { background: #dbeafe; color: #1e40af; }
    .loyalty-tier-chip.luminary{ background: #fef3c7; color: #92400e; }

    /* Automation */
    .automation-rule   { display: flex; align-items: center; gap: 14px; padding: 14px 22px; border-bottom: 1px solid #f4f5f7; }
    .automation-rule:last-child { border-bottom: none; }
    .automation-icon   { width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0; }
    .automation-icon.email   { background: #dbeafe; }
    .automation-icon.sms     { background: #d1fae5; }
    .automation-icon.trigger { background: #ede9fe; }
    .automation-info { flex: 1; }
    .automation-info strong { font-size: .88rem; font-weight: 600; display: block; }
    .automation-info span   { font-size: .75rem; color: rgba(10,10,10,.45); }
    .automation-stats { text-align: right; flex-shrink: 0; }
    .automation-stats .open-rate  { font-size: .82rem; font-weight: 700; }
    .automation-stats .sent-count { font-size: .72rem; color: rgba(10,10,10,.4); margin-top: 1px; }

    /* Team & roles */
    .team-member-row { display: flex; align-items: center; gap: 14px; padding: 14px 22px; border-bottom: 1px solid #f4f5f7; }
    .team-member-row:last-child { border-bottom: none; }
    .team-avatar { width: 38px; height: 38px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: .85rem; flex-shrink: 0; }
    .team-member-info { flex: 1; }
    .team-member-info strong { font-size: .88rem; font-weight: 600; display: block; }
    .team-member-info span   { font-size: .75rem; color: rgba(10,10,10,.45); }
    .role-badge { display: inline-flex; align-items: center; gap: 5px; padding: 3px 10px; border-radius: 20px; font-size: .72rem; font-weight: 700; }
    .role-badge.super     { background: #fef3c7; color: #92400e; }
    .role-badge.ops       { background: #dbeafe; color: #1e40af; }
    .role-badge.support   { background: #d1fae5; color: #065f46; }
    .role-badge.marketing { background: #ede9fe; color: #5b21b6; }
    .role-badge.employee  { background: #f0f2f4; color: rgba(10,10,10,.6); }
    .permissions-matrix   { width: 100%; border-collapse: collapse; }
    .permissions-matrix th { padding: 10px 14px; font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; color: rgba(10,10,10,.4); background: #fafbfc; border-bottom: 1px solid #f0f2f4; text-align: center; }
    .permissions-matrix th:first-child { text-align: left; }
    .permissions-matrix td { padding: 10px 14px; font-size: .82rem; border-bottom: 1px solid #f4f5f7; text-align: center; }
    .permissions-matrix td:first-child { text-align: left; font-weight: 600; }
    .perm-yes     { color: #16a34a; font-size: 1rem; }
    .perm-no      { color: #d1d5db; font-size: 1rem; }
    .perm-limited { color: #f59e0b; font-size: .72rem; font-weight: 700; }

    /* Spa */
    .appointment-card { border: 1.5px solid #e8eaed; border-radius: 14px; padding: 16px; margin-bottom: 12px; display: flex; align-items: flex-start; gap: 12px; }
    .appt-date-box { background: var(--black); color: #fff; border-radius: 10px; padding: 10px 12px; text-align: center; flex-shrink: 0; min-width: 54px; }
    .appt-date-box .appt-day   { font-size: 1.3rem; font-weight: 700; line-height: 1; }
    .appt-date-box .appt-month { font-size: .65rem; font-weight: 600; text-transform: uppercase; letter-spacing: .5px; margin-top: 2px; opacity: .7; }
    .appt-info { flex: 1; }
    .appt-info strong { font-size: .9rem; font-weight: 700; display: block; margin-bottom: 3px; }
    .appt-info span   { font-size: .78rem; color: rgba(10,10,10,.45); }
    .appt-actions { display: flex; gap: 6px; }
    .service-card { border: 1.5px solid #e8eaed; border-radius: 14px; padding: 18px; margin-bottom: 12px; }
    .service-card-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px; }
    .service-card-header strong { font-size: .95rem; font-weight: 700; }
    .service-card-header .svc-price { font-size: 1rem; font-weight: 700; }
    .service-concerns { display: flex; flex-wrap: wrap; gap: 5px; margin-bottom: 10px; }
    .service-linked-products { display: flex; flex-wrap: wrap; gap: 6px; }
    .spa-rec-row { display: flex; align-items: flex-start; gap: 12px; padding: 14px 22px; border-bottom: 1px solid #f4f5f7; }
    .spa-rec-row:last-child { border-bottom: none; }
    .spa-rec-avatar { width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: .8rem; flex-shrink: 0; }
    .spa-rec-info { flex: 1; }
    .spa-rec-info strong { font-size: .85rem; font-weight: 600; display: block; margin-bottom: 3px; }
    .spa-rec-chips { display: flex; flex-wrap: wrap; gap: 5px; }

    /* Box / subscription modal */
    .box-product-row { display: flex; align-items: center; gap: 12px; padding: 10px 0; border-bottom: 1px solid #f4f5f7; }
    .box-product-row:last-child { border-bottom: none; }
    .box-product-thumb { width: 40px; height: 40px; border-radius: 8px; object-fit: cover; background: #eee; }
    .box-product-name  { flex: 1; font-size: .85rem; font-weight: 600; }
    .box-qty-input     { width: 60px; padding: 5px 8px; border: 1.5px solid #e8eaed; border-radius: 8px; font-size: .85rem; text-align: center; }

    /* User modal tabs */
    .user-modal-tabs { display: flex; gap: 0; border-bottom: 2px solid #f0f2f4; margin-bottom: 20px; }
    .user-modal-tab  { padding: 9px 18px; font-size: .82rem; font-weight: 600; cursor: pointer; color: rgba(10,10,10,.45); border-bottom: 2px solid transparent; margin-bottom: -2px; transition: all .2s; }
    .user-modal-tab.active { color: var(--rose); border-bottom-color: var(--rose); }
    .user-modal-tab-content { display: none; }
    .user-modal-tab-content.active { display: block; }

    /* Gift card admin grid */
    .gc-admin-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 16px; }
    .gc-admin-denomination { border: 2px dashed #e8eaed; border-radius: 14px; padding: 20px; text-align: center; }
    .gc-admin-denomination .amount  { font-size: 1.4rem; font-weight: 700; color: var(--black); margin-bottom: 4px; }
    .gc-admin-denomination .sold    { font-size: .78rem; color: rgba(10,10,10,.5); margin-bottom: 2px; }
    .gc-admin-denomination .revenue { font-size: .78rem; color: rgba(10,10,10,.35); }

    /* Toast notification */
    #admin-toast {
      position: fixed; bottom: 28px; right: 28px;
      background: var(--black); color: #fff;
      padding: 14px 20px; border-radius: 14px;
      display: flex; align-items: center; gap: 10px;
      font-size: .85rem; font-weight: 600;
      box-shadow: 0 8px 30px rgba(0,0,0,.25);
      transform: translateY(80px); opacity: 0;
      transition: all .35s cubic-bezier(.4,0,.2,1);
      z-index: 9999; pointer-events: none;
      max-width: 340px;
    }
    #admin-toast.show { transform: translateY(0); opacity: 1; }
    #admin-toast .toast-icon { font-size: 1.1rem; }

    /* ── Mobile burger + overlay ── */
    .admin-menu-btn {
      display: none;
      width: 38px; height: 38px; border-radius: 10px;
      background: #f4f5f7; border: 1.5px solid #e8eaed;
      align-items: center; justify-content: center;
      font-size: 1.1rem; cursor: pointer; flex-shrink: 0;
      transition: background .15s;
    }
    .admin-menu-btn:hover { background: #eaedf0; }
    .admin-sidebar-overlay {
      display: none;
      position: fixed; inset: 0;
      background: rgba(0,0,0,.55);
      z-index: 120;
      opacity: 0; visibility: hidden;
      transition: opacity .3s ease, visibility .3s ease;
    }
    .admin-sidebar-overlay.open { opacity: 1; visibility: visible; }

    /* Page-specific CSS (inserted before responsive overrides) */
    @stack('admin_page_css')

    /* ── Responsive ── */
    @media (max-width: 1200px) {
      .admin-sidebar { width: 220px; }
      .admin-main { margin-left: 220px; }
      .admin-search-bar { width: 180px; }
    }
    @media (max-width: 1100px) {
      .kpi-grid { grid-template-columns: repeat(2,1fr); }
      .dash-row { grid-template-columns: 1fr; }
      .dash-row-3 { grid-template-columns: 1fr; }
      .coupon-grid { grid-template-columns: repeat(2,1fr); }
      .community-grid { grid-template-columns: repeat(3,1fr); }
      .gc-admin-grid { grid-template-columns: repeat(2,1fr); }
      .mini-stats, .mini-stats.cols-3, .mini-stats.cols-5 { grid-template-columns: repeat(2,1fr); }
      .subscriber-grid { grid-template-columns: repeat(2,1fr); }
    }
    @media (max-width: 900px) {
      .admin-search-bar { display: none; }
      .kpi-grid { grid-template-columns: repeat(2,1fr); }
      .form-grid, .form-grid.cols-3, .form-grid.ratio-13-07 { grid-template-columns: 1fr; }
    }
    @media (max-width: 768px) {
      .admin-menu-btn { display: flex; }
      .admin-sidebar-overlay { display: block; }
      .admin-sidebar {
        display: flex !important;
        transform: translateX(-100%);
        transition: transform .3s cubic-bezier(.4,0,.2,1);
        z-index: 250; width: 260px;
      }
      .admin-sidebar.open { transform: translateX(0); }
      .admin-main { margin-left: 0; }
      .admin-topbar { padding: 12px 16px; gap: 10px; }
      .kpi-grid { grid-template-columns: 1fr 1fr; }
      .mini-stats, .mini-stats.cols-3, .mini-stats.cols-5 { grid-template-columns: repeat(2,1fr); }
      .subscriber-grid { grid-template-columns: 1fr; }
      .settings-grid { grid-template-columns: 1fr; }
      .admin-panel { padding: 16px; }
      .admin-panel.active { height: calc(100vh - 49px); }
      .community-grid { grid-template-columns: repeat(2,1fr); }
      .coupon-grid { grid-template-columns: 1fr 1fr; }
      .coupon-grid .coupon-card { padding: 14px; }
      .section-card { overflow: auto; }
      .data-table { min-width: 640px; }
      .panel-tabs { overflow-x: auto; scrollbar-width: none; flex-wrap: nowrap; -webkit-overflow-scrolling: touch; }
      .panel-tabs::-webkit-scrollbar { display: none; }
      .form-grid, .form-grid.cols-3, .form-grid.ratio-13-07 { grid-template-columns: 1fr; }
      .ann-item { flex-wrap: wrap; }
      .ann-link-field { width: 100%; }
      .bundle-row { grid-template-columns: 1fr; gap: 10px; }
      .bundle-summary { text-align: left; }
      .bundle-summary-top { align-items: flex-start; }
      .choice-grid { grid-template-columns: 1fr; }
      .panel-header { flex-direction: column; align-items: flex-start; gap: 10px; }
      .panel-header .topbar-actions,
      .panel-header > *:last-child { width: 100%; }
      .panel-header > .action-btn { justify-content: center; width: 100%; }
      .review-row { flex-wrap: wrap; }
      .review-actions { flex-direction: row; width: 100%; margin-top: 8px; }
      .appointment-card { flex-wrap: wrap; }
      .permissions-matrix { display: block; overflow-x: auto; }
      .admin-hide-xs { display: none !important; }
    }
    @media (max-width: 480px) {
      .kpi-grid { grid-template-columns: 1fr 1fr; }
      .mini-stats, .mini-stats.cols-3, .mini-stats.cols-5 { grid-template-columns: 1fr 1fr; }
      .coupon-grid { grid-template-columns: 1fr; }
      .gc-admin-grid { grid-template-columns: 1fr 1fr; }
      .community-grid { grid-template-columns: 1fr 1fr; }
      .admin-panel { padding: 12px; }
      .admin-topbar { padding: 10px 12px; }
      .admin-topbar-title { font-size: .9rem; }
      .kpi-value { font-size: 1.5rem; }
      .kpi-card { padding: 16px 14px; }
      .section-card-header { padding: 14px 16px; flex-wrap: wrap; gap: 8px; }
      .data-table th, .data-table td { padding: 10px 12px; }
      #admin-toast { bottom: 16px; right: 8px; left: 8px; max-width: none; }
      .tag-modal { border-radius: 16px; }
      .tag-modal-header, .tag-modal-body, .tag-modal-footer { padding: 16px; }
      .add-modal-header, .add-modal-body, .add-modal-footer { padding: 16px; }
      .activity-item, .loyalty-leaderboard-row, .top-product-row { padding: 10px 14px; }
    }
  </style>
</head>
<body>

<!-- ── Admin loading screen (removed once DOM + JS are ready) ── -->
<div id="admin-loader">
  <div class="admin-loader-brand">Komin<span>hoo</span></div>
  <div class="admin-loader-bar-wrap"><div class="admin-loader-bar"></div></div>
  <div class="admin-loader-label">Loading admin panel</div>
</div>

<div class="admin-wrapper">
  <!-- ── Sidebar ─────────────────────────────────────────────── -->
  <aside class="admin-sidebar" id="adminSidebar">
    <div class="admin-brand">
      <div class="logo-text">Komin<span>hoo</span><span class="admin-badge">Admin</span></div>
    </div>

    <nav class="admin-nav">
      <div class="admin-nav-section">Main</div>
      <div class="admin-nav-item active" onclick="switchAdminPanel('overview', this)">
        <span class="nav-icon">📊</span> Overview
      </div>
      <div class="admin-nav-item" onclick="switchAdminPanel('products', this)">
        <span class="nav-icon">🧴</span> Products
        <span class="nav-badge">20</span>
      </div>
      <div class="admin-nav-item" onclick="switchAdminPanel('bundles', this)">
        <span class="nav-icon">📦</span> Bundle Kits
      </div>
      <div class="admin-nav-item" onclick="switchAdminPanel('guides', this)">
        <span class="nav-icon">📖</span> Buying Guides
      </div>
      <div class="admin-nav-item" onclick="switchAdminPanel('routines', this)">
        <span class="nav-icon">✨</span> Routines
      </div>
      <div class="admin-nav-item" onclick="switchAdminPanel('inventory', this)">
        <span class="nav-icon">📦</span> Inventory
        <span class="nav-badge" style="background:#f59e0b;">3</span>
      </div>
      <div class="admin-nav-item" onclick="switchAdminPanel('orders', this)">
        <span class="nav-icon">🛒</span> Orders
        <span class="nav-badge">8</span>
      </div>

      <div class="admin-nav-section">Customers</div>
      <div class="admin-nav-item" onclick="switchAdminPanel('subscribers', this)">
        <span class="nav-icon">📬</span> Subscribers
      </div>
      <div class="admin-nav-item" onclick="switchAdminPanel('users', this)">
        <span class="nav-icon">👥</span> Users
      </div>
      <div class="admin-nav-item" onclick="switchAdminPanel('loyalty', this)">
        <span class="nav-icon">🏆</span> Loyalty & Members
      </div>
      <div class="admin-nav-item" onclick="switchAdminPanel('wallet', this)">
        <span class="nav-icon">💳</span> Wallet
      </div>
      <div class="admin-nav-item" onclick="switchAdminPanel('subscriptions', this)">
        <span class="nav-icon">📬</span> Subscriptions
      </div>
      <div class="admin-nav-item" onclick="switchAdminPanel('analytics', this)">
        <span class="nav-icon">📈</span> Analytics
      </div>
      <div class="admin-nav-item" onclick="switchAdminPanel('reviews', this)">
        <span class="nav-icon">⭐</span> Reviews
        <span class="nav-badge">14</span>
      </div>
      <div class="admin-nav-item" onclick="switchAdminPanel('community', this)">
        <span class="nav-icon">🖼️</span> Community
        <span class="nav-badge" id="comm-nav-badge">—</span>
      </div>

      <div class="admin-nav-section">Marketing</div>
      <div class="admin-nav-item" onclick="switchAdminPanel('promotions', this)">
        <span class="nav-icon">🏷️</span> Promotions
      </div>
      <div class="admin-nav-item" onclick="switchAdminPanel('influencers', this)">
        <span class="nav-icon">✨</span> Influencers
        <span class="nav-badge" id="inf-nav-badge" style="display:none"></span>
      </div>
      <div class="admin-nav-item" onclick="switchAdminPanel('content', this)">
        <span class="nav-icon">📝</span> Content Manager
      </div>
      <div class="admin-nav-item" onclick="switchAdminPanel('blog', this)">
        <span class="nav-icon">📰</span> Blog
      </div>
      <div class="admin-nav-item" onclick="switchAdminPanel('automation', this)">
        <span class="nav-icon">⚡</span> Automation
      </div>

      <div class="admin-nav-section">System</div>
      <div class="admin-nav-item" onclick="switchAdminPanel('security-events', this)">
        <span class="nav-icon">🛡️</span> Security Events
        <span class="nav-badge" id="sec-events-badge" style="display:none;background:#f59e0b"></span>
      </div>
      <div class="admin-nav-item" onclick="switchAdminPanel('roles', this)">
        <span class="nav-icon">🔐</span> Roles &amp; Permissions
      </div>
      <div class="admin-nav-item" onclick="switchAdminPanel('spa', this)">
        <span class="nav-icon">💆</span> Spa &amp; Clinic
      </div>
      <div class="admin-nav-item" onclick="switchAdminPanel('skin-results', this)">
        <span class="nav-icon">🔬</span> Skin Results
      </div>
      <div class="admin-nav-item" onclick="switchAdminPanel('quiz-config', this)">
        <span class="nav-icon">🎯</span> Quiz Config
      </div>
      <div class="admin-nav-item" onclick="switchAdminPanel('giftcards', this)">
        <span class="nav-icon">🎁</span> Gift Cards
      </div>
      <div class="admin-nav-item" onclick="switchAdminPanel('settings', this)">
        <span class="nav-icon">⚙️</span> Settings
      </div>
    </nav>

    <div class="admin-sidebar-footer">
      <div class="admin-user-row">
        <div class="admin-avatar">{{ strtoupper(substr($admin['name'] ?? 'K', 0, 1)) }}</div>
        <div class="admin-user-info">
          <div class="admin-user-name">{{ $admin['name'] ?? 'Admin' }}</div>
          <div class="admin-user-role">{{ $admin['role'] ?? 'Super Administrator' }}</div>
        </div>
        <form method="POST" action="{{ route('admin.logout') }}" style="margin:0;">
          @csrf
          <button type="submit" style="background:none;border:none;color:rgba(255,255,255,.4);cursor:pointer;font-size:.85rem;" title="Logout">⏻</button>
        </form>
      </div>
    </div>
  </aside>

  <!-- ── Main ──────────────────────────────────────────────────── -->
  <div class="admin-main">
    <!-- Topbar -->
    <div class="admin-topbar">
      <button class="admin-menu-btn" id="adminMenuBtn" title="Menu" aria-label="Open sidebar">☰</button>
      <div class="admin-topbar-title" id="adminPageTitle">
        Overview <span>/ Dashboard</span>
      </div>
      <div class="admin-search-bar">
        🔍 &nbsp; <input id="adminTopSearch" style="border:none;background:transparent;outline:none;font-size:.85rem;color:rgba(10,10,10,.7);width:100%;" placeholder="Search products, orders, users…" />
      </div>
      <div class="topbar-actions">
        <div class="topbar-icon-btn" title="Notifications">
          🔔 <span class="notif-dot"></span>
        </div>
        <div class="topbar-icon-btn admin-hide-xs" title="Settings" onclick="switchAdminPanel('settings', null)">⚙️</div>
        <a href="{{ route('home') }}" class="topbar-icon-btn admin-hide-xs" title="View Site" style="text-decoration:none;">🌐</a>
      </div>
    </div>
    <!-- Sidebar overlay (mobile) -->
    <div class="admin-sidebar-overlay" id="adminSidebarOverlay"></div>

    @yield('content')
  </div><!-- /admin-main -->
</div><!-- /admin-wrapper -->

<!-- Toast notification -->
<div id="admin-toast"><span class="toast-icon" id="toastIcon"></span><span id="toastMsg"></span></div>

@yield('modals')

<script src="{{ asset('js/app.js') }}"></script>
<script>
(function() {
  const menuBtn  = document.getElementById('adminMenuBtn');
  const sidebar  = document.getElementById('adminSidebar');
  const overlay  = document.getElementById('adminSidebarOverlay');
  if (!menuBtn || !sidebar || !overlay) return;

  function openSidebar() {
    sidebar.classList.add('open');
    overlay.classList.add('open');
    menuBtn.textContent = '✕';
    menuBtn.setAttribute('aria-label', 'Close sidebar');
    document.body.style.overflow = 'hidden';
  }
  function closeSidebar() {
    sidebar.classList.remove('open');
    overlay.classList.remove('open');
    menuBtn.textContent = '☰';
    menuBtn.setAttribute('aria-label', 'Open sidebar');
    document.body.style.overflow = '';
  }

  menuBtn.addEventListener('click', () =>
    sidebar.classList.contains('open') ? closeSidebar() : openSidebar()
  );
  overlay.addEventListener('click', closeSidebar);
  document.addEventListener('keydown', e => { if (e.key === 'Escape') closeSidebar(); });

  // Close sidebar when a nav item is clicked on mobile
  document.querySelectorAll('.admin-nav-item').forEach(item => {
    item.addEventListener('click', () => {
      if (window.innerWidth <= 768) closeSidebar();
    });
  });

  // Close on resize to desktop
  window.addEventListener('resize', () => {
    if (window.innerWidth > 768) {
      sidebar.classList.remove('open');
      overlay.classList.remove('open');
      document.body.style.overflow = '';
    }
  });
})();
</script>
@yield('scripts')
<script>
// Dismiss loading screen once the page is interactive
(function () {
  function dismissLoader() {
    var el = document.getElementById('admin-loader');
    if (el) {
      el.classList.add('fade-out');
      setTimeout(function () { el.remove(); }, 380);
    }
  }
  if (document.readyState === 'complete') {
    dismissLoader();
  } else {
    window.addEventListener('load', dismissLoader);
    // Fallback: always remove after 4 s even if load stalls
    setTimeout(dismissLoader, 4000);
  }
})();
</script>
</body>
</html>

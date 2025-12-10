@push('style')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .main-content{ flex:1; padding:24px 28px; overflow-y:auto; }
        .header{ display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap; margin-bottom:18px; }
        .welcome h2{ font-size:26px; margin:0 0 6px; }
        .welcome p{ color:var(--text-color-1); margin:0; }
        .search-bar{ display:flex; align-items:center; gap:8px; background:#fff; padding:10px 16px; border-radius:30px; box-shadow:var(--shadow-2); min-width:280px; border: 1px solid var(--border-1) }
        .search-bar input{ border:0; outline:0; width:100%; font-size:14px; }
        .search-bar i{ color:#a0a0a0; }

        .card, .metric-card, .insights, .booking-list, .chart { background:#fff; border-radius:16px; box-shadow:var(--shadow-2); }
        .card{ padding:22px; transition:all .25s ease; }
        .card:hover{ transform:translateY(-4px); box-shadow:0 8px 26px rgba(0,0,0,.12); }

        .stat-card{ display:flex; align-items:center; height:100%; border: 1px solid var(--border-1) }
        .stat-card .stat-info{display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 10px }
        .stat-icon{ width:60px; height:60px; border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:24px; margin-right:16px; }
        .booking-icon{ background:rgba(0,166,153,.12); color:var(--bs-secondary); }
        .revenue-icon{ background:rgba(255,90,95,.12); color:var(--primary-color); }
        .occupancy-icon{ background:rgba(255,180,0,.12); color:var(--bs-warning); }
        .rating-icon{ background:rgba(0,132,137,.12); color:var(--bs-success); }
        .stat-info h3{ font-size:26px; font-weight:700; margin:0 0 4px; }
        .stat-info p{ color:var(--bs-gray); margin:0; }
        .trend{ display:flex; gap:6px; align-items:center; margin-top:6px; font-size:13px; }
        .trend.up{ color:var(--bs-success); }.trend.up{ color:var(--bs-success); }
        .trend.down{ color:var(--bs-danger); }
        .trend.up i{ animation:pulseUp 1.6s infinite ease-in-out; }
        .trend.down i{ animation:pulseDown 1.6s infinite ease-in-out; }
        @keyframes pulseUp{0%{transform:translateY(0)}50%{transform:translateY(-3px)}100%{transform:translateY(0)}}
        @keyframes pulseDown{0%{transform:translateY(0)}50%{transform:translateY(3px)}100%{transform:translateY(0)}}

        .metrics-container{ display:grid; grid-template-columns:repeat(auto-fit,minmax(240px,1fr)); gap:16px; margin:14px 0 26px; }
        .metric-card{ padding:18px; text-align:center; transition:all .25s ease; }
        .metric-card:hover{ transform:translateY(-4px); }
        .metric-header{ display:flex; justify-content:center; align-items:center; gap:8px; margin-bottom:8px; }
        .metric-header h3{ font-size:16px; font-weight:600; margin:0; }
        .metric-header .hint{ color:#bdbdbd; font-size:14px; cursor:pointer; }
        .metric-value{ font-size:28px; font-weight:800; margin-bottom:6px; }
        .metric-comparison{ font-size:13px; color:var(--bs-gray); }

        .charts-container{ display:grid; grid-template-columns:2fr 1fr; gap:16px; margin-bottom:22px; }
        .chart{ padding:18px; }
        .chart-header{ display:flex; justify-content:space-between; gap:10px; align-items:center; margin-bottom:12px; }
        .chart-header h3{ font-size:18px; margin:0; }
        .chart-actions{ display:flex; gap:8px; align-items:center; }
        .chart-actions .btn-range{ border:1px solid #e7e7e7; background:#fff; padding:6px 10px; font-size:13px; border-radius:10px; cursor:pointer; }
        .chart-actions .btn-range.active{ border-color:var(--primary-color); color:var(--primary-color); }
        .chart-canvas{ height:280px; position:relative; }

        .chart-card {background: white;border-radius: 12px;box-shadow: 0 2px 16px rgba(0, 0, 0, 0.08);overflow: hidden;}
        .chart-header {display: flex;justify-content: space-between;align-items: center;padding: 20px 24px;border-bottom: 1px solid #f0f0f0;}
        .chart-header h3 {font-size: 18px;font-weight: 600;color: #1a1a1a;margin: 0;}
        .chart-actions {display: flex;gap: 8px;
            flex-wrap: wrap;
        }
        .btn-range {padding: 8px 16px;border: 1px solid #e0e0e0;background: white;border-radius: 6px;font-size: 14px;font-weight: 500;cursor: pointer;transition: all 0.2s;}
        .chart-content {padding: 24px;}
        .chart-summary {display: flex;gap: 32px;margin-bottom: 24px;}
        .summary-item {display: flex;flex-direction: column;gap: 4px;}
        .summary-item .label {font-size: 14px;color: #666;}
        .summary-item .value {font-size: 20px;font-weight: 600;color: #1a1a1a;}
        .chart-canvas {height: 300px;background: #fafafa;border-radius: 8px;}

        .transactions-section {background: white;border-radius: 12px;box-shadow: 0 2px 16px rgba(0, 0, 0, 0.08);overflow: hidden;position: relative;display: flex;flex-direction: column;max-width: 800px;margin: 0 auto;}
        .section-header {display: flex;justify-content: space-between;align-items: center;padding: 20px 24px;border-bottom: 1px solid #f0f0f0;background-color: #f8fafc;margin-bottom: 0 !important;}
        .section-header h3 {font-size: 20px;font-weight: 600;color: #1a1a1a;margin: 0;}
        .filter-options {display: flex;gap: 12px;}
        .filter-options select {padding: 8px 12px;border: 1px solid #e0e0e0;border-radius: 6px;font-size: 14px;background-color: white;}
        .transactions-list {padding: 0;}
        .transaction-card {display: flex;gap: 16px;padding: 20px 24px;border-bottom: 1px solid #f0f0f0;transition: background-color 0.2s ease;}
        .transaction-card:hover {background-color: #f8fafc;}
        .transaction-card:last-child {border-bottom: none;}
        .transaction-icon {display: flex;align-items: center;justify-content: center;width: 48px;height: 48px;background: #f0f7ff;border-radius: 10px;color: #4361ee;font-size: 20px;flex-shrink: 0;}
        .transaction-details {flex: 1;}
        .transaction-header {display: flex;justify-content: space-between;align-items: flex-start;margin-bottom: 12px;}
        .transaction-header h4 {font-size: 16px;font-weight: 600;color: #1a1a1a;margin: 0;}
        .amount {font-size: 18px;font-weight: 700;color: #25b865;}
        .transaction-meta {display: flex;gap: 20px;margin-bottom: 12px;flex-wrap: wrap;}
        .meta-item {display: flex;gap: 6px;}
        .meta-label {font-size: 14px;color: #666;font-weight: 500;}
        .meta-value {font-size: 14px;color: #1a1a1a;font-weight: 500;}
        .transaction-remarks {background: #f9f9f9;padding: 12px;border-radius: 6px;margin-top: 8px;}
        .transaction-remarks p {margin: 0;font-size: 14px;color: #555;}
        .btn-area {padding: 20px 24px;margin-top: auto;display: flex;justify-content: center;}
        .transaction-status {display: inline-flex;align-items: center;gap: 4px;padding: 4px 8px;border-radius: 4px;font-size: 12px;font-weight: 600;margin-left: 8px;}
        .status-completed {background-color: #e7f7ef;color: #25b865;}
        .status-pending {background-color: #fff4e6;color: #f59e0b;}
        .transaction-date {font-size: 14px;color: #666;margin-top: 4px;}
        .badge-success {display: inline-flex;align-items: center;gap: 5px;padding: 0px 5px;background-color: #d4edda;color: #155724;font-size: 14px;font-weight: 500;border-radius: 6px;border: 1px solid #c3e6cb;}
        @media (max-width: 768px) {  .section-header {flex-direction: column;align-items: flex-start;gap: 16px;}  .transaction-header {flex-direction: column;gap: 8px;}  .transaction-meta {flex-direction: column;gap: 8px;}  .filter-options {width: 100%;justify-content: space-between;}  .filter-options select {flex: 1;} .badge-success {font-size: 11px;} .transaction-header h4 {font-size: 13px;}  }

        .booking-list{ padding:16px 18px; }
        .booking-list-header{ display:flex; justify-content:space-between; align-items:center; margin-bottom:6px; }
        .booking-list-header h3{ font-size:18px; margin:0; }
        .booking-list-header a{ color:var(--primary-color); text-decoration:none; font-weight:600; font-size:14px; }
        .booking-item{ display:flex; align-items:center; gap:14px; padding:12px 4px; border-bottom:1px solid #f2f2f2; position:relative; }
        .booking-item:last-child{ border-bottom:0; }
        .booking-avatar{ width:44px; height:44px; border-radius:50%;}
        .booking-avatar img{ width:100%; height:100%; object-fit:cover; border-radius:50%;}
        .booking-info{ flex:1; min-width:0; }
        .booking-info h4{ font-size:15px; margin:0 0 4px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .booking-info p{ font-size:13px; color:var(--bs-gray); margin:0; }
        .booking-status{ padding:6px 10px; border-radius:18px; font-size:12px; font-weight:600; }
        .status-confirmed{ background:rgba(0,166,153,.12); color:var(--bs-secondary); }
        .status-pending{ background:rgba(255,180,0,.16); color:var(--bs-warning); }
        .status-checkin{ background:rgba(0,132,137,.12); color:var(--bs-success); }
        .booking-actions{ display:flex; gap:8px; margin-left:10px; }
        .booking-actions .btn-mini{ border:1px solid #e7e7e7; padding:6px 10px; font-size:12px; border-radius:10px; background:#fff; cursor:pointer; }
        .booking-actions .btn-mini.primary{ background:var(--primary-color); color:#fff; border-color:var(--primary-color); }
        .booking-actions .btn-mini:hover{color: #fff !important;}
        .booking-actions .btn-mini.details:hover{color: var(--text-color-1) !important;}
        #guestInfoSection{ height: 200px; overflow:auto; }
        .chart-content{height: 410px;}
        .chart-wrapper {width: 100%;height: 350px;max-width: 100%;}

        #bookingDetailsModal .booking-nav-tabs {border-bottom: 1px solid #dee2e6;}
        #bookingDetailsModal .nav-tabs-container {padding: 0 1rem;}
        #bookingDetailsModal .nav-tabs .nav-link {color: #495057;font-weight: 500;padding: 1rem 1.5rem;border: none;border-bottom: 3px solid transparent;transition: all 0.2s ease;}
        #bookingDetailsModal .nav-tabs .nav-link:hover {border-color: var(--border-2);color: var(--primary-color);}
        #bookingDetailsModal .nav-tabs .nav-link.active {color: var(--primary-color);background-color: transparent;border-bottom: 3px solid var(--primary-color);}
        #bookingDetailsModal .booking-info-grid {display: grid;grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));gap: 1.5rem;}
        #bookingDetailsModal .info-item {display: flex;align-items: flex-start;gap: 1rem;}
        #bookingDetailsModal .info-icon {width: 40px;height: 40px;border-radius: 8px;background-color: #e9f2ff;color: #4b6cc8;display: flex;align-items: center;justify-content: center;flex-shrink: 0;}
        #bookingDetailsModal .info-content label {font-size: 0.875rem;color: #6c757d;margin-bottom: 0.25rem;display: block;}
        #bookingDetailsModal .info-content p {margin-bottom: 0;font-weight: 500;}
        #bookingDetailsModal .guest-badges {display: flex;gap: 0.5rem;flex-wrap: wrap;}
        #bookingDetailsModal .guest-cards-container {display: flex;flex-direction: column;gap: 1rem;}
        #bookingDetailsModal .guest-card {display: flex;align-items: center;gap: 1rem;padding: 1rem;background-color: #f8f9fa;border-radius: 8px;transition: transform 0.2s ease, box-shadow 0.2s ease;}
        #bookingDetailsModal .guest-card:hover {transform: translateY(-2px);box-shadow: 0 4px 12px rgba(0,0,0,0.1);}
        #bookingDetailsModal .guest-avatar {width: 50px;height: 50px;border-radius: 50%;display: flex;align-items: center;justify-content: center;color: white;flex-shrink: 0;}
        #bookingDetailsModal .guest-details h6 {margin-bottom: 0.5rem;font-weight: 600;}
        #bookingDetailsModal .guest-info {display: flex;flex-wrap: wrap;gap: 0.75rem;}
        #bookingDetailsModal .guest-meta {font-size: 0.875rem;color: #6c757d;display: flex;align-items: center;gap: 0.25rem;}
        #bookingDetailsModal .payment-details {display: flex;flex-direction: column;gap: 1rem;}
        #bookingDetailsModal .payment-item {display: flex;justify-content: space-between;align-items: center;padding: 0.75rem 0;}
        #bookingDetailsModal .payment-item:last-child {border-bottom: none;}
        #bookingDetailsModal .payment-item.total {padding-top: 1.25rem;font-weight: 600;font-size: 1.1rem;}
        #bookingDetailsModal .payment-item.discount .payment-value {color: #dc3545;}
        #bookingDetailsModal .payment-item.status {border-top: 1px solid #dee2e6;padding-top: 1.25rem;}
        #bookingDetailsModal .payment-divider {height: 1px;background-color: #dee2e6;margin: 0.5rem 0;}
        #bookingDetailsModal .modal-footer .btn-3{padding: 6px 12px !important}

        .stripModalBtn .btn-3{padding: 8px 13px !important;}
        #countrySelect .select2-container--default .select2-selection--single {height: 42px!important;border-radius: 8px!important;border: 1px solid var(--border-1);padding: 5px 12px!important;font-size: 14px!important;transition: border-color 0.3s, box-shadow 0.3s!important;}
        #countrySelect .select2-container--default .select2-selection--single:focus, #countrySelect .select2-container--default .select2-selection--single:hover {border-color: #80bdff;box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);}
        #countrySelect .select2-container--default .select2-selection--single .select2-selection__placeholder {color: #6c757d;font-style: italic;}
        #countrySelect .select2-container--default .select2-results__option {padding: 8px 12px;font-size: 14px;}
        #countrySelect .select2-container--default .select2-results__option--highlighted {background-color: var(--primary-color) !important;color: #fff;}
        #countrySelect .select2-search--dropdown .select2-search__field {border-radius: 5px;}
        #countrySelect .select2-dropdown {max-height: 250px;overflow-y: auto;}
        #countrySelect .select2-dropdown::-webkit-scrollbar {width: 6px;}
        #countrySelect .select2-dropdown::-webkit-scrollbar-thumb {background-color: rgba(0,0,0,0.2);border-radius: 3px;}
        #countrySelect .select2-container--default .select2-selection--single .select2-selection__rendered {line-height: 29px !important;}


        @media (max-width: 768px) {  #bookingDetailsModal .booking-info-grid {grid-template-columns: 1fr;}  #bookingDetailsModal .guest-info {flex-direction: column;gap: 0.5rem;}  #bookingDetailsModal .nav-tabs .nav-link {padding: 0.75rem 0.5rem;font-size: 0.875rem;}  .transaction-card {flex-direction: column;}  .main-content {padding: 24px 15px;padding-bottom: 80px;}  .chart-content {height: 300px;}  }
        @media (max-width: 768px) { .chart-wrapper {height: 250px;}}
        @media (max-width: 480px) { .chart-wrapper {height: 200px;}}

        @media (max-width:1200px){ .charts-container{ grid-template-columns:1fr; } }
        @media (max-width:992px){  .sidebar{ width:80px; }  .logo h1, .nav-links span{ display:none; }  .nav-links a{ justify-content:center; }  }
        @media (max-width:767px){
            .header{ position:sticky; top:0; background:#fff; z-index:9; padding:12px 8px; border-bottom:1px solid #eee; }
            .search-bar{ width:100%; }
            .charts-container{ gap:12px; }
            .metrics-container{ grid-template-columns:1fr; }
            .booking-item {flex-direction: column;align-items: flex-start;}
            .booking-actions {margin-left: 0;}
            .booking-list-header {flex-direction: column;align-items: flex-start;}
        }
    </style>
@endpush

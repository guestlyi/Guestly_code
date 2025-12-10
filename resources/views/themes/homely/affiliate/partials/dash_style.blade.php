@push('style')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .affiliate-dashboard {
            padding: 2rem 0;
        }

        .greeting-title {
            font-weight: 700;
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
        }

        .greeting-subtitle {
            font-size: 0.95rem;
        }

        .promo-banner {
            background: linear-gradient(135deg, #f6f7ff 0%, #eef0ff 100%);
            border-left: 4px solid #6366F1;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            font-size: 0.95rem;
        }

        .promo-icon {
            background-color: #6366F1;
            color: white;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .metric-card {
            border: none;
            border-radius: 12px;
            overflow: hidden;
            height: 100%;
            display: flex;
            flex-direction: column;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .metric-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .metric-content {
            padding: 1.5rem;
            flex-grow: 1;
        }

        .metric-footer {
            padding: 1rem 1.5rem;
            background-color: rgba(0, 0, 0, 0.03);
            border-top: 1px solid rgba(0, 0, 0, 0.05);
        }

        .metric-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
        }

        .earnings-card .metric-icon {
            background-color: rgba(99, 102, 241, 0.1);
            color: #6366F1;
        }

        .clicks-card .metric-icon {
            background-color: rgba(16, 185, 129, 0.1);
            color: #10B981;
        }

        .balance-card .metric-icon {
            background-color: rgba(245, 158, 11, 0.1);
            color: #F59E0B;
        }

        .metric-label {
            font-size: 0.9rem;
            color: #6c757d;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .metric-value {
            font-weight: 700;
            font-size: 1.8rem;
            margin-bottom: 0.75rem;
        }

        .metric-trend {
            font-size: 0.85rem;
        }

        .metric-trend.positive {
            color: #10B981;
        }

        .metric-trend.negative {
            color: #EF4444;
        }

        .metric-link {
            font-size: 0.85rem;
            color: #6366F1;
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        .metric-link i {
            margin-left: 0.25rem;
            font-size: 0.7rem;
        }

        .progress {
            border-radius: 10px;
        }

        .progress-bar {
            border-radius: 10px;
        }

        .performance-tips .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #eee;
        }

        .tip-item {
            padding: 1rem;
            background-color: #f8f9fa;
            border-radius: 8px;
        }

        .tip-icon {
            font-size: 1.25rem;
            margin-top: 3px;
        }

        .action-card {
            border: none;
            border-radius: 12px;
            padding-top: 25px;
            overflow: hidden;
            height: 100%;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .action-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            background-color: rgba(99, 102, 241, 0.1);
            color: #6366F1;
        }

        .action-title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .product-thumb {
            width: 30px;
            height: 30px;
            border-radius: 4px;
            object-fit: cover;
        }

        .activity-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            color: #6366F1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .activity-item {
            padding-bottom: 1rem;
            border-bottom: 1px solid #eee;
        }

        .activity-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
            margin-bottom: 0;
        }

        .btn-primary:hover {
            color: #fff !important;
        }

        @media (max-width: 768px) {
            .greeting-title {
                font-size: 1.5rem;
            }

            .metric-value {
                font-size: 1.5rem;
            }

            .metric-icon {
                width: 50px;
                height: 50px;
                font-size: 1.25rem;
                margin-bottom: 1rem;
            }
        }

        .scrollable-table {
            max-height: 300px;
            overflow-y: auto;
        }

        .scrollable-activity {
            max-height: 400px;
            overflow-y: auto;
        }

        .scrollable-activity .activity-item {
            padding-right: 20px;
        }

        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .stripModalBtn .btn-3{
            padding: 8px 13px !important;
        }

        #countrySelect .select2-container--default .select2-selection--single {
            height: 42px!important;
            border-radius: 8px!important;
            border: 1px solid var(--border-1);
            padding: 5px 12px!important;
            font-size: 14px!important;
            transition: border-color 0.3s, box-shadow 0.3s!important;
        }

        #countrySelect .select2-container--default .select2-selection--single:focus,
        #countrySelect .select2-container--default .select2-selection--single:hover {
            border-color: #80bdff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        #countrySelect .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #6c757d;
            font-style: italic;
        }

        #countrySelect .select2-container--default .select2-results__option {
            padding: 8px 12px;
            font-size: 14px;
        }

        #countrySelect .select2-container--default .select2-results__option--highlighted {
            background-color: var(--primary-color) !important;
            color: #fff;
        }
        #countrySelect .select2-search--dropdown .select2-search__field {
            border-radius: 5px;
        }
        #countrySelect .select2-dropdown {
            max-height: 250px;
            overflow-y: auto;
        }
        #countrySelect .select2-dropdown::-webkit-scrollbar {
            width: 6px;
        }
        #countrySelect .select2-dropdown::-webkit-scrollbar-thumb {
            background-color: rgba(0,0,0,0.2);
            border-radius: 3px;
        }
        #countrySelect .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 29px !important;
        }
        .itemListBtn{
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* ---------------------------------
        responsive
        ---------------------------------- */
        /* MackBook */
        @media only screen and (max-width: 1440px) {

        }

        /* Large screen  */
        @media only screen and (min-width: 1200px) and (max-width: 1319px) {

        }

        /* Medium screen  */
        @media only screen and (min-width: 992px) and (max-width: 1199px) {

        }

        /* Tablet Layout: 768px. */
        @media only screen and (min-width: 768px) and (max-width: 991px) {

        }

        /* Mobile Layout: 320px. */
        @media only screen and (max-width: 767px) {
            .promo-banner-inner {
                flex-direction: column;
                align-items: flex-start !important;
                gap: 10px;
            }

            .promo-banner-inner .ms-3 {
                margin-left: 0 !important;
            }

            .section-header.d-flex.justify-content-between.align-items-center.mb-4 {
                flex-direction: column;
                align-items: flex-start !important;
            }

            .activity-icon {
                width: 64px;
            }


        }

        /* ---------------------------------
        responsive
        ---------------------------------- */

        .section-title {
            font-size: 24px !important;
        }
    </style>
@endpush

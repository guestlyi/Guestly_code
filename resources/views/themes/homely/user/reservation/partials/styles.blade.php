@push('style')
    <style>
        .listing-image-container {
            gap: 7px;
        }
        .listing-container{
            margin-top: 15px;
        }
        .reservation-nav .nav-link.active{
            border: 1px solid var(--primary-color) !important;
        }
        .reservations-date{
            cursor: pointer;
        }
        .reservations-date-icon{
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .list-group-item{
            border: none;
        }

        .booking-details p {
            margin-bottom: 0.75rem;
            font-size: 0.95rem;
        }

        .booking-header h5 {
            font-size: 1.25rem;
        }

        .modal-content {
            border-radius: 0.75rem;
            box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.1);
        }
        .modalHead{
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .listing-top{
            padding: 0!important;
        }
        .rounded-circle{
            height: 40px;
            width: 40px;
        }
        .booking-nav-tabs {
            border-bottom: 1px solid #dee2e6;
        }

        .nav-tabs-container {
            padding: 0 1rem;
        }

        .nav-tabs .nav-link {
            color: #495057;
            font-weight: 500;
            padding: 1rem 1.5rem;
            border: none;
            border-bottom: 3px solid transparent;
            transition: all 0.2s ease;
        }

        .nav-tabs .nav-link:hover {
            border-color: var(--border-2);
            color: var(--primary-color);
        }

        .nav-tabs .nav-link.active {
            color: var(--primary-color);
            background-color: transparent;
            border-bottom: 3px solid var(--primary-color);
        }

        .booking-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .info-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }

        .info-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background-color: #e9f2ff;
            color: #4b6cc8;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .info-content label {
            font-size: 0.875rem;
            color: #6c757d;
            margin-bottom: 0.25rem;
            display: block;
        }

        .info-content p {
            margin-bottom: 0;
            font-weight: 500;
        }

        .guest-badges {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .guest-cards-container {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .guest-card {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background-color: #f8f9fa;
            border-radius: 8px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .guest-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .guest-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            flex-shrink: 0;
        }

        .guest-details h6 {
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .guest-info {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .guest-meta {
            font-size: 0.875rem;
            color: #6c757d;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .payment-details {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .payment-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
        }

        .payment-item:last-child {
            border-bottom: none;
        }

        .payment-item.total {
            padding-top: 1.25rem;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .payment-item.discount .payment-value {
            color: #dc3545;
        }

        .payment-item.status {
            border-top: 1px solid #dee2e6;
            padding-top: 1.25rem;
        }

        .payment-divider {
            height: 1px;
            background-color: #dee2e6;
            margin: 0.5rem 0;
        }

        @media (max-width: 768px) {
            .booking-info-grid {
                grid-template-columns: 1fr;
            }

            .guest-info {
                flex-direction: column;
                gap: 0.5rem;
            }

            .nav-tabs .nav-link {
                padding: 0.75rem 0.5rem;
                font-size: 0.875rem;
            }
        }
    </style>
@endpush

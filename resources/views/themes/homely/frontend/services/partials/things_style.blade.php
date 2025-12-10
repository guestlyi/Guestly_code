@push('style')
    <style>
        .service-details-things-container .service-details-things {
            font-family: 'Segoe UI', Roboto, sans-serif;
            color: #333;
        }

        .service-details-things-container .service-details-things-container {
            padding: 40px 0;
            border-top: 1px solid #e0e0e0;
            margin: 0 auto;
        }

        .service-details-things-container .things-title {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 30px;
            color: #222;
        }

        .service-details-things-container .sections-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .service-details-things-container .section-card {
            background: #fff;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .service-details-things-container .section-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .service-details-things-container .section-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 15px;
            color: #222;
            padding-bottom: 10px;
            border-bottom: 1px solid #f0f0f0;
        }

        .service-details-things-container .section-list {
            list-style: none;
            padding: 0;
            margin: 0 0 20px 0;
        }

        .service-details-things-container .section-list li {
            padding: 8px 0;
            position: relative;
            padding-left: 20px;
            line-height: 1.5;
        }

        .service-details-things-container .section-list li:before {
            content: "•";
            color: #008489;
            position: absolute;
            left: 0;
            font-weight: bold;
        }

        .service-details-things-container .show-more-btn {
            background: none;
            border: none;
            color: #008489;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            padding: 5px 0;
            display: flex;
            align-items: center;
            transition: color 0.2s ease;
        }

        .service-details-things-container .show-more-btn:hover {
            color: #006569;
        }

        .arrow {
            margin-left: 5px;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            .sections-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .section-card {
                padding: 20px;
            }

            .things-title {
                font-size: 24px;
            }
        }
        .safety-items-container {
            padding: 0 1rem;
        }

        .category-title {
            font-size: 1rem;
            font-weight: 600;
            color: #333;
        }

        .items-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 0.75rem;
            padding: 1rem 0.5rem;
        }

        .safety-item {
            display: flex;
            align-items: flex-start;
            padding: 0.25rem 0;
        }

        .item-bullet {
            color: #0d6efd;
            margin-right: 0.5rem;
            font-weight: bold;
        }

        .item-text {
            font-size: 0.95rem;
        }

        .modal-lg {
            max-width: 800px;
        }
        ul p{
            margin-bottom: 0;
        }
        .cancellation-policy-details {
            font-size: 15px;
            line-height: 1.6;
        }
        .policy-section-title {
            font-weight: 600;
            margin-bottom: 1rem;
            color: #333;
        }
        .policy-terms-list {
            list-style: none;
            padding-left: 0;
        }
        .policy-term-item {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 8px;
            background-color: #f9f9f9;
            border-left: 4px solid #ddd;
        }
        .term-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        .term-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
        }
        .full-refund {
            background-color: #e6f7ee;
            color: #0d6e41;
            border: 1px solid #b8e6d0;
        }
        .partial-refund {
            background-color: #fff8e6;
            color: #b38a00;
            border: 1px solid #ffe8a1;
        }
        .no-refund {
            background-color: #ffebee;
            color: #c62828;
            border: 1px solid #ffcdd2;
        }
        .term-days {
            font-size: 0.9rem;
            color: #666;
        }
        .term-description {
            color: #444;
        }
        .show-more-btn {
            background: none;
            border: none;
            color: var(--primary-color);
            font-weight: 500;
            padding: 0;
            cursor: pointer;
        }
        .show-more-btn .arrow {
            transition: transform 0.2s;
        }
        .show-more-btn:hover .arrow {
            transform: translateX(3px);
        }
        .house-rules-list {
            list-style: none;
            padding: 30px;
            margin: 0;
        }

        .house-rules-list li {
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .house-rules-list li:last-child {
            border-bottom: none;
        }
        .rules-section {
            padding: 20px 0;
            border-bottom: 1px solid #f5f5f5;
        }

        .rules-section:last-child {
            border-bottom: none;
        }

        .section-heading {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 16px;
            color: #333;
            display: flex;
            align-items: center;
        }

        .section-heading i {
            margin-right: 12px;
            color: #4CAF50;
        }

        .rules-list {
            list-style: none;
            padding: 0 30px 30px 30px;
            margin: 0;
        }

        .rules-list li {
            padding: 12px 0;
            display: flex;
            align-items: flex-start;
            border-bottom: 1px solid #f9f9f9;
        }

        .rules-list li:last-child {
            border-bottom: none;
        }

        .rule-icon {
            color: #4CAF50;
            margin-right: 12px;
            font-size: 1.1rem;
            flex-shrink: 0;
            margin-top: 2px;
        }
    </style>
@endpush

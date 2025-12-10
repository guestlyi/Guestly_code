@push('style')
    <link rel="stylesheet" href="{{ asset(template(true) . "css/flatpickr.min.css") }}"/>
    <style>
        section.listing{
            padding-bottom: 0 !important;
        }
        .input-box{
            margin-top: 0 !important;
        }
        .inner-row-kyc{
            padding: 25px 10px 10px;
        }
        .custom-image-uploader .upload-box {
            border: 2px dashed #ccc;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            position: relative;
            height: 200px;
            width: 100%;
            max-width: 400px;
            margin: 10px 3px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border-radius: 10px;
            background: #f9f9f9;
        }

        .custom-image-uploader .upload-placeholder {
            color: #999;
            font-size: 14px;
        }

        .custom-image-uploader .upload-placeholder i {
            font-size: 24px;
            margin-bottom: 8px;
        }

        .custom-image-uploader .image-preview {
            max-height: 100%;
            max-width: 100%;
            width: 100%;
            object-fit: cover;
            position: absolute;
            top: 0;
            left: 0;
        }
        .personal-info-title{
            margin-bottom: 0 !important;
        }
        .kyc-card {
            max-width: 500px;
            margin: 50px auto;
            padding: 30px 25px;
            background: #f9f9f9;
            border-radius: 12px;
            position: relative;
            transition: all 0.3s ease-in-out;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
            text-align: center;
        }

        .kyc-card:hover {
            transform: scale(1.02);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }

        .kyc-card.status-pending {
            box-shadow: 0 0 12px rgba(255, 193, 7, 0.3);
            border-left: 5px solid #ffc107;
        }

        .kyc-card.status-approved {
            box-shadow: 0 0 12px rgba(40, 167, 69, 0.3);
            border-left: 5px solid #28a745;
        }

        .kyc-card.status-pending i {
            animation: pulse 1.5s infinite;
        }

        .animated-check {
            animation: bounceIn 0.8s ease-in-out;
        }

        .kyc-card .progress {
            height: 5px;
            margin-top: 20px;
            border-radius: 10px;
            background: #eee;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        @keyframes bounceIn {
            0% { transform: scale(0.3); opacity: 0; }
            50% { transform: scale(1.05); opacity: 1; }
            70% { transform: scale(0.95); }
            100% { transform: scale(1); }
        }
        .kyc-card small {
            display: block;
            margin-top: 15px;
            font-size: 0.85rem;
            color: #6c757d;
        }

        @media (max-width: 576px) {
            .kyc-card {
                padding: 20px 15px;
            }

            .kyc-card i.fs-1 {
                font-size: 2rem !important;
            }

            .kyc-card h5 {
                font-size: 1.1rem;
            }
        }
        .search-bar .card-header{
            padding: 23px 23px 0 23px;
            border: none !important;
            background: transparent !important;
        }
    </style>
@endpush

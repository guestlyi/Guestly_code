@push('style')
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <style>
        #priceDetailsModal .price-summary {
            font-size: 16px;
            line-height: 1.5;
            color: #333;
        }

        #priceDetailsModal .price-summary .row {
            align-items: center;
        }

        #priceDetailsModal .price-summary .text-end {
            color: #444;
        }

        #priceDetailsModal .price-summary hr {
            border-top: 1px solid #ddd;
            margin: 1rem 0;
        }

        #priceDetailsModal .modal-content {
            border-radius: 1rem;
            overflow: hidden;
        }
        .modal-footer .btn-3 {
            padding: 6px 15px !important;
        }
        .image-upload-section {
            margin-bottom: 15px;
        }

        .image-upload-wrapper {
            border: 2px dashed #ccc;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            border-radius: 10px;
            position: relative;
            transition: border-color 0.3s;
            overflow: hidden;
            background-color: #fafafa;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }

        .image-upload-wrapper:hover,
        .image-upload-wrapper.border-highlight {
            border-color: #999;
        }

        .upload-placeholder {
            text-align: center;
            color: #666;
        }

        .upload-placeholder i {
            font-size: 24px;
            color: #aaa;
            margin-bottom: 5px;
        }

        .preview-inside {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            width: 100%;
            justify-content: flex-start;
            align-items: flex-start;
        }

        .image-preview-box {
            position: relative;
            width: 100px;
            height: 100px;
            border-radius: 8px;
            overflow: hidden;
        }

        .image-preview-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .remove-preview-btn {
            position: absolute;
            top: 4px;
            right: 4px;
            background-color: rgba(255, 255, 255, 0.8);
            border: none;
            font-weight: bold;
            padding: 2px 6px;
            border-radius: 50%;
            cursor: pointer;
            z-index: 2;
        }

        .share-modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            z-index: 1050;
            display: flex;
            justify-content: center;
            align-items: center;
            backdrop-filter: blur(2px);
        }

        .share-modal-content {
            background: #fff;
            padding: 24px 32px;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 360px;
            position: relative;
            animation: fadeInUp 0.3s ease;
            text-align: center;
        }

        .modal-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .close-btn {
            position: absolute;
            top: 12px;
            right: 12px;
            background: #f2f2f2;
            border: none;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background 0.2s;
        }

        .close-btn:hover {
            background: #e0e0e0;
        }

        .share-options {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 14px;
        }

        .share-options a,
        .share-options button {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #f8f8f8;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 14px;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            color: #333;
            text-decoration: none;
        }

        .share-options a:hover,
        .share-options button:hover {
            background: #eaeaea;
        }

        .share-options .facebook { color: #1877f2; }
        .share-options .twitter { color: #1da1f2; }
        .share-options .linkedin { color: #0a66c2; }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade.shareModalCs:not(.show) {
            opacity: 1;
        }
        .map-container {
            height: 450px;
            width: 100%;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: relative;
        }

        /* Fullscreen mode */
        .map-container.fullscreen {
            position: fixed !important;
            top: 0;
            left: 0;
            width: 100vw !important;
            height: 100vh !important;
            border-radius: 0 !important;
            z-index: 9999;
        }

        /* Expand button */
        .expand-map-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 10001;
            padding: 8px 12px;
            background: #fff;
            border: 1px solid #ccc;
            border-radius: 50%;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(0,0,0,0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }

        .expand-map-btn i {
            pointer-events: none; /* Ensure icon doesn't block clicks */
        }

        /* Combined Marker */
        .combined-marker {
            position: absolute;
            cursor: default;
            z-index: 100;
        }

        .marker-content {
            display: flex;
            flex-direction: column;
            align-items: end;
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            padding: 0;
            min-width: 180px;
            max-width: 290px;
            width: 100% !important;
        }

        .marker-image-container {
            width: 217px;
            height: 187px;
        }
        .marker-image-container img{
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 8px;
        }
        .marker-text {
            flex: 1;
            min-width: 0;
            padding: 16px !important;
            width: 100%;
        }
         .marker-address svg{
             height: 14px;
         }

        .marker-title {
            font-weight: 400;
            font-size: 16px;
            color: #333;
            margin-bottom: 10px;
        }

        .marker-address {
            font-size: 14px;
            color: #666;
            overflow: hidden;
            margin-top: 2px;
        }
        .marker-address span{
            cursor: pointer;
        }

        .marker-price {
            font-size: 11px;
            color: #1a73e8;
            font-weight: bold;
            margin-top: 2px;
        }

        /* Search wrapper */
        .map-search-wrapper {
            position: absolute !important;
            top: 10px !important;
            left: 10px !important;
            z-index: 10000 !important;
            display: flex;
            align-items: start;
        }

        .map-search-toggle {
            background: #fff;
            border: 1px solid #ccc;
            border-radius: 50%;
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(0,0,0,0.25);
            z-index: 10001;
        }

        .map-search-toggle i {
            font-size: 18px;
            color: #555;
        }

        .map-search-container {
            margin-left: 8px;
            display: flex;
            flex-direction: column;
        }

        .hidden {
            display: none;
        }

        .map-search-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            padding: 12px;
            width: 280px;
            display: flex;
            flex-direction: column;
        }

        #mapSearchInput {
            padding: 10px 12px;
            border-radius: 8px;
            border: 1px solid #ccc;
            outline: none;
            font-size: 14px;
            margin-bottom: 10px;
        }

        #mapSearchInput:focus {
            border-color: #007bff;
            box-shadow: 0 0 8px rgba(0,123,255,0.4);
        }

        .map-search-results {
            max-height: 200px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .search-instruction {
            padding: 10px;
            font-size: 13px;
            line-height: 1.4;
            color: #555;
            background: #f7f7f7;
            border-radius: 8px;
        }

        .search-instruction strong {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #333;
        }

        .search-result-item {
            padding: 8px 10px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 5px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .search-result-item:hover {
            background: #e0e0ff;
        }
        .search-result-item:hover .search-icon {
            border: 1px solid #d1d1d1;
        }
        .search-result-item .search-icon{
            border: 1px solid var(--border-2);
            border-radius: 5px;
            padding: 6px;
        }
        .search-result-item .search-text .search-title{
            font-size: 16px;
        }
        .search-result-item .search-text .search-address{
            font-size: 12px;
        }

        #dynamicTravelToggle .mode-btn {
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        #dynamicTravelToggle .mode-btn .duration {
            font-weight: bold;
        }
        #fullscreenMapContainer {
            transition: opacity 0.3s ease;
        }
        .map-search-container.fullscreen {
            position: absolute;
            top: 20px;
            left: 20px;
            z-index: 1000;
            width: 300px;
        }

        .fa-expand, .fa-compress {
            font-size: 16px;
        }
        .marker-travel-toggle{
            display: flex;
            flex-direction: column;
            align-items: start;
            justify-content: flex-start;
        }
        .distance-box {
            position: absolute;
            padding: 4px 8px;
            border-radius: 4px;
            color: white;
            font-size: 10px;
            font-weight: bold;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            white-space: nowrap;
            transform: translate(-50%, -50%);
            z-index: 100;
        }
        .addressVal{
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .marker-image-slider {
            position: relative;
            height: 180px;
            overflow: hidden;
        }

        .slider-container {
            display: flex;
            height: 100%;
            transition: transform 0.3s ease;
        }

        .slider-container .slide img{
            border-radius: 8px 8px 0 0;
            height: 100%;
        }
        .slide {
            flex-shrink: 0;
            height: 100%;
        }

        .slider-controls {
            position: absolute;
            bottom: 10px;
            left: 0;
            right: 0;
            display: flex;
            justify-content: center;
            gap: 6px;
            z-index: 10;
        }

        .slider-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: white;
            opacity: 0.5;
            cursor: pointer;
            transition: opacity 0.3s ease;
        }

        .slider-dot.active, .slider-dot.bg-opacity-100 {
            opacity: 1;
        }

        .marker-close {
            position: absolute;
            display: block;
            width: 28px;
            height: 29px;
            font-size: 14px;
            border-radius: 50%;
            background: white;
            border: 1px solid #e4e4e4;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            z-index: 9999;
            top: 10px;
            right: 10px;
        }
        .marker-close i{
            font-weight: 500;
        }

        .marker-close:hover {
            background: #f7f7f7;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
            transform: scale(1.05);
        }

        .marker-close:active {
            transform: scale(0.95);
        }

        .marker-close::before,
        .marker-close::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 16px;
            height: 16px;
            transform: translate(-50%, -50%);
            background: transparent;
        }


        .near-this-area.wrapper {
            position: relative;
        }

        .places-container.text-container {
            max-height: 90px;
            overflow: hidden;
            position: relative;
            transition: max-height 0.5s ease;
        }

        .places-container.text-container.show {
            min-height: 200px;
            overflow-y: auto;
        }

        .gradient::before {
            content: '';
            height: 80px;
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            background: linear-gradient(to bottom, rgba(255,255,255,0) 0%, rgba(255,255,255,1) 70%);
            transition: height 0.25s ease;
        }

        .places-container.text-container.show.gradient::before {
            height: 0;
        }

        .show-btn {
            position: absolute;
            left: 50%;
            bottom: -18px;
            transform: translateX(-50%);
            border-radius: 50%;
            border: none;
            width: 40px;
            height: 40px;
            color: #fff !important;
            font-size: 20px;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0,0,0,0.4);
            transition: background-color 0.25s ease, transform 0.3s ease;
        }

        .show-btn:hover {
            background-color: var( --primary-color);
            color: #fff;
        }

        .show-btn.rotate {
            transform: translateX(-50%) rotate(180deg);
        }
        .view-map-icon{
            padding: 0 !important;
            border: 1px solid var(--border-1) !important;
        }
        .arrorControls{
            padding: 12px !important;
            transform: translateY(-50%);
            transition: 0.5s;
            opacity: 0;
            visibility: hidden;
        }
        .combined-marker:hover .arrorControls{
            opacity: 1;
            visibility: visible;
        }
        .arrorControls .slider-arrow{
            width: 24px !important;
            height: 24px !important;
            border-radius: 50% !important;
            background: var(--white-color) !important;
            color: var(--text-color-1) !important;
            font-size: 12px !important;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .marker-image-container{
            width: 100% !important;
        }
        .gm-style-iw, .gm-style-iw-tc{
            display: none !important;
        }
        .place-card{
            border: none !important;
            box-shadow: none !important;
        }

        .btn-3.view-map-icon .btn-wrapper {
            height: 16px;
        }
        .btn-3.view-map-icon .btn-wrapper .btn-single {
            height: 16px;
        }

        .overall-rating-title {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .overall-rating-title .service-review-title{
            font-size: 68px;
        }
        .overall-rating {
            height: 100%;
            width: 100%;
            text-align: center;
            display: flex;
            align-items: center;
            flex-direction: column;
            justify-content: center;
        }
        .count.active .count-container {
            top: 100px !important;
            width: 100%;
        }
    </style>
@endpush

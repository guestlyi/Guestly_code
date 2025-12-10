@push('style')
    <style>
        .sign-in{
            padding: 80px 0 80px;
            overflow: hidden;
        }
        .sign-in-container{
            padding: 0 60px;
        }
        .sign-in-form-container {
            margin-left: 80px;
        }
        .sign-in-image.text-end {
            margin-right: -60px;
            width: 100%;
        }
        .sign-in-image.text-end img{
            height: 100%;
        }
        .sign-in-title{
            margin-bottom: 15px;
        }
        .sign-in-title h3{
            font-size: 36px;
            font-weight: 500;
            line-height: 48px;
            margin-bottom: 7px;
        }
        .sign-in-form .form-group{
            margin-top: 15px;
        }
        .sign-in-form .password-1 .form-group{
            margin-top: 0;
        }
        .sign-in-form .form-group label {
            color: var(--black-color);
            font-size: 16px;
            font-weight: 400;
            line-height: 18px;
            margin-bottom: 7px;
        }
        .sign-in-form .form-control {
            width: 100%;
            padding: 11px 16px;
            border-radius: 6px;
            border: 1px solid var( --border-1);
            background: transparent;
        }
        .sign-in-form .form-control::placeholder{
            color: var(--text-color-1);
            font-size: 14px;
            font-weight: 400;
            line-height: 28px;
        }
        .password-box, .password-box-two{
            position: relative;
        }
        .password-box i, .password-box-two i{
            position: absolute;
            top: 13px;
            right: 16px;
            cursor: pointer;
        }
        .sign-in-image img{
            border-radius: 24px;
        }
        .rember {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 10px;
        }
        .rember .form-check {
            margin-bottom: 0;
        }
        .rember .form-check label{
            color: var(--text-color-1);
        }
        .rember .rember-password a{
            color: var(--text-color-1) !important;
        }
        .rember .rember-password a:hover{
            color: var(--primary-color) !important;
        }
        .sign-in-btn button {
            margin-top: 25px;
            width: 100%;
            padding: 6px 9px;
            text-transform: capitalize;
            border-radius: 8px;
            display: flex;
            justify-content: center;
            font-size: 16px;
            font-weight: 400;
        }

        .media-login{
            margin-top: 16px;
        }
        .media-login-border{
            position: relative;
            text-align: center;
        }
        .media-login-border h5 {
            position: relative;
            width: 26px;
            border-radius: 50%;
            font-size: 14px !important;
            font-weight: 400 !important;
            background: var(--white-color);
            margin: 0 auto;
            text-align: center;
            z-index: 1;
        }
        .media-login-border::after {
            content: '';
            width: 100%;
            height: 0.5px;
            background: #D6DFDF;
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
        }
        .media-login ul{
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            margin-top: 18px;
            gap: 15px;
        }
        .media-login ul li{
            flex-grow: 1;
        }
        .media-login ul li a{
            width: 95px !important;
            height: 50px !important;
            border-radius: 4px;
            border: 1px solid #D6DFDF;
            padding: 13px 16px;
            font-size: 15px;
            color: var(--black-color) !important;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            overflow: hidden;
        }
        .media-login ul li a img{
            width: 32px;
            height: 32px;
        }
        .signup-account{
            text-align: center;
            margin-top: 20px;
        }
        .signup-account p a{
            font-weight: 500;
            color: var(--primary-color) !important;
        }
        .signup-account p a:hover{
            color: var(--black-color) !important;
        }
        .signup-account p a i{
            transition: 0.3s;
        }
        .signup-account p a:hover i{
            margin-right: 5px;
        }

        /* MackBook */
        @media only screen and (max-width: 1440px){
            .sign-in-container {
                padding: 0;
            }
            .sign-in-image.text-end {
                margin-right: 0;
            }
        }

        /* Large screen  */
        @media only screen and (min-width: 1200px) and (max-width: 1319px) {
            .sign-in-title h3 {
                font-size: 38px;
                line-height: 48px;
            }
            .sign-in-image.text-end {
                margin-right: 0;
            }
        }

        /* Medium screen  */
        @media only screen and (min-width: 992px) and (max-width: 1199px) {
            .sign-in-title h3 {
                font-size: 32px;
                line-height: 42px;
            }
            .sign-in-container-navigation {
                margin-top: 50px;
            }
            .sign-in-image.text-end {
                margin-right: 0;
            }
            .sign-in-form-container{
                margin-left: 10px;
            }
        }

        /* Tablet Layout: 768px. */
        @media only screen and (min-width: 768px) and (max-width: 991px){
            .sign-in {
                padding: 80px 0 80px;
            }
            .sign-in-container {
                margin-right: 0;
                justify-content: center;
                text-align: center;
            }
            .sign-in-form .form-group {
                text-align: start;
            }
            .sign-in-form .form-group {
                margin-bottom: 0;
            }
            .sign-in-container-navigation {
                margin-top: 50px;
            }
            .sign-in-container-navigation ul {
                justify-content: center;
            }

            .sign-in-image.text-end {
                display: none;
            }

            .sign-in-form-container {
                margin-left: 0;
            }
        }
        @media only screen and (max-width: 991px){
            .sign-in-container-inner, .sign-in-form-container{
                display: flex;
                flex-direction: column;
                align-items: center;
            }
            .sign-in-title h3 {
                font-size: 36px !important;
                line-height: 50px !important;
            }
            .sign-in-form-container .sign-in-form{
                width: 100%;
            }
        }

        /* Mobile Layout: 320px. */
        @media only screen and (max-width: 767px){
            .sign-in {
                padding: 80px 0 80px;
            }
            .sign-in-container {
                margin-right: 0;
            }
            .sign-in-container-navigation {
                margin-top: 50px;
            }
            .form-group {
                margin-bottom: 0;
            }

            .sign-in-image.text-end {
                display: none;
            }
            .sign-in-form-container {
                margin-left: 0;
            }
        }
        @media only screen and (max-width: 425px){
            .media-login ul li a {
                width: 77px !important;
            }
            .sign-in-container {
                padding: 29px 10px 40px !important;
            }
            .sign-in-container-inner, .sign-in-form-container {
                display: flex;
                flex-direction: column;
                 align-items: start;
            }
            .sign-in-title h3 {
                font-size: 32px !important;
            }
        }

        .input-group.input-group-merge {
            justify-content: space-between;
            border: 1px solid var(--border-1);
            border-radius: 8px;
            overflow: hidden;
        }
        .input-group.input-group-merge img{
            height:80%;
        }
        /* From Uiverse.io by 00Kubi */
        .radio-inputs {
            position: relative;
            display: flex;
            flex-wrap: wrap;
            border-radius: 1rem;
            background: linear-gradient(145deg, #e6e6e6, #ffffff);
            box-sizing: border-box;
            box-shadow:
                5px 5px 15px rgba(0, 0, 0, 0.15),
                -5px -5px 15px rgba(255, 255, 255, 0.8);
            padding: 0.5rem;
            width: 300px;
            font-size: 14px;
            gap: 0.5rem;
        }

        .radio-inputs .radio {
            flex: 1 1 auto;
            text-align: center;
            position: relative;
        }

        .radio-inputs .radio input {
            display: none;
        }

        .radio-inputs .radio .name {
            display: flex;
            cursor: pointer;
            align-items: center;
            justify-content: center;
            border-radius: 0.7rem;
            border: none;
            padding: 0.7rem 0;
            color: #2d3748;
            font-weight: 500;
            font-family: inherit;
            background: linear-gradient(145deg, #ffffff, #e6e6e6);
            box-shadow:
                3px 3px 6px rgba(0, 0, 0, 0.1),
                -3px -3px 6px rgba(255, 255, 255, 0.7);
            transition: all 0.2s ease;
            overflow: hidden;
        }

        .radio-inputs .radio input:checked + .name {
            background: linear-gradient(145deg, #e51111, #2a1313);
            color: #ffffff !important;
            font-weight: 600;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
            box-shadow:
                inset 2px 2px 5px rgba(0, 0, 0, 0.2),
                inset -2px -2px 5px rgba(255, 255, 255, 0.1),
                3px 3px 8px rgba(242, 63, 63, 0.4);
        }


        /* Hover effect */
        .radio-inputs .radio:hover .name {
            background: linear-gradient(145deg, #f0f0f0, #ffffff);
            transform: translateY(-1px);
            box-shadow:
                4px 4px 8px rgba(0, 0, 0, 0.1),
                -4px -4px 8px rgba(255, 255, 255, 0.8);
        }

        .radio-inputs .radio:hover input:checked + .name {
            transform: translateY(1px);
        }

        /* Animation */
        .radio-inputs .radio input:checked + .name {
            animation: select 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Particles */
        .radio-inputs .radio .name::before,
        .radio-inputs .radio .name::after {
            content: "";
            position: absolute;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            opacity: 0;
            pointer-events: none;
        }

        .radio-inputs .radio input:checked + .name::before,
        .radio-inputs .radio input:checked + .name::after {
            animation: particles 0.8s ease-out forwards;
        }

        .radio-inputs .radio .name::before {
            background: #60a5fa;
            box-shadow: 0 0 6px #60a5fa;
            top: -10px;
            left: 50%;
            transform: translateX(-50%);
        }

        .radio-inputs .radio .name::after {
            background: #93c5fd;
            box-shadow: 0 0 8px #93c5fd;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
        }

        /* Sparkles */
        .radio-inputs .radio .name::after {
            content: "";
            position: absolute;
            inset: 0;
            z-index: -1;
            background: radial-gradient(
                circle at var(--x, 50%) var(--y, 50%),
                rgba(59, 130, 246, 0.3) 0%,
                transparent 50%
            );
            opacity: 0;
            transition: opacity 0.3s;
        }

        .radio-inputs .radio input:checked + .name::after {
            opacity: 1;
            animation: sparkle-bg 1s ease-out forwards;
        }

        /* Multiple particles */
        .radio-inputs .radio input:checked + .name {
            overflow: visible;
        }

        .radio-inputs .radio input:checked + .name::before {
            box-shadow:
                0 0 6px #024CA7,
                10px -10px 0 #024CA7,
                -10px -10px 0 #024CA7;
            animation: multi-particles-top 0.8s ease-out forwards;
        }

        .radio-inputs .radio input:checked + .name::after {
            box-shadow:
                0 0 8px #024CA7,
                10px 10px 0 #024CA7,
                -10px 10px 0 #024CA7;
            animation: multi-particles-bottom 0.8s ease-out forwards;
        }

        @keyframes select {
            0% {
                transform: scale(0.95) translateY(2px);
            }
            50% {
                transform: scale(1.05) translateY(-1px);
            }
            100% {
                transform: scale(1) translateY(2px);
            }
        }

        @keyframes multi-particles-top {
            0% {
                opacity: 1;
                transform: translateX(-50%) translateY(0) scale(1);
            }
            40% {
                opacity: 0.8;
            }
            100% {
                opacity: 0;
                transform: translateX(-50%) translateY(-20px) scale(0);
                box-shadow:
                    0 0 6px transparent,
                    20px -20px 0 transparent,
                    -20px -20px 0 transparent;
            }
        }

        @keyframes multi-particles-bottom {
            0% {
                opacity: 1;
                transform: translateX(-50%) translateY(0) scale(1);
            }
            40% {
                opacity: 0.8;
            }
            100% {
                opacity: 0;
                transform: translateX(-50%) translateY(20px) scale(0);
                box-shadow:
                    0 0 8px transparent,
                    20px 20px 0 transparent,
                    -20px 20px 0 transparent;
            }
        }

        @keyframes sparkle-bg {
            0% {
                opacity: 0;
                transform: scale(0.2);
            }
            50% {
                opacity: 1;
            }
            100% {
                opacity: 0;
                transform: scale(2);
            }
        }

        /* Ripple effect */
        .radio-inputs .radio .name::before {
            content: "";
            position: absolute;
            inset: 0;
            border-radius: inherit;
            background: radial-gradient(
                circle at var(--x, 50%) var(--y, 50%),
                rgba(255, 255, 255, 0.5) 0%,
                transparent 50%
            );
            opacity: 0;
            transition: opacity 0.3s;
        }

        .radio-inputs .radio input:checked + .name::before {
            animation: ripple 0.8s ease-out;
        }
        .radio-inputs .radio input:checked + .name::after {
            display: none;
        }


        @keyframes ripple {
            0% {
                opacity: 1;
                transform: scale(0.2);
            }
            50% {
                opacity: 0.5;
            }
            100% {
                opacity: 0;
                transform: scale(2.5);
            }
        }

        /* Glowing border */
        .radio-inputs .radio input:checked + .name {
            position: relative;
        }

        .radio-inputs .radio input:checked + .name::after {
            content: "";
            position: absolute;
            inset: -2px;
            border-radius: inherit;
            background: linear-gradient(
                45deg,
                rgba(59, 130, 246, 0.5),
                rgba(37, 99, 235, 0.5)
            );

            -webkit-mask-composite: xor;
            mask-composite: exclude;
            animation: border-glow 1.5s ease-in-out infinite alternate;
        }

        @keyframes border-glow {
            0% {
                opacity: 0.5;
            }
            100% {
                opacity: 1;
            }
        }

        .pac-logo:after{
            background-image: none!important;
            height: 0 !important;
        }

        .pac-container{
            padding: 10px;
            box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.15),
            -5px -5px 15px rgba(255, 255, 255, 0.8);
        }

        .pac-item{
            padding: 5px 10px;
            border-top: 0 !important;
            border-bottom: 1px solid #e6e6e6 !important;
        }

        .pac-item:nth-last-child{
            border-bottom: 0 !important;
        }
        .rember .form-check{
            display: flex;
            flex-direction: row;
            gap: 10px;
        }
        .captcha-box .captcha-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 3px;
        }
        .captcha-box .captcha-inner img{
            border: 1px solid var(--border-1);
            padding: 1px;
            border-radius: 8px
        }
        .captcha-box .captcha-inner a{
            padding: 13px;
            border: 1px solid var(--border-1);
            border-radius: 7px;
        }
        .sign-in-form .form-group {
            margin-top: 15px;
            display: flex;
            flex-direction: column;
        }
    </style>
@endpush

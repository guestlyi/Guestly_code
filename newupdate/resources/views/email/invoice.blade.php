<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ basicControl()->site_title }} - Receipt</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="{{ $booking->logo }}" type="image/x-icon">
    <link rel="icon" href="{{ $booking->favicon }}" type="image/x-icon">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap');

        * {
            margin: 0px;
            padding: 0px;
            border: none;
            outline: none;
            font-size: 100%;
            line-height: inherit;
        }

        .page-wrapper {
            position: relative;
            width: 100%;
            min-width: 320px;
            z-index: 9;
            margin: 0px auto;
            overflow: hidden;
        }

        body {
            -webkit-font-smoothing: antialiased;
            font-family: "Inter", sans-serif;
        }
        ::selection {
            background: #10100F;
            color: var(--white-color);
        }

        a{
            display: inline-block;
            color: #10100F ;
            text-decoration: none;
        }
        a,
        a:active,
        a:focus {
            text-decoration: none;
            outline: none;
            -webkit-transition: 0.5s;
            -o-transition: 0.5s;
            transition: 0.5s;
        }
        a:hover {
            outline: none;
            color: #F23F3F;
        }
        span{
            display: inline-block;
        }

        h1, h2, h3, h4, h5, h6{
            color: #10100F;
            font-family: "Inter", sans-serif;
        }

        h1, .h1 {
            font-size: 56px;
            font-weight: 700;
            line-height: 67px;
        }

        h2, .h2 {
            font-size: 48px;
            font-weight: 700;
            line-height:56px;
        }

        h3, .h3 {
            font-size: 36px;
            font-weight: 600;
            line-height: 56px;
        }

        h4, .h4 {
            font-size: 30px ;
            font-weight: 600 ;
            line-height: 32px ;
        }

        h5, .h5 {
            font-size: 22px ;
            font-weight: 500 ;
            line-height: 32px ;
        }

        h6, .h6 {
            font-size: 16px;
            font-weight: 500;
            line-height: 24px;
        }

        p {
            font-size: 16px;
            line-height: 24px;
            font-weight: 400;
            margin-bottom: 0;
            font-family: "Inter", sans-serif;
            color: #484848;
        }
        ul, li {
            list-style: none;
        }

        .container {
            position: static;
            max-width: 1350px;
            margin: 0 auto;
            padding: 0 15px;
        }
        /* global end */

        .receipt-container{
            padding: 120px 0;
        }
        .receipt-top-info{
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 20px;
        }
        .customer-info-content li{
            margin-bottom: 15px;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
        }
        .customer-info-content li h6{
            width: 30%;
        }
        .customer-info-content li p{
            width: 30%;
        }
        .item-list li{
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            flex-wrap: wrap;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .item-list li h6, .item-list li p{
            text-align: center;
            white-space: nowrap;
        }

        table {
            font-family: "Inter", sans-serif;
            border-collapse: collapse;
            width: 100%;
            overflow-x: auto;
        }
        td, th {
            border-bottom: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        tr:nth-child(even) {
            background-color: #F6F6F6;
        }

        .total-amount{
            justify-content: flex-end !important;
            border-bottom: none !important;
        }
        .total-amount h6, .total-amount p{
            text-align: end !important;
        }
        .contact ul{
            display: flex;
            align-items: center;
            gap: 20px;
            justify-content: space-between;
            flex-wrap: wrap;
        }
        .contact ul li a{
            display: flex;
            align-items: center;
            gap: 7px;
        }
        .contact ul li a .icon{
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #F23F3F;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
        }
        .contact {
            max-width: 950px;
            width: 100%;
            margin: 0 auto;
        }


        /* Mobile Layout: 320px. */
        @media only screen and (max-width: 767px){
            .customer-info-content li p {
                width: auto;
            }
            tr {
                display: flex;
                flex-direction: column;
            }
            tr th:nth-child(1), tr th:nth-child(3){
                display: none;
            }
            table {
                overflow-x: unset;
            }
        }

        /* Tablet Layout: 768px. */
        @media only screen and (min-width: 768px) and (max-width: 991px){

        }
    </style>

</head>

<body>

<div class="page-wrapper" style="overflow: hidden;">

    <div class="container">
        <div class="receipt-container">

            <div class="receipt-top-info">
                <div class="receipt-top-left">
                    <div class="logo">
                        <a href="{{ url('/') }}">
                            <img src="{{ $booking->logo }}" alt="Logo" style="height: 60px;">
                        </a>
                    </div>
                    <div class="receipt-top-content">
                        <a href="{{ route('page','/') }}">{{ basicControl()->site_title }}</a>
                        <div style="margin: 20px 0 30px;">
                            <p>{{ basicControl()->address }}</p>
                        </div>
                        <h6>Receipt:</h6>
                    </div>
                </div>
                <div class="receipt-top-right">
                    <div class="receipt-top-content" style="height: 100%; display: flex; flex-direction: column; justify-content: space-between;">
                        <div style="margin-bottom: 50px;">
                            <p><strong>Booking ID:</strong> {{ $booking->uid }}</p>
                            <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($booking->created_at)->format('D, d M, Y') }}</p>
                        </div>
                        <h6 style="text-transform: uppercase;">{{ strtoupper($booking->uid) }}</h6>
                    </div>
                </div>
            </div>

            <div class="customer-info" style="padding: 20px 0 30px; border-bottom: 1px solid #ddd;">
                <div class="customer-info-title" style="background: #10100F; text-align: center; padding: 10px 0; margin-bottom: 30px;">
                    <h5 style="color: #ffffff !important;">Customer Name & Address</h5>
                </div>
                <ul class="customer-info-content" style="max-width: 950px; margin: 0 auto;">
                    <li><h6>Name</h6><p>{{ $booking->guest->firstname }} {{ $booking->guest->lastname }}</p></li>
                    <li><h6>Email Address</h6><p>{{ $booking->guest->email }}</p></li>
                    <li><h6>Payment mode</h6><p>Online</p></li>
                </ul>
            </div>

            <div class="items" style="padding: 50px 0;">
                @php
                    $info = is_array($booking->information)
                        ? $booking->information
                        : json_decode($booking->information, true);
                @endphp

                <table>
                    <tr>
                        <th>Item</th>
                        <th>Item Details</th>
                        <th style="text-align: end;">Total Amount</th>
                    </tr>
                    <tr>
                        <td>
                            <p>Hotel Booking at {{ $booking->property->title }}</p>
                        </td>
                        <td>
                            <p>Period: {{ \Carbon\Carbon::parse($booking->check_in_date)->format('D, d M, Y') }} - {{ \Carbon\Carbon::parse($booking->check_out_date)->format('D, d M, Y') }}</p>
                            <p>Rooms: {{ $info['room_type'] ?? 'N/A' }}</p>
                            <p>PAX: {{ $info['adults'] ?? 0 }} Adult, {{ $info['children'] ?? 0 }} Child</p>
                            <p>Number of Rooms: {{ $info['number_of_rooms'] ?? 1 }}</p>
                        </td>
                        <td style="text-align: end;">
                            <p>{{ $booking->base_currency }} {{ number_format($booking->amount_without_discount, 2) }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="border: none;"></td>
                        <td style="border: none;"></td>
                        <td class="total-amount">
                            <div style="text-align: end !important;">
                                <p>Sub Total: <strong>{{ $booking->base_currency }} {{ number_format($booking->amount_without_discount, 2) }}</strong></p>
                                <p>Discount: <strong>{{ $booking->base_currency }} {{ number_format($booking->discount_amount, 2) }}</strong></p>
                                <p>Site Charge: <strong>{{ $booking->base_currency }} {{ number_format($booking->site_charge, 2) }} </strong></p>
                            </div>
                            <div style="border-top: 1px solid #10100F; margin-top: 20px; padding-top: 20px;">
                                <p>Total Payment:</p>
                                <h6>(Refundable) {{ $booking->base_currency }} {{ number_format($booking->total_amount, 2) }}</h6>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

            <footer>
                <div class="contact">
                    <h5 style="margin-bottom: 30px; text-align: center;">Need Help?</h5>
                    <ul>
                        <li>
                            <a href="tel:{{ basicControl()->contact_number }}">
                                <div class="icon">
                                    <i class="fa-solid fa-phone"></i>
                                </div>
                                <span>{{ basicControl()->contact_number }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="mailto:{{ basicControl()->sender_email }}">
                                <div class="icon">
                                    <i class="fa-solid fa-envelope"></i>
                                </div>
                                <span>{{ basicControl()->sender_email }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/') }}">
                                <div class="icon">
                                    <i class="fa-solid fa-message"></i>
                                </div>
                                <span>{{ basicControl()->site_title }}</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </footer>

        </div>
    </div>

</div>

</body>
</html>

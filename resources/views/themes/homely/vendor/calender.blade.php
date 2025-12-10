@extends(template().'layouts.user')
@section('title',trans('Calender'))
@section('content')
    <section class="calender-page">
        <div class="container-fluid">
            <div class="row justify-content-center align-items-center">
                <div class="col-lg-10">
                    <div class="calender-container">
                        <div class="evo-calender">
                            <div id="evoCalendar"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('style')
    <link rel="stylesheet" href="{{ asset(template(true) . "css/evo-calendar.min.css") }}"/>
    <style>
        .booking-item {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            background-color: #f8f9fa;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .booking-item:hover {
            background-color: #e9ecef;
        }

        .active-booking {
            border-left: 4px solid #3498db;
        }

        .calender-sidebar-list ul {
            list-style: none;
            padding: 0;
        }

        .calender-sidebar-list li {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }

        .calender-sidebar-list li a {
            display: flex;
            justify-content: space-between;
            color: #495057;
            text-decoration: none;
        }
        #evoCalendar .event-list{
            margin-top: 10px;
        }
        #evoCalendar .event-list>.event-empty {
            padding: 15px 10px;
            background-color: rgba(135,115,193,.15);
            border: 1px solid var(--border-1);
            border-radius: 6px;
        }
        #evoCalendar .calendar-header {
            background-color: var(--primary-color) !important;
            color: #fff !important;
        }

        #evoCalendar .calendar-header .month-btn {
            background-color:var(--primary-color) !important;
            color: #fff !important;
        }
        #evoCalendar .calendar-header .calendar-header-day{
            color: #fff !important;
        }
        #evoCalendar .event-icon .event-bullet-event{
            background-color: var(--primary-color) !important;
        }
        .calendar-sidebar{
            background-color: var(--primary-color) !important;
            color: #fff !important;
        }
        .calendar-sidebar>span#sidebarToggler {
            background-color: var(--primary-color) !important;
            color: #fff !important;
        }
        .calendar-sidebar>.calendar-year>p {
            color: #fff !important;
        }
        #eventListToggler {
            background-color: var(--primary-color) !important;
            color: #fff !important;
        }
        .type-event{
            background-color: var(--primary-color) !important;
            color: #fff !important;
        }
        th[colspan="7"] {
            position: relative;
            text-align: center;
            text-transform: uppercase;
            font-weight: 600;
            font-size: 30px;
            color: var(--primary-color);
        }
        .calendar-sidebar>.month-list>.calendar-months>li.active-month {
            background-color: #221313;
        }
        .calendar-sidebar>.month-list>.calendar-months>li:hover {
            background-color: #9d6f6f;
        }
    </style>
@endpush
@push('script')
    <script src="{{ asset(template(true) . 'js/evo-calendar.min.js') }}"></script>
    <script>
        const currencySymbol = '{{ basicControl()->currency_symbol }}';

        document.addEventListener('DOMContentLoaded', function () {
            const calendarData = @json($events);

            if ($('#evoCalendar').length) {
                $('#evoCalendar').evoCalendar({
                    theme: 'Custom Theme',
                    calendarEvents: calendarData,
                });
            }
        });
    </script>
@endpush


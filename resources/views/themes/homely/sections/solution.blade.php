@if(isset($solution))
    <section class="solution">
        <div class="container">
            <div class="common-title">
                <h3>{{ $solution['single']['title'] ?? '' }}</h3>
            </div>
            <div class="solution-table">
                <div class="solution-table-inner">
                    <div class="solution-header">
                        <div class="solution-header-title"><h6>{{ $solution['single']['our_name'] ?? '' }}</h6></div>
                        <div class="solution-header-title"><h6>{{ $solution['single']['other_name'] ?? '' }}</h6></div>
                    </div>
                    <div class="solution-body">
                        <ul>
                            @foreach($solution['multiple'] ?? [] as $item)
                                <li>
                                    <div class="solution-content">
                                        <h6>{{ $item['title'] ?? '' }}</h6>
                                        <p>{{ $item['description'] ?? '' }}</p>
                                    </div>
                                    <div class="compair-mark">
                                        <div class="solution-mark"><i class="fa-regular fa-check"></i></div>
                                        <div class="solution-mark"><i class="fa-light fa-xmark"></i></div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif


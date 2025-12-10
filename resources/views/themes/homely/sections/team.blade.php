@if(isset($team))
    <section class="team">
        <div class="container">
            <div class="common-title">
                <h3>{{ $team['single']['heading'] ?? '' }}</h3>
            </div>
            <div class="row">
                @foreach($team['multiple'] as $member)
                    <div class="col-lg-3 col-md-6">
                        <div class="team-single">
                            <div class="team-single-image">
                                <img src="{{ getFile($member['media']->image->driver, $member['media']->image->path) }}" alt="{{ $member['name'] ?? '' }}">
                            </div>
                            <div class="team-single-content">
                                <h5>{{ $member['name'] ?? '' }}</h5>
                                <p>{{ $member['designation'] ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif


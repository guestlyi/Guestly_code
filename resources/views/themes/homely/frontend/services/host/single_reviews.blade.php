@foreach($reviews as $hrv)
    <div class="col-lg-6">
        <div class="user-reviews-content border p-3 rounded">
            <div class="d-flex align-items-center gap-3 mb-2">
                <img src="{{ getFile($hrv->guest->image_driver, $hrv->guest->image) }}" class="rounded-circle" style="width: 50px; height: 50px;" alt="{{ $hrv->guest?->firstname }}">
                <div>
                    <h6 class="mb-0">{{ $hrv->guest?->firstname . ' '. $hrv->guest?->lastname }}</h6>
                    <small class="text-muted">{{ \Carbon\Carbon::parse($hrv->created_at)->diffForHumans() }}</small>
                </div>
            </div>

            @php
                $rating = round($hrv->avg_rating, 1);
                $fullStars = floor($rating);
                $halfStar = ($rating - $fullStars) >= 0.5 ? 1 : 0;
                $emptyStars = 5 - $fullStars - $halfStar;
            @endphp

            <div class="user-rating mb-2">
                <ul class="d-flex list-unstyled mb-0">
                    @for ($i = 0; $i < $fullStars; $i++)
                        <li><i class="fa-solid fa-star text-warning"></i></li>
                    @endfor
                    @if ($halfStar)
                        <li><i class="fa-solid fa-star-half-stroke text-warning"></i></li>
                    @endif
                    @for ($i = 0; $i < $emptyStars; $i++)
                        <li><i class="fa-regular fa-star text-warning"></i></li>
                    @endfor
                </ul>
            </div>

            <p>{{ $hrv->comment ?? 'Not Available Comment' }}</p>
        </div>
    </div>
@endforeach

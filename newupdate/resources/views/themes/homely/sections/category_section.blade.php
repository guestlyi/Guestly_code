@if(isset($category_section))
    <section class="category-section">
        <div class="container">
            <div class="section-header">
                <div class="left-side">
                    <h2 class="section-title split-text">{{ $category_section['single']['title'] ?? '' }}</h2>
                </div>
                <div class="right-side split-text">
                    {{ $category_section['single']['description'] ?? '' }}
                </div>
            </div>
            <div class="row g-4">
                @foreach($category_section['multiple'] as $item)
                    <div class="col-lg-3 col-sm-6">
                        <div class="category-box">
                            <div class="img-box">
                                <img src="{{ getFile($item->driver, $item->image) }}" alt="{{ $item->name }}"/>
                            </div>
                            <div class="text-box">
                                <div class="title">{{ $item->name }}</div>
                                <a href="{{ route('services', ['style' => slug($item->name)]) }}" class="btn-4">{{ $category_section['single']['button_name'] ?? 'More' }} <i class="fa-regular fa-arrow-up-right"></i></a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif

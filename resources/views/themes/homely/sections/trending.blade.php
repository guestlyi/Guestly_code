@if(isset($trending))
    <section class="trending">
        <div class="container">
            <div class="common-title">
                <h3>{{ $trending['single']['heading'] ?? '' }}</h3>
            </div>
            <div class="trending-container">
                <div class="trending-btn-box">
                    <nav>
                        <div class="nav nav-tabs trending-tabs" role="tablist">
                            @foreach($trending['categories'] ?? [] as $item)
                                <button class="nav-link trending-tab-btn {{ $loop->first ? 'active' : '' }}"
                                        id="trending-tab-{{ $item->id }}"
                                        data-category-id="{{ $item->id }}"
                                        type="button">
                                    {{ $item->name }}
                                </button>
                            @endforeach
                        </div>
                    </nav>
                </div>
                <div class="trending-tab-content">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="trending-content-one" role="tabpanel" aria-labelledby="trending-tab-one">
                            <div class="row showSearchProperty"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif


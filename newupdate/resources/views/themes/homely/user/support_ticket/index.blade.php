@extends(template().'layouts.user')
@section('title',trans('Support Ticket'))
@section('content')
    <section class="listing">
        <div class="container">
            <div class="personal-info-title listing-top">
                <div class="text-area">
                    <ul>
                        <li><a href="{{ route('user.profile') }}">@lang('Account')</a></li>
                        <li><i class="fa-light fa-chevron-right"></i></li>
                        <li>@lang('Support Ticket')</li>
                    </ul>
                    <h4>@lang('Support Ticket')</h4>
                </div>
                <a href="#" class="listing-plus-btn" data-bs-toggle="modal" data-bs-target="#createTicket"><i class="fa-light fa-plus"></i></a>
            </div>

            <div class="listing-container">
                <div class="shop-view-content">
                    <div class="tab-content" id="nav-tabContent">
                        <div class="list-view-wrapper">
                            <div class="table-responsive">
                                <table class="table table-striped align-middle">
                                    <thead>
                                    <tr>
                                        <th scope="col">@lang('Subject')</th>
                                        <th scope="col">@lang('Status')</th>
                                        <th scope="col">@lang('Last Reply')</th>
                                        <th scope="col">@lang('Action')</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($tickets as $item)
                                        <tr>
                                            <td data-label="subject">
                                                <div class="listing-image-container">
                                                    <h6>[{{ trans('Ticket#').$item->ticket }}] {{ $item->subject }}</h6>
                                                </div>
                                            </td>
                                            <td data-label="Status">
                                                @if($item->status == 0)
                                                    <span class="badge bg-primary-subtle text-primary">@lang('Open')</span>
                                                @elseif($item->status == 1)
                                                    <span class="badge bg-success-subtle text-success">@lang('Answered')</span>
                                                @elseif($item->status == 2)
                                                    <span class="badge bg-warning-subtle text-warning">@lang('Replied')</span>
                                                @elseif($item->status == 3)
                                                    <span class="badge bg-danger-subtle text-danger">@lang('Closed')</span>
                                                @endif
                                            </td>
                                            <td data-label="Last Reply">{{diffForHumans($item->last_reply) }}</td>

                                            <td data-label="Edit" class="text-center">
                                                <a class="btn-3 other_btn2" href="{{ route('user.ticket.view', $item->ticket) }}">
                                                    <div class="btn-wrapper">
                                                        <div class="main-text btn-single">
                                                            <i class="far fa-eye"></i>
                                                        </div>
                                                        <div class="hover-text btn-single">
                                                            <i class="far fa-eye"></i>
                                                        </div>
                                                    </div>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        @include('empty')
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                            {{ $tickets->appends(request()->query())->links(template().'partials.pagination') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="createTicket" tabindex="-1" aria-labelledby="createTicketLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createTicketLabel">@lang('Create Ticket')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form-row" action="{{route('user.ticket.store')}}" method="post"
                          enctype="multipart/form-data">
                        @csrf

                        <div class="col-md-12">
                            <div class="form-group mb-2">
                                <label>@lang('Subject')</label>
                                <input class="form-control" type="text" name="subject"
                                       value="{{old('subject')}}" placeholder="@lang('Enter Subject')">
                                @error('subject')
                                <div class="error text-danger">@lang($message) </div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group mb-2">
                                <label>@lang('Message')</label>
                                <textarea class="form-control ticket-box" name="message" rows="5"
                                          id="textarea1"
                                          placeholder="@lang('Enter Message')">{{old('message')}}</textarea>
                                @error('message')
                                <div class="error text-danger">@lang($message) </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12" id="form-group-container">
                            <div class="form-group mb-2">
                                <div id="image-preview"></div>
                                <label for="file-input" id="file-label" class="form-control ticketText d-none">@lang('Choose Files')</label>
                                <input type="file" name="attachments[]"
                                       class="form-control ticketText"
                                       id="file-input"
                                       multiple
                                >
                                @error('attachments')
                                <span class="text-danger">{{trans($message)}}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group d-flex justify-content-end align-items-center gap-2">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('Close')</button>
                                <button type="submit" class="btn btn-primary"><span>@lang('Submit')</span></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('style')
    <style>
        .listing-top{
            padding: 100px 0 0 !important;
        }
    </style>
@endpush
@push('script')
    <script>
        document.getElementById('file-input').addEventListener('change', function (event) {
            const preview = document.getElementById('image-preview');
            const fileInput = this;
            const fileLabel = document.getElementById('file-label');
            const formGroupContainer = document.getElementById('form-group-container');

            const allFiles = Array.from(event.target.files);
            updatePreview(allFiles);

            function updatePreview(files) {
                preview.innerHTML = '';
                fileLabel.style.display = files.length ? 'block' : 'none';
                formGroupContainer.style.display = files.length ? 'block' : 'none';
                fileLabel.textContent = `${files.length} file(s) selected`;

                const dataTransfer = new DataTransfer();

                files.forEach((file) => {
                    const container = document.createElement('div');
                    container.className = 'preview-container';
                    container.style.cssText = 'position:relative;display:inline-block;margin:10px;';
                    container.dataset.filename = file.name + file.size;

                    const closeIcon = document.createElement('span');
                    closeIcon.innerHTML = '&times;';
                    closeIcon.className = 'close-icon';
                    closeIcon.style.cssText = 'position:absolute;top:5px;right:5px;cursor:pointer;background:rgba(255,255,255,0.8);border-radius:50%;padding:2px 5px;z-index:1;font-size:15px;';
                    closeIcon.onclick = function () {
                        const remainingFiles = files.filter(f => f.name + f.size !== file.name + file.size);
                        updatePreview(remainingFiles);
                    };

                    if (file.type.startsWith('image/')) {
                        const img = document.createElement('img');
                        img.src = URL.createObjectURL(file);
                        img.style.cssText = 'height:100px;width:100px;border-radius:15px;';
                        container.appendChild(img);
                    } else {
                        const div = document.createElement('div');
                        div.textContent = file.name;
                        div.style.cssText = 'padding:20px;border:1px solid #ccc;border-radius:15px;width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;';
                        container.appendChild(div);
                    }

                    container.appendChild(closeIcon);
                    preview.appendChild(container);

                    dataTransfer.items.add(file);
                });

                fileInput.files = dataTransfer.files;
            }
        });
    </script>
@endpush

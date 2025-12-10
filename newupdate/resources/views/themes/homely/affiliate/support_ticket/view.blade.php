@extends(template().'layouts.affiliate')
@section('title',trans('View Ticket'))
@section('content')
    <div class="container">
        <div class="personal-info-title listing-top">
            <div class="text-area">
                <ul>
                    <li><a href="{{ route('affiliate.dashboard') }}">@lang('Dashboard')</a></li>
                    <li><i class="fa-light fa-chevron-right"></i></li>
                    <li>@lang('Support Ticket View')</li>
                </ul>
                <h4>@lang('Support Ticket View')</h4>
            </div>
        </div>
    </div>

    <section class="message-page supportTicketView">
        <div class="container">
            <div class="message-page-container">
                <div class="row justify-content-center align-items-center">
                    <div class="col-lg-10n ">
                        <div class="message-container p-0">
                            <div class="message-wrapper">
                                <div class="message-wrapper-header d-flex justify-content-between align-items-center">
                                    <div class="status">
                                        <div>
                                            @if($ticket->status == 0)
                                                <span class="badge bg-soft-warning text-warning">
                                                    <span class="legend-indicator bg-warning"></span>@lang("Open")
                                                </span>
                                            @elseif($ticket->status == 1)
                                                <span class="badge bg-success-subtle text-success">
                                                     <span class="legend-indicator bg-success"></span>@lang("Answered")
                                                </span>
                                            @elseif($ticket->status == 2)
                                                <span class="badge bg-info-subtle text-info">
                                                    <span class="legend-indicator bg-info"></span>@lang("Customer Reply")
                                                </span>
                                            @elseif($ticket->status == 3)
                                                <span class="badge bg-soft-danger text-danger">
                                                    <span class="legend-indicator bg-danger"></span>@lang("Closed")
                                                </span>
                                            @endif
                                            <span>[{{trans('Ticket#'). __($ticket->ticket) }}] {{ __($ticket->subject) }}</span>
                                        </div>
                                    </div>
                                    @if($ticket->status != 3)
                                        <div class="message-wrapper-header-btn">
                                            <button class="btn-3 other_btn set" type="button"
                                                    data-route="{{ route('affiliate.ticket.closed', $ticket->id) }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#CloseTicketModal">
                                                <div class="btn-wrapper">
                                                    <div class="main-text btn-single">
                                                        <i class="fa-light fa-circle-xmark"></i> @lang("Close")
                                                    </div>
                                                    <div class="hover-text btn-single">
                                                        <i class="fa-light fa-circle-xmark"></i> @lang("Close")
                                                    </div>
                                                </div>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                                <div class="message-wrapper-inner">
                                    @if(count($ticket->messages) > 0)
                                        @foreach($ticket->messages as $item)
                                            @if(!isset($item->admin_id))
                                                <div class="sender text-end">
                                                    <div class="sender-inner">
                                                        <div class="sender-image">
                                                            <img src="{{ getFile($ticket->affiliate->image_driver, $ticket->affiliate->image) }}" alt="{{ $ticket->affiliate->firstname.' '.$ticket->affiliate->lastname }}">
                                                        </div>
                                                        <div class="message-details">
                                                            <p class="{{ $item->message ? '' : 'd-none' }}">{{ $item->message }}</p>
                                                            @if(count($item->attachments) > 0)
                                                                <div class="text-info d-flex time">
                                                                    @forelse($item->attachments as $k => $file)
                                                                        <a href="{{ route('affiliate.ticket.download',encrypt($file->id)) }}"
                                                                           class="file" type="button">
                                                                            <i class="fal fa-file"></i>
                                                                            @lang('File(s)') {{ ++$k}}
                                                                        </a>
                                                                    @empty
                                                                    @endforelse
                                                                </div>
                                                            @endif
                                                            <span class="time">{{ __($item->created_at->format('d M, Y h:i A')) }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="sender">
                                                    <div class="sender-inner">
                                                        <div class="sender-image">
                                                            <img src="{{ getFile($item->admin->image_driver, $item->admin->image) }}" alt="photo">
                                                        </div>
                                                        <div class="message-details">
                                                            <p class="{{ $item->message ? '' : 'd-none' }}">{{ $item->message }}</p>
                                                            @if(count($item->attachments) > 0)
                                                                <div class="text-info d-flex time">
                                                                    @forelse($item->attachments as $k => $file)
                                                                        <a href="{{ route('affiliate.ticket.download',encrypt($file->id)) }}"
                                                                           class="file" type="button">
                                                                            <i class="fal fa-file"></i>
                                                                            @lang('File(s)') {{ ++$k}}
                                                                        </a>
                                                                    @empty
                                                                    @endforelse
                                                                </div>
                                                            @endif
                                                            <span class="time">{{ __($item->created_at->format('d M, Y h:i A')) }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                                <form class="form-row mt-4" action="{{ route('affiliate.ticket.reply', $ticket->id)}}"
                                      method="post"
                                      enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div id="imagePreview" class="image-preview-container"></div>
                                    <div class="message-input-box">
                                        <div class="message-image">
                                            <i class="fa-light fa-image"></i>
                                            <input type="file" class="imageInput" name="attachments[]" multiple>
                                        </div>

                                        <div class="message-input">
                                            <input type="text" class="form-control" name="message" placeholder="Type a message">
                                            <button type="submit" name="reply_ticket" value="1"><i class="fa-light fa-arrow-right"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" id="CloseTicketModal" tabindex="-1" role="dialog" aria-labelledby="CloseTicketModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="accountAddCardModalLabel"><i
                            class="bi bi-check2-square"></i> @lang("Confirmation")</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <span>@lang("Do you want to closed this ticket?")</span>
                </div>
                <form action="" method="get" class="setRoute">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white" data-bs-dismiss="modal">@lang("Close")</button>
                        <button type="submit" class="btn btn-primary">@lang("Confirm")</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .supportTicketView{
            padding: 0px 0 100px;
        }
        .listing-top{
            padding: 100px 0 0;
        }
        .sender-inner{
            align-items: center;
        }
        .image-preview-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 0;
            padding: 0 0 45px 23px;
        }

        .image-preview-container img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .message-details{
            display: flex;
            justify-content: left;
            flex-direction: column;
            padding: 0 14px;
            gap: 14px;
        }

        .message-details .text-info{
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 10px;
        }
        .text-end .message-details .text-info{
            justify-content: flex-end;
        }
    </style>
@endpush
@push('script')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const modal = document.getElementById("CloseTicketModal");

            modal.addEventListener("show.bs.modal", function (event) {
                let button = event.relatedTarget;
                let route = button.getAttribute("data-route");

                let form = modal.querySelector("form.setRoute");
                form.setAttribute("action", route);
            });
        });
        document.querySelector('.imageInput').addEventListener('change', function (event) {
            const previewContainer = document.getElementById('imagePreview');
            previewContainer.innerHTML = '';

            const files = event.target.files;
            if (files.length > 0) {
                Array.from(files).forEach(file => {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            previewContainer.appendChild(img);
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
        });
    </script>
@endpush


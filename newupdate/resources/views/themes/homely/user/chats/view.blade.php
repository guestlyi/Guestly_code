@extends(template().'layouts.user')
@section('title',trans('Messages'))
@section('content')
    <section class="message-page">
        <div class="container-fluid">
            <div class="message-page-container">
                <div class="row justify-content-center">
                    <div class="col-lg-4">
                        <div class="message-sidebar">
                            <div class="message-sidebar-header">
                                <h4>@lang('Messages')</h4>
                                <div class="d-flex align-items-center justify-content-end gap-3">
                                    @if(request()->property_slug || request()->booking_uid)
                                        <button type="button" class="message-filter-btn" data-bs-toggle="modal" data-bs-target="#newChatModal">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16">
                                                <path d="M12 5v14M5 12h14"></path>
                                            </svg>
                                        </button>
                                    @endif

                                    <button type="button" class="message-filter-btn reload-btn" onclick="window.location.reload();">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" role="presentation" focusable="false" style="display: block; fill: none; height: 16px; width: 16px; stroke: currentcolor; stroke-width: 2; overflow: visible;">
                                            <path d="M2 12a10 10 0 1 1 4.29 8.36M2 12h6m-6 0l3 3"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div class="message-sidebar-tabs">
                                <div class="reservations">
                                    <div class="tab-content" id="nav-tabContent">
                                        <div class="tab-pane fade show active" id="nav-checking" role="tabpanel" aria-labelledby="nav-checking-tab">
                                            <div id="chat-messages-all">
                                                @include(template().'user.chats.partials.chatList', ['chatList' => $allChat])
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="nav-currently" role="tabpanel" aria-labelledby="nav-currently-tab">
                                            <div class="reservations-container">
                                                <div class="unread-message-content">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <div class="message-container">
                            <div class="message-wrapper">
                                @if(isset($chat))
                                    <div class="message-wrapper-inner" id="messageWrapperInner">
                                        @php
                                            $class = '';
                                            if ($chat->sender_id == auth()->id()){
                                                $class = 'text-end';
                                            }
                                        @endphp
                                        <div class="sender {{ $class }}">
                                            <div class="sender-inner">
                                                <div class="sender-image">
                                                    <img src="{{ getFile($chat->sender?->image_driver, $chat->sender?->image)  }}" alt="photo">
                                                </div>
                                                <div class="message-details">
                                                    @if(isset($chat->message))
                                                        <p>{{ $chat->message }}</p>
                                                    @endif
                                                    <div class="d-flex align-items-center gap-3">
                                                        @if(!empty($chat->attachment))
                                                            @foreach(json_decode($chat->attachment, true) ?? [] as $item)
                                                                <div class="previewAttachmentContainer">
                                                                    <img class="message-single-image" src="{{ getFile($chat->driver, $item) }}" alt="@lang('attachment images')" >
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        @foreach($chat->reply ?? [] as $reply)
                                            @php
                                                $class = '';
                                                if ($reply->sender_id == auth()->id()){
                                                    $class = 'text-end';
                                                }
                                            @endphp
                                            <div class="sender {{ $class }}">
                                                <div class="sender-inner">
                                                    <div class="sender-image">
                                                        <img src="{{ getFile($reply->sender?->image_driver, $reply->sender?->image)  }}" alt="photo">
                                                    </div>
                                                    <div class="message-details">
                                                        @if(isset($reply->message))
                                                            <p>{{ $reply->message }}</p>
                                                        @endif
                                                        <div class="d-flex align-items-center flex-wrap gap-3 message-images ">
                                                            @if(!empty($reply->attachment))
                                                                @foreach(json_decode($reply->attachment, true) ?? [] as $item)
                                                                    <div class="previewAttachmentContainer">
                                                                        <img class="message-single-image" src="{{ getFile($reply->driver, $item) }}" alt="@lang('attachment images')" >
                                                                    </div>
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="message-image-preview previewSelectedContainer" id="previewSelectedContainer"></div>
                                    <form action="{{ route('user.chat.reply') }}" enctype="multipart/form-data" method="POST">
                                        @csrf

                                        <input name="slug" type="hidden" value="{{ request()->property_slug ?? null }}" />
                                        <input name="chat" type="hidden" value="{{ $chat->id ?? null }}" />
                                        <input name="property_id" type="hidden" value="{{ $chat->peoperty_id ?? null }}" />

                                        <div class="message-input-box d-flex">
                                            <div class="message-image">
                                                <i class="fa-light fa-image"></i>
                                                <input type="file" class="imageInput" name="attachments[]" id="imageInput" accept="image/*" multiple>
                                            </div>
                                            <div class="message-input flex-grow-1">
                                                <input type="text" class="form-control" name="message" placeholder="Type a message">
                                                <button type="submit">
                                                    <i class="fa-light fa-arrow-right"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                @else
                                    <div class="message-wrapper-inner no-chat-message">
                                        <p>@lang('No Conversion Message Here')</p>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="newChatModal" tabindex="-1" aria-labelledby="newChatModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newChatModalLabel">@lang('Start New Chat')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('user.chat.new') }}" method="POST">
                    @csrf

                    <div class="modal-body">
                        <input type="hidden" class="form-control" name="property_slug" value="{{ request()->property_slug ?? null }}">
                        <input type="hidden" class="form-control" name="booking_uid" value="{{ request()->booking_uid ?? null }}">
                        <input type="text" class="cmn-input" placeholder="@lang('Enter Message')" name="message">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn-primary">@lang('Start Chat')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('style')
    <style>
        .message-image-preview {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin: 10px 0;
        }

        .message-image-preview .preview-image {
            position: relative;
            width: 80px;
            height: 80px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 6px rgba(0, 0, 0, 0.15);
        }

        .message-image-preview .preview-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .message-image-preview .preview-image .remove-btn {
            position: absolute;
            top: 2px;
            right: 2px;
            background: rgba(0, 0, 0, 0.6);
            color: #fff;
            border: none;
            font-size: 14px;
            width: 18px;
            height: 18px;
            line-height: 18px;
            text-align: center;
            border-radius: 50%;
            cursor: pointer;
        }

        .message-image i{
            cursor: pointer;
        }

        .message-wrapper-inner.no-chat-message{
            display: flex;
            align-items: center;
            justify-content: center;
        }
        #chat-messages-all{
            height: 535px;
            overflow: auto;
        }
    </style>
@endpush
@push('script')
    <script>
        const imageInput = document.getElementById('imageInput');
        const previewSelectedContainer = document.getElementById('previewSelectedContainer');
        let selectedFiles = [];

        imageInput.addEventListener('change', function () {
            const newFiles = Array.from(this.files);
            selectedFiles = selectedFiles.concat(newFiles);
            renderPreviews();
            updateInputFiles();
        });

        function renderPreviews() {
            previewSelectedContainer.innerHTML = '';

            selectedFiles.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const previewBox = document.createElement('div');
                    previewBox.className = 'preview-image';
                    previewBox.innerHTML = `
                    <img src="${e.target.result}" alt="Image Preview">
                    <button type="button" class="remove-btn">&times;</button>
                `;

                    previewBox.querySelector('.remove-btn').addEventListener('click', function () {
                        selectedFiles.splice(index, 1);
                        renderPreviews();
                        updateInputFiles();
                    });
                    previewSelectedContainer.appendChild(previewBox);
                };
                reader.readAsDataURL(file);
            });
        }

        function updateInputFiles() {
            const dataTransfer = new DataTransfer();
            selectedFiles.forEach(file => dataTransfer.items.add(file));
            imageInput.files = dataTransfer.files;
        }
        function scrollToBottom() {
            const container = document.getElementById('messageWrapperInner');
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            scrollToBottom();
        });

        document.querySelector('form').addEventListener('submit', function () {
            setTimeout(scrollToBottom, 300);
        });
    </script>
@endpush


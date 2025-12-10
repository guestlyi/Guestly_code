<form action="{{ route('admin.affiliate.profile.image.update', $affiliate->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="card">
        <div class="profile-cover">
            <div class="profile-cover-img-wrapper">
                <img id="profileCoverImg" class="profile-cover-img" src="{{ asset('assets/admin/img/img1.jpg') }}" alt="Image Description"/>
            </div>
        </div>

        <label class="avatar avatar-xxl avatar-circle avatar-uploader profile-cover-avatar" for="editAvatarUploaderModal">
            <img id="editAvatarImgModal" class="avatar-img" src="{{ getFile($affiliate->image_driver, $affiliate->image) }}" alt="{{ $affiliate->username }}">
            <input type="file" class="js-file-attach avatar-uploader-input" name="profileImage" id="editAvatarUploaderModal" data-hs-file-attach-options='{
                            "textTarget": "#editAvatarImgModal",
                            "mode": "image",
                            "targetAttr": "src",
                            "allowTypes": [".png", ".jpeg", ".jpg"]
                         }'>

            <span class="avatar-uploader-trigger">
          <i class="bi-pencil-square avatar-uploader-icon shadow-sm"></i>
        </span>
        </label>
    </div>
</form>


@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('form[action^="{{ route('admin.affiliate.profile.image.update', '') }}"]');

            function previewImage(input, targetSelector) {
                const file = input.files[0];
                const target = document.querySelector(targetSelector);

                if (file && target) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        target.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            }

            function ajaxSubmit(input) {
                const formData = new FormData(form);

                Notiflix.Loading.standard('Uploading...');
                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: formData
                })
                    .then(async response => {
                        Notiflix.Loading.remove();

                        if (!response.ok) {
                            const err = await response.json();
                            throw new Error(err.message || 'Something went wrong');
                        }

                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            Notiflix.Notify.success(data.message || 'Image uploaded successfully!');
                        } else {
                            Notiflix.Notify.failure(data.message || 'Upload failed.');
                        }
                    })
                    .catch(error => {
                        Notiflix.Loading.remove();
                        Notiflix.Notify.failure(error.message || 'An error occurred.');
                        console.error(error);
                    });
            }

            document.getElementById('editAvatarUploaderModal')?.addEventListener('change', function () {
                previewImage(this, '#editAvatarImgModal');
                ajaxSubmit(this);
            });
        });
    </script>
@endpush

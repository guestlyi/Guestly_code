@push('script')
    @if ($errors->any())
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                @foreach ($errors->all() as $error)
                Notiflix.Notify.failure(@json($error));
                @endforeach
            });
        </script>
    @endif

    <script>
        window.existingImages = @json($existingImages);
        window.generatedImages = @json(session('generated_images', []));

        document.addEventListener("DOMContentLoaded", function () {
            const fileInput = document.querySelector("#fileUpload");
            const previewContainer = document.querySelector(".image-preview");
            const titleInputBox = document.querySelector("#titleInputBox");
            const titleInput = document.querySelector("#titleInput");
            const saveTitleBtn = document.querySelector("#saveTitleBtn");
            const cancelTitleBtn = document.querySelector("#cancelTitleBtn");
            const form = document.querySelector("form");
            let currentImageBox = null;
            let imageCount = 0;
            const dataTransfer = new DataTransfer();

            function addImageBox(src, initialTitle = "", imagePath = "", isExisting = false, originalIndex = null, file = null) {
                const box = document.createElement("div");
                box.className = "image-box";
                box.dataset.index = imageCount;
                box.dataset.existing = isExisting ? "1" : "0";

                const img = document.createElement("img");
                img.src = src;

                const title = document.createElement("div");
                title.className = "image-title";
                title.innerText = initialTitle.trim() === "" ? "Click to add title" : initialTitle;

                const deleteBtn = document.createElement("button");
                deleteBtn.className = "delete-btn";
                deleteBtn.type = "button";
                deleteBtn.innerHTML = '<i class="fa-solid fa-xmark"></i>';

                deleteBtn.onclick = () => {
                    const index = parseInt(box.dataset.index);

                    if (box.dataset.existing === "0") {
                        const currentBoxes = Array.from(previewContainer.querySelectorAll(".image-box")).filter(b => b.dataset.existing === "0");
                        const fileIndex = currentBoxes.indexOf(box);

                        if (fileIndex !== -1) {
                            dataTransfer.items.remove(fileIndex);
                            fileInput.files = dataTransfer.files;
                        }
                    }

                    box.remove();
                    form.querySelectorAll(`input[data-index="${box.dataset.index}"]`).forEach(el => el.remove());
                };

                img.onclick = () => {
                    currentImageBox = box;
                    titleInputBox.style.display = "flex";
                    titleInput.value = title.innerText === "Click to add title" ? "" : title.innerText;
                };

                box.appendChild(img);
                box.appendChild(title);
                box.appendChild(deleteBtn);
                previewContainer.appendChild(box);

                if (isExisting) {
                    const hiddenTitleInput = document.createElement("input");
                    hiddenTitleInput.type = "hidden";
                    hiddenTitleInput.name = `existingTitles[]`;
                    hiddenTitleInput.value = initialTitle;
                    hiddenTitleInput.dataset.index = imageCount;
                    hiddenTitleInput.dataset.type = "title";
                    form.appendChild(hiddenTitleInput);

                    const hiddenPathInput = document.createElement("input");
                    hiddenPathInput.type = "hidden";
                    hiddenPathInput.name = `existingImages[]`;
                    hiddenPathInput.value = imagePath || src;
                    hiddenPathInput.dataset.index = imageCount;
                    hiddenPathInput.dataset.type = "image";
                    form.appendChild(hiddenPathInput);

                    const hiddenIndexInput = document.createElement("input");
                    hiddenIndexInput.type = "hidden";
                    hiddenIndexInput.name = `existingIndexes[]`;
                    hiddenIndexInput.value = originalIndex;
                    hiddenIndexInput.dataset.index = imageCount;
                    hiddenIndexInput.dataset.type = "index";
                    form.appendChild(hiddenIndexInput);
                } else {
                    const hiddenNewTitle = document.createElement("input");
                    hiddenNewTitle.type = "hidden";
                    hiddenNewTitle.name = `newTitles[]`;
                    hiddenNewTitle.value = initialTitle;
                    hiddenNewTitle.dataset.index = imageCount;
                    hiddenNewTitle.dataset.type = "title";
                    form.appendChild(hiddenNewTitle);

                    if (file) {
                        dataTransfer.items.add(file);
                        fileInput.files = dataTransfer.files;
                    }
                }

                imageCount++;
            }

            const existingImages = window.existingImages || [];
            existingImages.forEach((img, i) => {
                addImageBox(img.url, img.title, img.path, true, i);
            });

            fileInput.addEventListener("change", function () {
                [...this.files].forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        addImageBox(e.target.result, "", "", false, null, file);
                    };
                    reader.readAsDataURL(file);
                });

                fileInput.value = "";
            });

            saveTitleBtn.addEventListener("click", function () {
                if (currentImageBox) {
                    const index = currentImageBox.dataset.index;
                    const titleDiv = currentImageBox.querySelector(".image-title");
                    const titleText = titleInput.value.trim() || "Click to add title";
                    titleDiv.innerText = titleText;

                    const hiddenInput = form.querySelector(`input[data-index="${index}"][data-type="title"]`);
                    if (hiddenInput) hiddenInput.value = titleText;

                    titleInputBox.style.display = "none";
                    currentImageBox = null;
                }
            });

            cancelTitleBtn.addEventListener("click", function () {
                titleInputBox.style.display = "none";
                currentImageBox = null;
            });

            document.querySelectorAll('.image-count-box').forEach(box => {
                box.addEventListener('click', function () {
                    document.querySelectorAll('.image-count-box').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    document.getElementById('imageCount').value = this.getAttribute('data-value');
                });
            });

            $('#thumbUpload').on('change', function (event) {
                const file = event.target.files[0];
                const previewContainer = $('.thumb-preview');
                previewContainer.empty();

                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const img = $('<img />', {
                            src: e.target.result,
                            class: 'img-fluid',
                            style: 'max-width: 100%; height: auto; margin-top: 10px; border-radius: 10px;'
                        });
                        previewContainer.append(img);
                    };
                    reader.readAsDataURL(file);
                } else {
                    previewContainer.html('<p style="color:red;">Invalid file type. Please upload an image.</p>');
                }
            });

            $('#imageType').on('change', function () {
                const selectedType = $(this).val();
                const imageCountWrapper = $('#imageCountWrapper');
                const previewContainer = document.querySelector('.previewImages');

                if (selectedType === 'images') {
                    imageCountWrapper.removeClass('d-none');
                } else {
                    imageCountWrapper.addClass('d-none');
                }

                previewContainer.innerHTML = '';

                const sessionData = window.generatedImages || {};
                const imagesForType = sessionData[selectedType]?.image_data_uris || [];

                if (imagesForType.length > 0) {
                    renderPreviewImages(imagesForType);
                }
            }).trigger('change');

            const generateBtn = document.getElementById('generateImageBtn');

            generateBtn.addEventListener('click', function () {
                const title = document.getElementById('generateImageTitle').value.trim();
                const imageType = document.getElementById('imageType').value;
                const imageCount = document.getElementById('imageCount')?.value || 1;

                if (!title) {
                    Notiflix.Notify.failure("Please enter a package title.");
                    return;
                }

                const formData = {
                    imageDescription: title,
                    image_type: imageType,
                    image_count: imageType === 'images' ? imageCount : 1
                };

                Notiflix.Loading.standard('Generating...');

                fetch("{{ route('user.listing.ai.generate.image') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify(formData)
                })
                    .then(response => response.json())
                    .then(data => {
                        const sessionData = data.session_data || {};
                        const type = data.type;

                        const imagesForType = sessionData[type]?.image_data_uris ?? [];

                        Notiflix.Loading.remove();

                        if (data.status === 'success') {
                            Notiflix.Notify.success("Images generated successfully!");
                            renderPreviewImages(imagesForType);
                        } else {
                            Notiflix.Notify.failure(data.message || "Failed to generate images.");
                        }
                    })
                    .catch(error => {
                        Notiflix.Loading.remove();
                        Notiflix.Notify.failure("Something went wrong while generating images.");
                    });
            });

            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.forEach(function (tooltipTriggerEl) {
                new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });

        function renderPreviewImages(imagesForType) {
            const previewDiv = document.querySelector(".previewImages");
            previewDiv.innerHTML = "";

            imagesForType.forEach((imageUri, index) => {
                const wrapper = document.createElement("div");
                wrapper.classList.add("image-preview");

                const img = document.createElement("img");
                img.src = imageUri;
                img.alt = `Generated Preview ${index + 1}`;
                img.classList.add("generated-imageView");

                const iconWrapper = document.createElement("div");
                iconWrapper.classList.add("image-icons");

                const downloadLink = document.createElement("a");
                downloadLink.href = imageUri;
                downloadLink.download = `generated-image-${index + 1}.png`;
                downloadLink.title = "Download Image";
                downloadLink.innerHTML = '<i class="far fa-download"></i>';

                const selectBtn = document.createElement("button");
                selectBtn.type = "button";
                selectBtn.title = "Select Image";
                selectBtn.innerHTML = '<i class="far fa-check-circle"></i>';

                selectBtn.addEventListener("click", function () {
                    const isSelected = wrapper.classList.toggle("image-selected");
                    selectBtn.style.background = isSelected ? "var(--primary-color)" : "rgba(0, 0, 0, 0.2)";

                    const selectedType = document.getElementById("imageType").value;

                    if (!isSelected) {
                        if (selectedType === "images") {
                            const imagePreview = document.querySelector(".images-preview");
                            const imgToRemove = imagePreview.querySelector(`[data-src="${imageUri}"]`);
                            if (imgToRemove) imgToRemove.remove();

                            const fileInput = document.getElementById("fileUpload");
                            const dt = new DataTransfer();

                            for (let i = 0; i < fileInput.files.length; i++) {
                                const f = fileInput.files[i];
                                if (!f.name.includes("ai-image") || f.name !== wrapper.dataset.fileName) {
                                    dt.items.add(f);
                                }
                            }

                            fileInput.files = dt.files;
                        } else if (selectedType === "thumbnail") {
                            const thumbPreview = document.querySelector(".thumb-preview");
                            const thumbToRemove = thumbPreview.querySelector(`[data-src="${imageUri}"]`);
                            if (thumbToRemove) thumbToRemove.remove();

                            const thumbInput = document.getElementById("thumbUpload");
                            const dtThumb = new DataTransfer();

                            for (let i = 0; i < thumbInput.files.length; i++) {
                                const f = thumbInput.files[i];
                                if (!f.name.includes("ai-image") || f.name !== wrapper.dataset.fileName) {
                                    dtThumb.items.add(f);
                                }
                            }

                            thumbInput.files = dtThumb.files;
                        }

                        return;
                    }

                    fetch(imageUri)
                        .then(res => res.blob())
                        .then(blob => {
                            const fileName = `ai-image-${Date.now()}.png`;
                            const file = new File([blob], fileName, { type: blob.type });

                            if (selectedType === "thumbnail") {
                                const thumbPreview = document.querySelector(".thumb-preview");
                                thumbPreview.innerHTML = `
                                <div class="thumb-item">
                                    <img src="${imageUri}" alt="Thumbnail" class="preview-image" />
                                </div>
                            `;
                                const dt = new DataTransfer();
                                dt.items.add(file);
                                document.getElementById("thumbUpload").files = dt.files;
                            }

                            if (selectedType === "images") {
                                const imagePreview = document.querySelector(".images-preview");
                                const imageBox = document.createElement("div");
                                imageBox.classList.add("image-box");
                                imageBox.dataset.index = Date.now();
                                imageBox.dataset.existing = "1";
                                imageBox.dataset.src = imageUri;
                                imageBox.dataset.fileName = fileName;

                                const img = document.createElement("img");
                                img.src = imageUri;
                                img.alt = "Generated Image";
                                img.classList.add("preview-image");

                                const titleDiv = document.createElement("div");
                                titleDiv.classList.add("image-title");
                                titleDiv.textContent = "Click to add title";

                                const deleteBtn = document.createElement("button");
                                deleteBtn.type = "button";
                                deleteBtn.classList.add("delete-btn");
                                deleteBtn.innerHTML = '<i class="fa-solid fa-xmark"></i>';

                                deleteBtn.addEventListener("click", () => {
                                    imageBox.remove();
                                    const fileInput = document.getElementById("fileUpload");
                                    const dt = new DataTransfer();
                                    for (let i = 0; i < fileInput.files.length; i++) {
                                        const f = fileInput.files[i];
                                        if (f.name !== fileName) dt.items.add(f);
                                    }
                                    fileInput.files = dt.files;
                                    wrapper.classList.remove("image-selected");
                                    selectBtn.style.background = "rgba(0, 0, 0, 0.2)";
                                });

                                titleDiv.addEventListener("click", () => {
                                    const input = document.createElement("input");
                                    input.type = "text";
                                    input.value = titleDiv.textContent === "Click to add title" ? "" : titleDiv.textContent;
                                    input.classList.add("image-title-input");

                                    imageBox.replaceChild(input, titleDiv);
                                    input.focus();

                                    function saveTitle() {
                                        const newTitle = input.value.trim() || "Click to add title";
                                        titleDiv.textContent = newTitle;
                                        imageBox.replaceChild(titleDiv, input);
                                    }

                                    input.addEventListener("blur", saveTitle);
                                    input.addEventListener("keydown", e => {
                                        if (e.key === "Enter") saveTitle();
                                        if (e.key === "Escape") imageBox.replaceChild(titleDiv, input);
                                    });
                                });

                                imageBox.appendChild(img);
                                imageBox.appendChild(titleDiv);
                                imageBox.appendChild(deleteBtn);
                                imagePreview.appendChild(imageBox);

                                const fileInput = document.getElementById("fileUpload");
                                const dt = new DataTransfer();

                                for (let i = 0; i < fileInput.files.length; i++) {
                                    dt.items.add(fileInput.files[i]);
                                }
                                dt.items.add(file);
                                fileInput.files = dt.files;

                                wrapper.dataset.fileName = fileName;
                            }
                        });
                });

                iconWrapper.appendChild(selectBtn);
                iconWrapper.appendChild(downloadLink);

                wrapper.appendChild(img);
                wrapper.appendChild(iconWrapper);
                previewDiv.appendChild(wrapper);
            });
        }

        const form = document.getElementById('photosForm');
        const postUrl = form.action;
        const redirectUrl = '{{ route('user.listing.title') }}';
        @include(template().'vendor.listing.partials.cmn_script')
    </script>
@endpush

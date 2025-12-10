@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const input = document.getElementById('metaKeywordInput');
            const wrapper = document.getElementById('keyword-wrapper');

            input.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' || e.key === ',') {
                    e.preventDefault();
                    const keyword = input.value.trim();

                    if (keyword) {
                        addKeywordTag(keyword);
                        input.value = '';
                    }
                }
            });

            wrapper.addEventListener('click', function (e) {
                if (e.target.classList.contains('btn-close')) {
                    e.target.parentElement.remove();
                }
            });

            function addKeywordTag(keyword) {
                const existing = Array.from(wrapper.querySelectorAll('input[name="meta_keywords[]"]'))
                    .map(input => input.value.toLowerCase());

                if (existing.includes(keyword.toLowerCase())) return;

                const span = document.createElement('span');
                span.className = 'keyword-tag badge bg-light text-dark border border-secondary pb-2';
                span.innerHTML = `
                    ${keyword}
                    <input type="hidden" name="meta_keywords[]" value="${keyword}">
                    <button type="button" class="btn-close btn-sm ms-2" aria-label="Remove"></button>
                `;

                wrapper.insertBefore(span, input);
            }
        });
        document.addEventListener("DOMContentLoaded", function () {
            const dropdown = document.getElementById('metaRobotsDropdown');
            const list = document.getElementById('metaRobotsList');
            const selected = document.getElementById('metaRobotsSelected');
            const inputs = document.getElementById('metaRobotsInputs');

            const listItems = list.querySelectorAll('.dropdown-item');

            const selectedSet = new Set(
                [...inputs.querySelectorAll('input')].map(input => input.value)
            );

            function render() {
                selected.innerHTML = '';
                inputs.innerHTML = '';

                const placeholder = dropdown.querySelector('.placeholder');
                placeholder?.classList.toggle('d-none', selectedSet.size > 0);


                selectedSet.forEach(value => {
                    const badge = document.createElement('span');
                    badge.className = 'badge bg-primary-subtle text-primary me-1 mb-1 pb-2';
                    badge.style.cursor = 'pointer';
                    badge.innerHTML = `${value} <span class="ms-1">&times;</span>`;
                    badge.onclick = () => {
                        selectedSet.delete(value);
                        render();
                    };
                    selected.appendChild(badge);

                    const hidden = document.createElement('input');
                    hidden.type = 'hidden';
                    hidden.name = 'meta_robots[]';
                    hidden.value = value;
                    inputs.appendChild(hidden);
                });

                listItems.forEach(item => {
                    const value = item.getAttribute('data-value');
                    const checkmark = item.querySelector('.checkmark');

                    if (selectedSet.has(value)) {
                        item.classList.add('active');
                        if (checkmark) checkmark.classList.remove('d-none');
                    } else {
                        item.classList.remove('active');
                        if (checkmark) checkmark.classList.add('d-none');
                    }
                });
            }

            dropdown.addEventListener('click', function (e) {
                e.stopPropagation();
                list.classList.toggle('d-none');
            });

            listItems.forEach(item => {
                item.addEventListener('click', function (e) {
                    e.stopPropagation();
                    const value = this.getAttribute('data-value');
                    if (!selectedSet.has(value)) {
                        selectedSet.add(value);
                        render();
                    }
                    list.classList.add('d-none');
                });
            });

            document.addEventListener('click', function (e) {
                if (!dropdown.contains(e.target) && !list.contains(e.target)) {
                    list.classList.add('d-none');
                }
            });

            render();
        });
        document.addEventListener('DOMContentLoaded', function () {
            const fileInput = document.getElementById('imageUploader');
            const imgPreview = document.getElementById('SeoImg');

            fileInput.addEventListener('change', function (e) {
                const file = this.files[0];
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        imgPreview.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
@endpush

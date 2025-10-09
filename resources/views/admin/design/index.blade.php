<x-admin-layout>
    <x-slot name="style">
        <style>
            #design-table th,
            #design-table td {
                text-align: center;
            }
        </style>
    </x-slot>

    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Thiết kế') }}
        </h2>
    </x-slot>

    @if (request()->routeIs('designs.index'))
        @include('admin.design.partials.datatables')
    @elseif (request()->routeIs('designs.edit'))
        @include('admin.design.partials.edit-design-form')
    @elseif (request()->routeIs('designs.create'))
        @include('admin.design.partials.add-design-form')
    @endif

    <x-slot name="script">
        <script>
            let imageFiles = [];

            function deleteDesign(id) {
                if (confirm('Bạn chắc chắn muốn xóa hoàn toàn thiết kế này chứ?')) {
                    window.location.href = `{{ url('designs/delete') }}/${id}`;
                }
            }

            function updateMainImage(src) {
                $('#mainImage').attr('src', src).removeClass('hidden');
            }

            function deleteImage(index) {
                imageFiles.splice(index, 1);
                $(`#main-image-select-${index}`).remove();
                updateThumbnails();
                updateMainImageSelector();
                if (imageFiles.length > 0) {
                    updateMainImage(imageFiles[0].dataUrl);
                } else {
                    $('#mainImage').addClass('hidden');
                }
            }

            // Update thumbnails for add form
            function updateThumbnails() {
                const $container = $('#thumbnailContainer');
                $container.empty();

                imageFiles.forEach((imageData, index) => {
                    const $div = $('<div>', {
                        class: 'relative group flex-none'
                    }).append(`
                        <img class="w-24 h-24 object-cover rounded-lg cursor-pointer hover:ring-1 hover:ring-primary thumbnail-image"
                            src="${imageData.dataUrl}"
                            alt="Design thumbnail"
                            onclick="updateMainImage('${imageData.dataUrl}')">
                        <div class="absolute top-1 right-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button class="btn btn-error btn-xs btn-circle" onclick="deleteImage(${index})">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <div class="absolute top-1 left-1 opacity-0 group-hover:opacity-100 transition-opacity text-gray-900 text-xs z-10 bg-white border border-black rounded-full w-6 h-6 flex items-center justify-center">
                            <p>${index+1}</p>
                        </div>
                    `);
                    $container.append($div);
                });
            }

            // Create thumbnail element for edit form
            function createThumbnailElement(imageId, imageUrl) {
                return `
                        <div class="relative group flex-none">
                            <img class="w-24 h-24 object-cover rounded-lg cursor-pointer hover:ring-1 hover:ring-primary thumbnail-image"
                                src="${imageUrl}"
                                alt="Design thumbnail"
                                onclick="updateMainImage('${imageUrl}')">
                            <div class="absolute top-1 right-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button class="btn btn-error btn-xs btn-circle">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <div class="absolute top-1 left-1 opacity-0 group-hover:opacity-100 transition-opacity text-gray-900 text-xs z-10 bg-white border border-black rounded-full w-6 h-6 flex items-center justify-center">
                                <p>${imageId}</p>
                            </div>
                        </div>
                        `;
            }

            function updateMainImageSelector(edit = false, id = null, image = null) {
                const $select = $('#main-image-select');
                if (edit) {
                    const $option = $('<option>', {
                        id: `main-image-select-${id}`,
                        value: id,
                        text: `Image ${id}: ${image}`
                    });
                    $select.append($option);
                } else {
                    $select.empty();
                    imageFiles.forEach((imageData, index) => {
                        const $option = $('<option>', {
                            id: `main-image-select-${index}`,
                            value: index,
                            text: `Image ${index+1}: ${imageData.file.name}`
                        });
                        $select.append($option);
                    });
                }
            }

            $(document).ready(function() {
                // DataTable initialization with Server-Side Processing
                $('#design-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('designs.data') }}",
                        type: 'GET'
                    },
                    columns: [{
                            data: 0,
                            orderable: false
                        }, // STT
                        {
                            data: 1
                        }, // Tên
                        {
                            data: 2
                        }, // Mã
                        {
                            data: 3
                        }, // Giá
                        {
                            data: 4,
                            orderable: false
                        }, // Ảnh chính
                        {
                            data: 5
                        }, // Danh mục
                        {
                            data: 6,
                            orderable: false
                        }, // Nhãn
                        {
                            data: 7
                        }, // Xuất hiện
                        {
                            data: 8,
                            orderable: false
                        } // Hành động
                    ],
                    pageLength: 10,
                    lengthMenu: [
                        [10, 25, 50, 100],
                        [10, 25, 50, 100]
                    ],
                    order: [
                        [1, 'asc']
                    ], // Default sort by Tên
                    language: {
                        "processing": "Đang tải dữ liệu...",
                        "entries per page": "số bản ghi mỗi trang",
                        "search": "Tìm kiếm",
                        "info": "Hiển thị _START_ đến _END_ của _TOTAL_ bản ghi",
                        "infoEmpty": "Showing 0 to 0 of 0 entries",
                        "emptyTable": "Không có dữ liệu",
                        "zeroRecords": "Không tìm thấy dữ liệu phù hợp",
                        "infoFiltered": "(lọc từ _MAX_ bản ghi)",
                        "lengthMenu": "Hiển thị _MENU_ bản ghi",
                        paginate: {
                            "first": "Đầu",
                            "last": "Cuối",
                            "next": "Tiếp theo",
                            "previous": "Trước đó"
                        }
                    }
                });

                // Handle image change for edit form
                $('#image-edit').change(function() {
                    if (this.files && this.files[0]) {
                        const $progressBar = $('#upload-progress');
                        $progressBar.removeClass('hidden');
                        let formData = new FormData();

                        //TODO: think again about this later
                        formData.append('image', this.files[0]);
                        formData.append('design_id', '{{ $design->id ?? '' }}');
                        formData.append('_token', '{{ csrf_token() }}');

                        $.ajax({
                            url: '{{ route('designs.upload-image') }}',
                            type: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(data) {
                                if (data.success) {
                                    // Add new thumbnail
                                    const newThumbnail = $(createThumbnailElement(data.image_id,
                                        data.image_url));
                                    $('.overflow-x-auto').prepend(newThumbnail);
                                    // Update main image if empty
                                    if (!$('#mainImage').attr('src')) {
                                        updateMainImage(data.image_url);
                                    }
                                    // Update main image selector
                                    updateMainImageSelector(true, data.image_id, data.image_name);
                                }
                            },
                            error: function(error) {
                                console.error('Error:', error);
                            },
                            complete: function() {
                                $('#image').val('');
                                $progressBar.addClass('hidden');
                                showToast('Tải ảnh lên thành công', 'success');
                            }
                        });
                    }
                });

                // Handle image change for add form
                $('#image').on('change', function(event) {
                    const files = Array.from(this.files);

                    files.forEach(file => {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const imageData = {
                                dataUrl: e.target.result,
                                file: file
                            };
                            imageFiles.push(imageData);

                            updateThumbnails();
                            updateMainImageSelector();
                            if (!$('#mainImage').attr('src')) {
                                updateMainImage(imageData.dataUrl);
                            }
                        };
                        reader.readAsDataURL(file);
                    });
                });

                // Handle form submission for add form
                $('#designForm').on('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(this);
                    imageFiles.forEach((imageData, index) => {
                        formData.append(`images[]`, imageData.file);
                    });

                    $.ajax({
                        url: $(this).attr('action'),
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.success) {
                                showToast(response.message, 'success');
                                setTimeout(() => {
                                    window.location.href = response.redirect;
                                }, 2000);
                            } else {
                                showToast(response.message, 'error');
                            }
                        },
                        error: function(xhr) {
                            const response = xhr.responseJSON;
                            showToast(response.message, 'error');
                        }
                    });
                });

                // Handle image deletion for edit form
                $(document).on('click', '.delete-image', function() {
                    const imageId = $(this).data('id');
                    if (!imageId) return;

                    const $imageContainer = $(`#image-${imageId}`);
                    if (confirm(`Bạn có chắc bạn muốn xóa ảnh ${imageId}?`)) {
                        $.ajax({
                            url: `{{ url('designs/images') }}/${imageId}`,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    $imageContainer.fadeOut(300, function() {
                                        $(this).remove();
                                        // Update main image if needed
                                        const mainImageSrc = $('#mainImage').attr('src');
                                        if (mainImageSrc.includes($imageContainer.find(
                                                'img').attr('src'))) {
                                            const nextImage = $('.thumbnail-image').first();
                                            if (nextImage.length) {
                                                updateMainImage(nextImage.attr('src'));
                                            } else {
                                                $('#mainImage').attr('src', '');
                                            }
                                        }

                                        // Update main image selector
                                        $(`#main-image-select-${imageId}`).remove();

                                        showToast(response.message, 'success');
                                    });
                                } else {
                                    showToast(response.message, 'error');
                                }
                            },
                            error: function(error) {
                                console.error('Error:', error);
                                showToast('Có lỗi xảy ra khi xóa ảnh', 'error');
                            }
                        });
                    }
                });
            });
        </script>
    </x-slot>
</x-admin-layout>

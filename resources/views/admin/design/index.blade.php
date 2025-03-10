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
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Designs') }}
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
                if (confirm('Bạn chắc chắn muốn xóa hoàn toàn item này chứ?')) {
                    window.location.href = `{{ url('designs/delete') }}/${id}`;
                }
            }

            function updateMainImage(src) {
                $('#mainImage').attr('src', src).removeClass('hidden');
            }

            function deleteImage(index) {
                imageFiles.splice(index, 1);
                updateThumbnails();
                if (imageFiles.length > 0) {
                    updateMainImage(imageFiles[0].dataUrl);
                } else {
                    $('#mainImage').addClass('hidden');
                }
            }

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
                    `);
                    $container.append($div);
                });
            }

            // Create thumbnail element
            function createThumbnailElement(imageUrl) {
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
                        </div>
                    `;
            }

            function updateMainImageSelector() {
                const $select = $('#main-image-select');
                $select.empty();

                imageFiles.forEach((imageData, index) => {
                    const $option = $('<option>', {
                        value: index,
                        text: `Image ${index+1}: ${imageData.file.name}`
                    });
                    $select.append($option);
                });
            }

            $(document).ready(function() {
                // DataTable initialization
                $('#design-table').DataTable({
                    language: {
                        paginate: {
                            "first": "",
                            "last": "",
                            "next": "Next",
                            "previous": "Previous"
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
                                    const newThumbnail = $(createThumbnailElement(data.image_url));
                                    $('.overflow-x-auto').prepend(newThumbnail);
                                    // Update main image if empty
                                    if (!$('#mainImage').attr('src')) {
                                        updateMainImage(data.image_url);
                                    }
                                }
                            },
                            error: function(error) {
                                console.error('Error:', error);
                            },
                            complete: function() {
                                $('#image').val('');
                                $progressBar.addClass('hidden');
                                showToast('Image added successfully.', 'success');
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
                            alert(response.message);
                        }
                    });
                });

                // Handle image deletion for edit form
                $(document).on('click', '.delete-image', function() {
                    const imageId = $(this).data('id');
                    if (!imageId) return;

                    const $imageContainer = $(`#image-${imageId}`);
                    if (confirm('Are you sure you want to delete this image?')) {
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
                                    });
                                } else {
                                    showToast(response.message, 'error');
                                }
                            },
                            error: function(error) {
                                console.error('Error:', error);
                                showToast('An error occurred while deleting the image.', 'error');
                            }
                        });
                    }
                });
            });
        </script>
    </x-slot>
</x-admin-layout>

<x-admin-layout>
    <x-slot name="style">
        <style>
            #design-table th,
            #design-table td,
            #tag-table th,
            #tag-table td {
                text-align: center;
            }
        </style>
    </x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tags') }}
        </h2>
    </x-slot>

    @if (request()->routeIs('tags.index'))
        @include('admin.tag.partials.datatables')
    @elseif (request()->routeIs('tags.edit'))
        @include('admin.tag.partials.edit-tag-form')
    @elseif (request()->routeIs('tags.create'))
        @include('admin.tag.partials.add-tag-form')
    @endif

    <x-slot name="script">
        <script>
            let imageFiles = [];

            function deleteTag(id) {
                if (confirm('Bạn chắc chắn muốn xóa hoàn toàn item này chứ?')) {
                    window.location.href = `{{ url('tags/delete') }}/${id}`;
                }
            }

            $(document).ready(function() {
                // DataTable initialization
                $('#tag-table').DataTable({
                    language: {
                        paginate: {
                            "first": "",
                            "last": "",
                            "next": "Next",
                            "previous": "Previous"
                        }
                    }
                });

                // // Handle image change for add form
                // $('#image').on('change', function(event) {
                //     const files = Array.from(this.files);

                //     files.forEach(file => {
                //         const reader = new FileReader();
                //         reader.onload = function(e) {
                //             const imageData = {
                //                 dataUrl: e.target.result,
                //                 file: file
                //             };
                //             imageFiles.push(imageData);

                //             updateThumbnails();
                //             if (!$('#mainImage').attr('src')) {
                //                 updateMainImage(imageData.dataUrl);
                //             }
                //         };
                //         reader.readAsDataURL(file);
                //     });
                // });

                // // Handle form submission for add form
                // $('#designForm').on('submit', function(e) {
                //     e.preventDefault();

                //     const formData = new FormData(this);
                //     imageFiles.forEach((imageData, index) => {
                //         formData.append(`images[]`, imageData.file);
                //     });

                //     $.ajax({
                //         url: $(this).attr('action'),
                //         method: 'POST',
                //         data: formData,
                //         processData: false,
                //         contentType: false,
                //         success: function(data) {
                //             if (data.success) {
                //                 window.location.href = '{{ route('designs.index') }}';
                //             }
                //         },
                //         error: function(error) {
                //             console.error('Error:', error);
                //         }
                //     });
                // });

                // // Handle image deletion for edit form
                // $(document).on('click', '.delete-image', function() {
                //     const imageId = $(this).data('id');
                //     if (!imageId) return;

                //     const $imageContainer = $(`#image-${imageId}`);
                //     if (confirm('Are you sure you want to delete this image?')) {
                //         $.ajax({
                //             url: `{{ url('designs/images') }}/${imageId}`,
                //             type: 'DELETE',
                //             data: {
                //                 _token: '{{ csrf_token() }}'
                //             },
                //             success: function(response) {
                //                 if (response.success) {
                //                     $imageContainer.fadeOut(300, function() {
                //                         $(this).remove();
                //                         // Update main image if needed
                //                         const mainImageSrc = $('#mainImage').attr('src');
                //                         if (mainImageSrc.includes($imageContainer.find('img').attr('src'))) {
                //                             const nextImage = $('.thumbnail-image').first();
                //                             if (nextImage.length) {
                //                                 updateMainImage(nextImage.attr('src'));
                //                             } else {
                //                                 $('#mainImage').attr('src', '');
                //                             }
                //                         }
                //                     });
                //                 }
                //             },
                //             error: function(error) {
                //                 console.error('Error:', error);
                //                 alert('Failed to delete image');
                //             }
                //         });
                //     }
                // });
            });
        </script>
    </x-slot>
</x-admin-layout>

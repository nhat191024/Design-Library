<x-admin-layout>
    <x-slot name="style">
        <style>
            #design-table th {
                text-align: center;
            }

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
    @endif

    <x-slot name="script">
        <script>
            $(document).ready(function() {
                // DataTable initialization
                $('#design-table').DataTable({
                    language: {
                        paginate: {
                            "first": "",
                            "last": "",
                            "next": "Next",
                            "previous": "Previous"
                        },
                    }
                });

                // Handle image upload
                $('#image').change(function() {
                    if (this.files && this.files[0]) {
                        const $progressBar = $('#upload-progress');

                        $progressBar.removeClass('hidden');

                        let formData = new FormData();
                        formData.append('image', this.files[0]);
                        formData.append('design_id', '{{ $design->id ?? "" }}');
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
                            }
                        });
                    }
                });

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
            });
        </script>
    </x-slot>
</x-admin-layout>

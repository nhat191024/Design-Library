<x-admin-layout>
    <x-slot name="style">
        <style>
            #design-table th,
            #design-table td,
            #tag-table th,
            #tag-table td,
            #category-table th,
            #category-table td {
                text-align: center;
            }
        </style>
    </x-slot>

    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Danh mục') }}
        </h2>
    </x-slot>

    @if (request()->routeIs('categories.index'))
        @include('admin.category.partials.datatables')
    @elseif (request()->routeIs('categories.edit'))
        @include('admin.category.partials.edit-category-form')
    @elseif (request()->routeIs('categories.create'))
        @include('admin.category.partials.add-category-form')
    @endif

    <x-slot name="script">
        <script>
            let imageFiles = [];

            function deleteCategory(id) {
                if (confirm('Bạn chắc chắn muốn xóa hoàn toàn danh mục này chứ?')) {
                    window.location.href = `{{ url('categories/delete') }}/${id}`;
                }
            }

            $(document).ready(function() {
                // Xử lý preview ảnh
                $('#image').on('change', function(e) {
                    const files = e.target.files;
                    if (files && files.length > 0) {
                        const firstFile = files[0];
                        const reader = new FileReader();

                        reader.onload = function(e) {
                            $('#mainImage')
                                .attr('src', e.target.result)
                                .removeClass('hidden');
                        };

                        reader.readAsDataURL(firstFile);
                    } else {
                        $('#mainImage')
                            .attr('src', '')
                            .addClass('hidden');
                    }
                });

                // Khởi tạo DataTable với Server-Side Processing
                $('#category-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('categories.data') }}",
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
                            data: 2,
                            orderable: false
                        }, // Ảnh
                        {
                            data: 3
                        }, // Danh mục cha
                        {
                            data: 4
                        }, // Hiển thị
                        {
                            data: 5,
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
            });
        </script>
    </x-slot>
</x-admin-layout>

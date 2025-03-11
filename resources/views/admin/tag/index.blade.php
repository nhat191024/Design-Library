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
            {{ __('Nhãn') }}
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
                if (confirm('Bạn chắc chắn muốn xóa hoàn toàn nhãn này chứ?')) {
                    window.location.href = `{{ url('tags/delete') }}/${id}`;
                }
            }

            $(document).ready(function() {
                // DataTable initialization
                $('#tag-table').DataTable({
                    language: {
                        "entries per page": "số bản ghi mỗi trang",
                        "search": "Tìm kiếm",
                        "info": "Hiển thị _START_ đến _END_ của _TOTAL_ bản ghi",
                        "infoEmpty": "Showing 0 to 0 of 0 entries",
                        "emptyTable": "Không có dữ liệu",
                        "zeroRecords": "Không tìm thấy dữ liệu phù hợp",
                        "infoFiltered": "(filtered from _MAX_ total records)",
                        "lengthMenu": "Hiển thị _MENU_ bản ghi",
                        paginate: {
                            "first": "",
                            "last": "",
                            "next": "Tiếp theo",
                            "previous": "Trước đó"
                        }
                    }
                });
            });
        </script>
    </x-slot>
</x-admin-layout>

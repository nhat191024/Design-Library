<section>
    <div class="py-12">
        <div class="mx-auto max-w-full sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <a class="btn btn-success float-end mb-5" href="{{ route('designs.create') }}">Thêm thiết kế</a>
                    <table id="design-table" class="display order-column">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Tên</th>
                                <th>Mã</th>
                                <th>Giá</th>
                                <th>Ảnh chính</th>
                                <th>Danh mục</th>
                                <th>Nhãn</th>
                                <th>Xuất hiện trên trang chủ?</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Data will be loaded via AJAX --}}
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>STT</th>
                                <th>Tên</th>
                                <th>Mã</th>
                                <th>Giá</th>
                                <th>Ảnh chính</th>
                                <th>Danh mục</th>
                                <th>Nhãn</th>
                                <th>Xuất hiện trên trang chủ?</th>
                                <th>Hành động</th>
                            </tr>
                        </tfoot>
                    </table>

                </div>
            </div>
        </div>
    </div>
</section>

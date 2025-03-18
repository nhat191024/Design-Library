<section>
    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <a href="{{ route('designs.create') }}" class="btn btn-success float-end mb-5">Thêm thiết kế</a>
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
                            @foreach ($designs as $key => $design)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $design->name }}</td>
                                    <td>{{ $design->code }}</td>
                                    <td class="w-[15%]">{{ $design->price }}</td>
                                    <td>
                                        @if ($design->MainImage && $design->MainImage->count() > 0)
                                            <img class="w-24 h-24 object-contain mx-auto"
                                                src="{{ asset($design->MainImage->url) }}" alt="">
                                        @else
                                            <p>Lỗi Ảnh</p>
                                        @endif
                                    </td>
                                    <td>{{ $design->category->name }}</td>
                                    <td class="w-1/6">
                                        @foreach ($design->tags as $tag)
                                            <span class="badge badge-info">
                                                {{ $tag->name }}
                                            </span>
                                        @endforeach
                                    </td>
                                    <td class="w-[10%]">
                                        @if ($design->is_showcase)
                                            <span class="badge badge-success">Hiển thị</span>
                                        @else
                                            <span class="badge badge-danger">Không hiển thị</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('designs.edit', $design->id) }}"
                                            class="btn btn-sm btn-primary">Sửa</a>
                                        <button class="btn btn-sm btn-error"
                                            onclick="deleteDesign({{ $design->id }})">Xóa</button>
                                    </td>
                                </tr>
                            @endforeach
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

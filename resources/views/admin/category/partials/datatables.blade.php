<section>
    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <a href="{{ route('categories.create') }}" class="btn btn-success float-end mb-5">Tạo danh mục</a>
                    <table id="category-table" class="display order-column">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Tên</th>
                                <th>Ảnh</th>
                                <th>Danh mục cha</th>
                                <th>Hiển thị trên thanh điều hướng?</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $key => $category)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $category->name }}</td>
                                    <td>
                                        @if ($category->image)
                                            <img class="w-24 h-24 object-contain mx-auto"
                                                src="{{ asset($category->image) }}" alt="">
                                        @else
                                            <span class="badge badge-Neutral">Lỗi ảnh</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-Neutral">Không có</span>
                                    </td>
                                    <td class="w-1/6">
                                        @if ($category->is_show)
                                            <span class="badge badge-success">Có</span>
                                        @else
                                            <span class="badge badge-danger">Không</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('categories.edit', $category->id) }}"
                                            class="btn btn-sm btn-primary">Sửa</a>
                                        <button class="btn btn-sm btn-error"
                                            onclick="deleteCategory({{ $category->id }})">Xóa</button>
                                    </td>
                                </tr>

                                @if ($category->Children->isNotEmpty())
                                    @foreach ($category->Children as $secondKey => $child)
                                        <tr>
                                            <td>{{ $key }}.{{ $secondKey + 1 }}</td>
                                            <td>{{ $child->name }}</td>
                                            <td>
                                                @if ($child->image)
                                                    <img class="w-24 h-24 object-contain mx-auto"
                                                        src="{{ asset($child->image) }}" alt="">
                                                @else
                                                    <span class="badge badge-Neutral">Lỗi ảnh</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge badge-success">{{ $child->parent->name }}</span>
                                            </td>
                                            <td>
                                                @if ($child->is_show)
                                                    <span class="badge badge-success">Có</span>
                                                @else
                                                    <span class="badge badge-danger">Không</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('categories.edit', $child->id) }}"
                                                    class="btn btn-sm btn-primary">Sửa</a>
                                                <button class="btn btn-sm btn-error"
                                                    onclick="deleteCategory({{ $child->id }})">Xóa</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>STT</th>
                                <th>Tên</th>
                                <th>Ảnh</th>
                                <th>Danh mục cha</th>
                                <th>Hiển thị trên thanh điều hướng?</th>
                                <th>Hành động</th>
                            </tr>
                        </tfoot>
                    </table>

                </div>
            </div>
        </div>
    </div>
</section>

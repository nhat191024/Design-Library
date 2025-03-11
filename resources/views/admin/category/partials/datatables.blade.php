<section>
    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <a href="{{ route('categories.create') }}" class="btn btn-success float-end mb-5">Add Category</a>
                    <table id="category-table" class="display order-column">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Name</th>
                                <th>Image</th>
                                <th>Parent</th>
                                <th>is show on nav</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $key => $category)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $category->name }}</td>
                                    <td>
                                        <img class="w-24 h-24 object-contain mx-auto" src="{{ asset($category->image) }}"
                                            alt="">
                                    </td>
                                    <td>{{ $category->parent ? $category->parent->name : 'None' }}</td>
                                    <td>{{ $category->is_show ? 'Yes' : 'No' }}</td>
                                    <td>
                                        <a href="{{ route('categories.edit', $category->id) }}"
                                            class="btn btn-sm btn-primary">Edit</a>
                                        <button class="btn btn-sm btn-error"
                                            onclick="deleteCategory({{ $category->id }})">Delete</button>
                                    </td>
                                </tr>

                                @if ($category->Children->isNotEmpty())
                                    @foreach ($category->Children as $secondKey => $child)
                                        <tr>
                                            <td>{{ $key }}.{{ $secondKey + 1 }}</td>
                                            <td>{{ $child->name }}</td>
                                            <td>
                                                <img class="w-24 h-24 object-contain mx-auto"
                                                    src="{{ asset($child->image) }}" alt="">
                                            </td>
                                            <td>{{ $child->parent ? $child->parent->name : 'None' }}</td>
                                            <td>{{ $child->is_show ? 'Yes' : 'No' }}</td>
                                            <td>
                                                <a href="{{ route('categories.edit', $child->id) }}"
                                                    class="btn btn-sm btn-primary">Edit</a>
                                                <button class="btn btn-sm btn-error"
                                                    onclick="deleteCategory({{ $child->id }})">Delete</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>STT</th>
                                <th>Name</th>
                                <th>Image</th>
                                <th>Parent</th>
                                <th>is show on nav</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>

                </div>
            </div>
        </div>
    </div>
</section>

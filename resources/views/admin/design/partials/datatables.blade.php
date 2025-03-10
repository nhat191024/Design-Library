<section>
    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <a href="{{ route('designs.create') }}" class="btn btn-success float-end mb-5">Add design</a>
                    <table id="design-table" class="display order-column">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Main Image</th>
                                <th>Category</th>
                                <th>Tag</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($designs as $key => $design)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $design->name }}</td>
                                    <td>{{ $design->description }}</td>
                                    <td>
                                        @if ($design->images && $design->images->count() > 0)
                                            <img class="w-24 h-24 object-contain mx-auto"
                                                src="{{ asset($design->MainImage->url) }}" alt="">
                                        @else
                                            <p>Image error</p>
                                        @endif
                                    </td>
                                    <td>{{ $design->category->name }}</td>
                                    <td>
                                        @foreach ($design->tags as $tag)
                                            <span class="badge badge-info">
                                                {{ $tag->name }}
                                            </span>
                                        @endforeach
                                    </td>
                                    <td>
                                        <a href="{{ route('designs.edit', $design->id) }}"
                                            class="btn btn-sm btn-primary">Edit</a>
                                        <button class="btn btn-sm btn-error"
                                            onclick="deleteDesign({{ $design->id }})">Delete</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>STT</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Main Image</th>
                                <th>Category</th>
                                <th>Tag</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>

                </div>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <a href="{{ route('tags.create') }}" class="btn btn-success float-end mb-5">Add Tag</a>
                    <table id="tag-table" class="display order-column">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tags as $key => $tag)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $tag->name }}</td>
                                    <td>
                                        <a href="{{ route('tags.edit', $tag->id) }}"
                                            class="btn btn-sm btn-primary">Edit</a>
                                        <button class="btn btn-sm btn-error"
                                            onclick="deleteTag({{ $tag->id }})">Delete</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>STT</th>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>

                </div>
            </div>
        </div>
    </div>
</section>

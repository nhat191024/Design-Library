<section>
    <button class="btn btn-success float-end mb-5">Add design</button>
    <table id="design-table" class="display order-column">
        <thead>
            <tr>
                <th>STT</th>
                <th>Name</th>
                <th>Description</th>
                <th>First Image</th>
                <th>Category</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($designs as $key => $design)
                <tr>
                    <td>{{ ++$key }}</td>
                    <td>{{ $design['name'] }}</td>
                    <td>{{ $design['description'] }}</td>
                    <td>
                        <img class="w-24 h-24 object-cover mx-auto"
                            src="https://www.shutterstock.com/editorial/image-editorial/M6z1QcweO7DdU3w4Mjg4Mg==/cat-that-looks-like-hitler-window-dublin-440nw-3387833a.jpg"
                            alt="">
                    </td>
                    <td>{{ $design['category_id'] }}</td>
                    <td>
                        <button class="btn btn-sm btn-primary" onclick="my_modal_5.showModal()">Edit</button>
                        <a href="" class="btn btn-sm btn-error">Delete</a>
                        <a href="" class="btn btn-sm btn-info">All Img</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th>STT</th>
                <th>Name</th>
                <th>Description</th>
                <th>First Image</th>
                <th>Category</th>
                <th>Action</th>
            </tr>
        </tfoot>
    </table>
</section>

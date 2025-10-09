<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;


class CategoryController extends Controller
{
    /**
     * Display the design index page.
     */
    public function index()
    {
        // Chỉ return view, DataTable sẽ load data qua AJAX
        return view('admin.category.index');
    }

    /**
     * Get categories data for DataTable with server-side processing
     */
    public function getCategoriesData(Request $request)
    {
        if ($request->ajax()) {
            // Load all categories with parent relationship để hiển thị hierarchy
            $query = Category::with('parent', 'Children')->select('categories.*');

            // Get total count trước khi filter
            $totalRecords = Category::count();

            // Search functionality
            if ($search = $request->get('search')['value']) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
                // Chỉ count lại khi có search/filter
                $filteredRecords = $query->count();
            } else {
                // Không có filter thì filtered = total (tránh duplicate query)
                $filteredRecords = $totalRecords;
            }

            // Order
            if ($request->has('order')) {
                $orderColumnIndex = $request->get('order')[0]['column'];
                $orderDirection = $request->get('order')[0]['dir'];
                $columns = ['id', 'name', 'image', 'parent_id', 'is_show'];

                if (isset($columns[$orderColumnIndex])) {
                    $query->orderBy($columns[$orderColumnIndex], $orderDirection);
                }
            } else {
                // Sắp xếp theo parent_id NULL trước (parent categories), rồi đến children
                $query->orderByRaw('parent_id IS NULL DESC')->orderBy('parent_id', 'asc')->orderBy('id', 'asc');
            }

            // Pagination
            $start = $request->get('start', 0);
            $length = $request->get('length', 10);
            $categories = $query->skip($start)->take($length)->get();

            // Format data for DataTable
            $data = [];
            $counter = $start;

            foreach ($categories as $category) {
                $counter++;

                // Xử lý ảnh
                $image = '';
                if ($category->image) {
                    $image = '<img class="w-24 h-24 object-contain mx-auto" src="' . asset($category->image) . '" alt="">';
                } else {
                    $image = '<span class="badge badge-neutral">Lỗi ảnh</span>';
                }

                // Xử lý parent category
                $parentName = '<span class="badge badge-neutral">Không có</span>';
                if ($category->parent_id && $category->parent) {
                    $parentName = '<span class="badge badge-success">' . $category->parent->name . '</span>';
                }

                // Xử lý is_show
                $isShow = $category->is_show
                    ? '<span class="badge badge-success">Có</span>'
                    : '<span class="badge badge-danger">Không</span>';

                // Actions
                $actions = '
                    <a href="' . route('categories.edit', $category->id) . '" class="btn btn-sm btn-primary">Sửa</a>
                    <button class="btn btn-sm btn-error" onclick="deleteCategory(' . $category->id . ')">Xóa</button>
                ';

                $data[] = [
                    'DT_RowId' => 'row_' . $category->id,
                    $counter,
                    $category->name,
                    $image,
                    $parentName,
                    $isShow,
                    $actions
                ];
            }

            return response()->json([
                'draw' => intval($request->get('draw')),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $filteredRecords,
                'data' => $data
            ]);
        }

        return response()->json(['error' => 'Invalid request'], 400);
    }

    public function showEditForm($id)
    {
        $category = Category::find($id);
        $categories = Category::whereNull('parent_id')->get();
        return view('admin.category.index', compact('category', 'categories'));
    }

    public function update(UpdateCategoryRequest $request, $id)
    {
        try {
            if ($id == $request->parent_id) {
                return redirect()->back()
                    ->with('error', 'Lỗi khi cập nhật danh mục: Danh mục cha không thể là chính nó');
            }

            if (Category::where('parent_id', $id)->exists() && $request->parent_id != "0") {
                return redirect()->back()
                    ->with('error', 'Lỗi khi cập nhật danh mục: Danh mục này đang chứa danh mục con');
            }

            $category = Category::find($id);

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->extension();
                $image->move(public_path('images/categories'), $imageName);

                $category->update([
                    'name' => $request->name,
                    'parent_id' => $request->parent_id == "0" ? null : $request->parent_id,
                    'is_show' => $request->is_show,
                    'image' => 'images/categories/' . $imageName,
                ]);
            } else {
                $category->update([
                    'name' => $request->name,
                    'parent_id' => $request->parent_id == "0" ? null : $request->parent_id,
                    'is_show' => $request->is_show
                ]);
            }

            $category->save();

            // Clear và refresh cache
            Cache::forget('categories_with_parent');
            Cache::remember('categories_with_parent', 3600, function () {
                return Category::whereNotNull('parent_id')->select('id', 'name')->get();
            });

            return redirect()->route('categories.index')
                ->with('success', 'Cập nhật danh mục thành công');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Lôi khi cập nhập danh mục' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            if (Product::where('category_id', $id)->exists()) {
                return redirect()->back()
                    ->with('error', 'Lỗi khi xóa danh mục: Danh mục này đang chứa sản phẩm');
            }
            $category = Category::find($id);
            $category->delete();

            // Clear và refresh cache
            Cache::forget('categories_with_parent');
            Cache::remember('categories_with_parent', 3600, function () {
                return Category::whereNotNull('parent_id')->select('id', 'name')->get();
            });

            return redirect()->route('categories.index')
                ->with('success', 'Xoá danh mục thành công');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Lỗi khi xóa danh mục' . $e->getMessage());
        }
    }

    public function create()
    {
        $categories = Category::whereNull('parent_id')->get();
        return view('admin.category.index', compact('categories'));
    }

    public function store(StoreCategoryRequest $request)
    {
        try {
            $category = new Category();
            $category->name = $request->name;
            $category->parent_id = $request->parent_id == 0 ? null : $request->parent_id;
            $category->is_show = $request->is_show;

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->extension();
                $image->move(public_path('images/categories'), $imageName);
                $category->image = 'images/categories/' . $imageName;
            }

            $category->save();

            // Clear và refresh cache
            Cache::forget('categories_with_parent');
            Cache::remember('categories_with_parent', 3600, function () {
                return Category::whereNotNull('parent_id')->select('id', 'name')->get();
            });

            return redirect()->route('categories.index')
                ->with('success', 'Tạo danh mục thành công');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Lỗi khi tạo danh mục' . $e->getMessage());
        }
    }
}

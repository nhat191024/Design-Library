<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Http\Request;


class CategoryController extends Controller
{
    /**
     * Display the design index page.
     */
    public function index()
    {
        $categories = Category::whereNull('parent_id')
            ->with('Children')
            ->get();
        return view('admin.category.index', compact('categories'));
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

            if (Category::where('parent_id', $id)->exists()) {
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

            return redirect()->route('categories.index')
                ->with('success', 'Tạo danh mục thành công');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Lỗi khi tạo danh mục' . $e->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

use App\Http\Requests\CategoryRequest;
use Illuminate\Http\Request;


class CategoryController extends Controller
{
    /**
     * Display the design index page.
     */
    public function index()
    {
        $categories = Category::all();
        return view('admin.category.index', compact('categories'));
    }

    public function showEditForm($id)
    {
        $category = Category::find($id);
        return view('admin.category.index', compact('category'));
    }

    public function update(CategoryRequest $request, $id)
    {
        try {
            $category = Category::find($id);

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->extension();
                $image->move(public_path('images/categories'), $imageName);

                $category->update([
                    'name' => $request->name,
                    'is_show' => $request->is_show,
                    'image' => 'images/categories/' . $imageName,
                ]);
            } else {
                $category->update([
                    'name' => $request->name,
                    'is_show' => $request->is_show
                ]);
            }

            $category->save();

            return redirect()->route('categories.index')
                ->with('success', 'Category updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating category: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            if (Product::where('category_id', $id)->exists()) {
                return redirect()->back()
                    ->with('error', 'Error deleting category: Category is in use');
            }
            $category = Category::find($id);
            $category->delete();
            return redirect()->route('categories.index')
                ->with('success', 'Category deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting category: ' . $e->getMessage());
        }
    }

    public function create()
    {
        return view('admin.category.index');
    }

    public function store(CategoryRequest $request)
    {
        try {
            $category = new Category();
            $category->name = $request->name;
            $category->is_show = $request->is_show;

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->extension();
                $image->move(public_path('images/categories'), $imageName);
                $category->image = 'images/categories/' . $imageName;
            }

            $category->save();

            return redirect()->route('categories.index')
                ->with('success', 'Category created successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error creating category: ' . $e->getMessage());
        }
    }
}

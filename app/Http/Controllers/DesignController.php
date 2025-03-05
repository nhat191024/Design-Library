<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Tag;

use Illuminate\Http\Request;

class DesignController extends Controller
{
    /**
     * Display the design index page.
     */
    public function index()
    {
        $designs = Product::all();
        return view('admin.design.index', compact('designs'));
    }

    public function showEditForm($id)
    {
        $design = Product::find($id);
        $categories = Category::all();
        $tags = Tag::all();
        $designTags = $design->tags->pluck('id')->toArray();

        return view('admin.design.index', compact('design', 'categories', 'tags', 'designTags'));
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240',
            'design_id' => 'required|exists:products,id'
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');

            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/designs'), $imageName);

            $design = Product::find($request->design_id);
            $design->images()->create([
                'url' => 'images/designs/' . $imageName
            ]);

            return response()->json([
                'success' => true,
                'image_url' => asset('images/designs/' . $imageName)
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Upload failed'
        ], 400);
    }
}

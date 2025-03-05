<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Image;

use Illuminate\Support\Facades\File;
use App\Http\Requests\StoreDesignRequest;
use App\Http\Requests\UpdateDesignRequest;
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

    public function update(UpdateDesignRequest $request, $id)
    {
        try {
            $design = Product::find($id);
            $design->update([
                'name' => $request->name,
                'description' => $request->description,
                'category_id' => $request->category
            ]);

            $design->save();
            $design->tags()->sync($request->tags);

            return redirect()->route('designs.index')
                ->with('success', 'Design updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating design: ' . $e->getMessage());
        }
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
                'image_url' => asset('images/designs/' . $imageName),
                'image_id' => $design->images->last()->id
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Upload failed'
        ], 400);
    }

    public function deleteImage($imageId)
    {
        $image = Image::findOrFail($imageId);

        if (File::exists(public_path($image->url))) {
            File::delete(public_path($image->url));
        }

        $image->delete();

        return response()->json([
            'success' => true,
            'message' => 'Image deleted successfully'
        ]);
    }

    public function destroy($id)
    {
        try {
            $design = Product::find($id);
            $design->delete();
            return redirect()->route('designs.index')
                ->with('success', 'Design deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting design: ' . $e->getMessage());
        }
    }

    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('admin.design.index', compact('categories', 'tags'));
    }

    public function store(StoreDesignRequest $request)
    {
        try {
            $design = Product::create([
                'name' => $request['name'],
                'description' => $request['description'],
                'category_id' => $request['category']
            ]);

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $imageName = time() . '_' . $image->getClientOriginalName();
                    $image->move(public_path('images/designs'), $imageName);

                    $design->images()->create([
                        'url' => 'images/designs/' . $imageName
                    ]);
                }
            }

            if ($request->has('tags')) {
                $design->tags()->sync($request->tags);
            }

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Design created successfully',
                    'redirect' => route('designs.index')
                ]);
            }

            return redirect()->route('designs.index')
                ->with('success', 'Design created successfully');

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error creating design: ' . $e->getMessage()
                ], 422);
            }

            return redirect()->back()
                ->with('error', 'Error creating design: ' . $e->getMessage())
                ->withInput();
        }
    }
}

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
        $categories = Category::whereNotNull('parent_id')->get();
        $tags = Tag::all();
        $designTags = $design->tags->pluck('id')->toArray();

        return view('admin.design.index', compact('design', 'categories', 'tags', 'designTags'));
    }

    public function update(UpdateDesignRequest $request, $id)
    {
        try {
            $design = Product::find($id);

            if ($request->code != $design->code) {
                $request->validate([
                    'code' => 'max:255|unique:products,code'
                ], [
                    'code.max' => 'Trường mã không được vượt quá 255 ký tự.',
                    'code.unique' => 'Mã đã tồn tại.'
                ]);
            }

            $design->update([
                'name' => $request->name,
                'price' => $request->price,
                'code' => $request->code,
                'description' => $request->description,
                'category_id' => $request->category,
                'main_image' => $request->main_image,
                'is_showcase' => $request->is_showcase
            ]);

            $design->save();
            $design->tags()->sync($request->tags);

            return redirect()->route('designs.index')
                ->with('success', 'Cập nhật thiết kế thành công');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Lỗi khi cập nhật thiết kế: ' . $e->getMessage());
        }
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'design_id' => 'required|exists:products,id'
        ], [
            'image.required' => 'Trường hình ảnh là bắt buộc.',
            'image.image' => 'Tệp tải lên phải là hình ảnh.',
            'image.mimes' => 'Hình ảnh phải có định dạng jpeg, png, jpg, hoặc gif.',
            'image.max' => 'Kích thước hình ảnh không được vượt quá 10MB.',
            'design_id.required' => 'Trường ID thiết kế là bắt buộc.',
            'design_id.exists' => 'ID thiết kế không tồn tại.'
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
                'message' => 'Tải ảnh lên thành công',
                'image_url' => asset('images/designs/' . $imageName),
                'image_name' => $imageName,
                'image_id' => $design->images->last()->id
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Có lỗi xảy ra khi tải ảnh lên'
        ], 400);
    }

    public function deleteImage($imageId)
    {
        $image = Image::findOrFail($imageId);

        if ($image->product->images->count() === 1) {
            return response()->json([
                'success' => false,
                'message' => 'Thiết kế phải có ít nhất 1 hình ảnh'
            ], 200);
        }

        $product = $image->product;
        if ($product->main_image === $image->id) {
            if ($image->id === $product->images->first()->id) {
                $product->main_image = $product->images->last()->id;
            } else {
                $product->main_image = $product->images->first()->id;
            }
            $product->save();
            $image->delete();
            if (File::exists(public_path($image->url))) {
                File::delete(public_path($image->url));
            }
        } else {
            $image->delete();
            if (File::exists(public_path($image->url))) {
                File::delete(public_path($image->url));
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Xoá hình ảnh thành công'
        ]);
    }

    public function destroy($id)
    {
        try {
            $design = Product::find($id);
            $design->delete();
            return redirect()->route('designs.index')
                ->with('success', 'Xoá thiết kế thành công');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Lỗi khi xóa thiết kế:' . $e->getMessage());
        }
    }

    public function create()
    {
        $categories = Category::whereNotNull('parent_id')->get();
        $tags = Tag::all();
        return view('admin.design.index', compact('categories', 'tags'));
    }

    public function store(StoreDesignRequest $request)
    {
        try {
            if (!$request->hasFile('images')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Trường hình ảnh không được để trống.'
                ], 200);
            }

            $design = Product::create([
                'name' => $request['name'],
                'price' => $request['price'],
                'description' => $request['description'],
                'category_id' => $request['category'],
                'is_showcase' => $request['is_showcase']
            ]);

            if ($request['code'] !== null) {
                $design->code = $request->code;
                $design->save();
            } else {
                $design->code = 'SP' . $design->id;
                $design->save();
            }

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $key => $image) {
                    $imageName = time() . '_' . $image->getClientOriginalName();
                    $image->move(public_path('images/designs'), $imageName);

                    $designImage = Image::create([
                        'product_id' => $design->id,
                        'url' => 'images/designs/' . $imageName
                    ]);

                    if ($key == $request['main-image']) {
                        $design->update([
                            'main_image' => $designImage->id
                        ]);
                        $design->save();
                    }
                }
            }

            if ($request->has('tags')) {
                $design->tags()->sync($request->tags);
            }

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Thiết kế được tạo thành công',
                    'redirect' => route('designs.index')
                ]);
            }

            return redirect()->route('designs.index')
                ->with('success', 'Thiết kế được tạo thành công');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lỗi khi tạo thiết kế:' . $e->getMessage()
                ], 422);
            }

            return redirect()->back()
                ->with('error', 'Lỗi khi tạo thiết kế: ' . $e->getMessage())
                ->withInput();
        }
    }
}

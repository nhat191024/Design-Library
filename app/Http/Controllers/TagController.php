<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\TagProduct;

use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display the tag index page.
     */
    public function index()
    {
        $tags = Tag::all();
        return view('admin.tag.index', compact('tags'));
    }

    /**
     * Display the tag edit page.
     */
    public function showEditForm($id)
    {
        $tag = Tag::find($id);
        return view('admin.tag.index', compact('tag'));
    }

    /**
     * Update a tag.
     */
    public function update(UpdateTagRequest $request, $id)
    {
        try {
            $tag = Tag::find($id);
            $tag->update([
                'name' => $request->name,
            ]);
            $tag->save();

            return redirect()->route('tags.index')
                ->with('success', 'Cập nhật nhãn thành công');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Lỗi khi cập nhật nhãn: ' . $e->getMessage());
        }
    }

    /**
     * delete a tag.
     */
    public function destroy($id)
    {
        try {
            $tagProduct = TagProduct::where('tag_id', $id)->first();
            if ($tagProduct) {
                return redirect()->back()
                    ->with('error', 'Lỗi khi xóa nhãn: nhãn này đang được sử dụng');
            }
            $design = Tag::find($id);
            $design->delete();
            return redirect()->route('tags.index')
                ->with('success', 'Xóa nhãn thành công');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Lỗi khi xóa nhãn: ' . $e->getMessage());
        }
    }


    /**
     * Display the tag create page.
     */
    public function create()
    {
        return view('admin.tag.index');
    }

    /**
     * Store a new tag.
     */
    public function store(StoreTagRequest $request)
    {
        try {
            Tag::create([
                'name' => $request->name,
            ]);

            return redirect()->route('tags.index')
                ->with('success', 'Tạo nhãn thành công');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Lỗi khi tạo nhãn: ' . $e->getMessage())
                ->withInput();
        }
    }
}

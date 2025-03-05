<?php

namespace App\Http\Controllers;

use App\Models\Tag;

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
    public function update(StoreTagRequest $request, $id)
    {
        try {
            $tag = Tag::find($id);
            $tag->update([
                'name' => $request->name,
            ]);
            $tag->save();

            return redirect()->route('tags.index')
                ->with('success', 'Tag updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating tag: ' . $e->getMessage());
        }
    }

    /**
     * delete a tag.
     */
    public function destroy($id)
    {
        try {
            $design = Tag::find($id);
            $design->delete();
            return redirect()->route('tags.index')
                ->with('success', 'Tag deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting tag: ' . $e->getMessage());
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
    public function store(UpdateTagRequest $request)
    {
        try {
            Tag::create([
                'name' => $request->name,
            ]);

            return redirect()->route('tags.index')
                ->with('success', 'Tag created successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error creating ag: ' . $e->getMessage())
                ->withInput();
        }
    }
}

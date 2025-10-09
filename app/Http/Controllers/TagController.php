<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\TagProduct;

use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TagController extends Controller
{
    /**
     * Display the tag index page.
     */
    public function index()
    {
        return view('admin.tag.index');
    }

    /**
     * Get tags data for DataTable with server-side processing
     */
    public function getTagsData(Request $request)
    {
        if ($request->ajax()) {
            $query = Tag::select('tags.*');

            $totalRecords = Tag::count();

            // Search functionality
            if ($search = $request->get('search')['value']) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
                $filteredRecords = $query->count();
            } else {
                $filteredRecords = $totalRecords;
            }

            // Order
            if ($request->has('order')) {
                $orderColumnIndex = $request->get('order')[0]['column'];
                $orderDirection = $request->get('order')[0]['dir'];
                $columns = ['id', 'name', 'is_show'];

                if (isset($columns[$orderColumnIndex])) {
                    $query->orderBy($columns[$orderColumnIndex], $orderDirection);
                }
            } else {
                $query->latest();
            }

            // Pagination
            $start = $request->get('start', 0);
            $length = $request->get('length', 10);
            $tags = $query->skip($start)->take($length)->get();

            // Format data for DataTable
            $data = [];
            foreach ($tags as $key => $tag) {
                $isShow = $tag->is_show
                    ? '<span class="badge badge-success">Có</span>'
                    : '<span class="badge badge-danger">Không</span>';

                $actions = '
                    <a href="' . route('tags.edit', $tag->id) . '" class="btn btn-sm btn-primary">Sửa</a>
                    <button class="btn btn-sm btn-error" onclick="deleteTag(' . $tag->id . ')">Xóa</button>
                ';

                $data[] = [
                    'DT_RowId' => 'row_' . $tag->id,
                    $start + $key + 1,
                    $tag->name,
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
                'is_show' => $request->isShow,
            ]);
            $tag->save();

            // Clear và refresh cache
            Cache::forget('all_tags');
            Cache::remember('all_tags', 3600, function () {
                return Tag::select('id', 'name')->get();
            });

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

            // Clear và refresh cache
            Cache::forget('all_tags');
            Cache::remember('all_tags', 3600, function () {
                return Tag::select('id', 'name')->get();
            });

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
                'is_show' => $request->isShow,
            ]);

            // Clear và refresh cache
            Cache::forget('all_tags');
            Cache::remember('all_tags', 3600, function () {
                return Tag::select('id', 'name')->get();
            });

            return redirect()->route('tags.index')
                ->with('success', 'Tạo nhãn thành công');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Lỗi khi tạo nhãn: ' . $e->getMessage())
                ->withInput();
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
    public function backgroundIndex()
    {
        $settings = [
            'bg_zone0_image'   => SiteSetting::get('bg_zone0_image'),
            'bg_zone0_blur'    => SiteSetting::get('bg_zone0_blur', 0),
            'bg_zone0_opacity' => SiteSetting::get('bg_zone0_opacity', 0.5),
            'bg_zone1_image'   => SiteSetting::get('bg_zone1_image'),
            'bg_zone1_blur'    => SiteSetting::get('bg_zone1_blur', 0),
            'bg_zone1_opacity' => SiteSetting::get('bg_zone1_opacity', 0.5),
            'bg_zone2_image'   => SiteSetting::get('bg_zone2_image'),
            'bg_zone2_blur'    => SiteSetting::get('bg_zone2_blur', 0),
            'bg_zone2_opacity' => SiteSetting::get('bg_zone2_opacity', 0.5),
        ];

        return view('admin.settings.background', compact('settings'));
    }

    public function backgroundUpdate(Request $request)
    {
        $request->validate([
            'bg_zone0_blur'    => 'nullable|integer|min:0|max:30',
            'bg_zone0_opacity' => 'nullable|numeric|min:0|max:1',
            'bg_zone1_blur'    => 'nullable|integer|min:0|max:30',
            'bg_zone1_opacity' => 'nullable|numeric|min:0|max:1',
            'bg_zone2_blur'    => 'nullable|integer|min:0|max:30',
            'bg_zone2_opacity' => 'nullable|numeric|min:0|max:1',
        ]);

        SiteSetting::set('bg_zone0_blur',    $request->input('bg_zone0_blur', 0));
        SiteSetting::set('bg_zone0_opacity', $request->input('bg_zone0_opacity', 0.5));
        SiteSetting::set('bg_zone1_blur',    $request->input('bg_zone1_blur', 0));
        SiteSetting::set('bg_zone1_opacity', $request->input('bg_zone1_opacity', 0.5));
        SiteSetting::set('bg_zone2_blur',    $request->input('bg_zone2_blur', 0));
        SiteSetting::set('bg_zone2_opacity', $request->input('bg_zone2_opacity', 0.5));

        return redirect()->back()->with('success', 'Cập nhật cài đặt thành công');
    }

    public function backgroundUpload(Request $request)
    {
        $request->validate([
            'zone'  => 'required|in:0,1,2',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $zone = $request->input('zone');
        $image = $request->file('image');

        $oldPath = SiteSetting::get("bg_zone{$zone}_image");
        if ($oldPath && file_exists(public_path($oldPath))) {
            unlink(public_path($oldPath));
        }

        $imageName = 'bg_zone' . $zone . '_' . time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('images/settings'), $imageName);
        $relativePath = 'images/settings/' . $imageName;

        SiteSetting::set("bg_zone{$zone}_image", $relativePath);

        return response()->json([
            'success'  => true,
            'message'  => 'Tải ảnh nền thành công',
            'image_url' => asset($relativePath),
        ]);
    }

    public function backgroundDelete(Request $request)
    {
        $request->validate([
            'zone'  => 'required|in:0,1,2',
        ]);

        $zone = $request->input('zone');

        $oldPath = SiteSetting::get("bg_zone{$zone}_image");
        if ($oldPath && file_exists(public_path($oldPath))) {
            unlink(public_path($oldPath));
        }

        SiteSetting::set("bg_zone{$zone}_image", null);

        return response()->json([
            'success'  => true,
            'message'  => 'Xóa ảnh nền thành công',
        ]);
    }
}

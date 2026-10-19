<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolSetting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $setting = SchoolSetting::current();
        return view('admin.settings.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'latitude'      => 'required|numeric|between:-90,90',
            'longitude'     => 'required|numeric|between:-180,180',
            'radius_meters' => 'required|integer|min:10|max:10000',
            'jam_masuk'     => 'required',
            'jam_pulang'    => 'required',
        ], [
            'latitude.between'  => 'Latitude harus antara -90 dan 90.',
            'longitude.between' => 'Longitude harus antara -180 dan 180.',
            'radius_meters.min' => 'Radius minimal 10 meter.',
            'radius_meters.max' => 'Radius maksimal 10.000 meter.',
        ]);

        $setting = SchoolSetting::current();
        $setting->update($validated);

        return redirect()->route('admin.settings.index')
            ->with('success', 'Pengaturan lokasi berhasil disimpan.');
    }
}
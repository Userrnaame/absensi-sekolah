<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\LeaveRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class LeaveRequestController extends Controller
{
    public function index(): View
    {
        $requests = LeaveRequest::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('izin.index', compact('requests'));
    }

    public function create(): View
    {
        return view('izin.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'jenis' => 'required|in:izin,sakit,cuti',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'alasan' => 'required|string|max:1000',
            'lampiran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ], [
            'tanggal_mulai.after_or_equal' => 'Tanggal mulai tidak boleh sebelum hari ini.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai.',
            'lampiran.mimes' => 'Lampiran harus berupa JPG, PNG, atau PDF.',
            'lampiran.max' => 'Ukuran lampiran maksimal 2 MB.',
        ]);

        $userId = Auth::id();
        $start = $validated['tanggal_mulai'];
        $end = $validated['tanggal_selesai'];

        $overlap = LeaveRequest::where('user_id', $userId)
            ->active()
            ->overlapping($start, $end)
            ->exists();

        if ($overlap) {
            return back()->withInput()->withErrors([
                'tanggal_mulai' => 'Rentang tanggal bentrok dengan pengajuan pending atau yang sudah disetujui.',
            ]);
        }

        $sudahAbsen = Attendance::where('user_id', $userId)
            ->whereBetween('tanggal', [$start, $end])
            ->whereNotNull('waktu_masuk')
            ->exists();

        if ($sudahAbsen) {
            return back()->withInput()->withErrors([
                'tanggal_mulai' => 'Ada tanggal di rentang ini yang sudah tercatat absen masuk. Pilih tanggal lain.',
            ]);
        }

        $path = null;
        if ($request->hasFile('lampiran')) {
            $path = $request->file('lampiran')->store('izin', 'public');
        }

        LeaveRequest::create([
            'user_id' => $userId,
            'jenis' => $validated['jenis'],
            'tanggal_mulai' => $start,
            'tanggal_selesai' => $end,
            'alasan' => $validated['alasan'],
            'lampiran' => $path,
            'status' => 'pending',
        ]);

        return redirect()->route('izin.index')->with('success', 'Pengajuan berhasil dikirim. Menunggu persetujuan admin.');
    }

    public function destroy(LeaveRequest $leaveRequest): RedirectResponse
    {
        if ($leaveRequest->user_id !== Auth::id()) {
            abort(403, 'Anda tidak punya akses ke pengajuan ini.');
        }

        if ($leaveRequest->status !== 'pending') {
            return back()->with('error', 'Hanya pengajuan pending yang bisa dibatalkan.');
        }

        if ($leaveRequest->lampiran) {
            Storage::disk('public')->delete($leaveRequest->lampiran);
        }

        $leaveRequest->delete();

        return redirect()->route('izin.index')->with('success', 'Pengajuan dibatalkan.');
    }
}

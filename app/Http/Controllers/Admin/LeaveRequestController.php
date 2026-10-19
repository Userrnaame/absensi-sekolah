<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LeaveRequestController extends Controller
{
    public function index(Request $request): View
    {
        $query = LeaveRequest::with('user')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        $requests = $query->paginate(15);

        return view('admin.izin.index', compact('requests'));
    }

    public function show(LeaveRequest $leaveRequest): View
    {
        $leaveRequest->load(['user', 'reviewer']);

        return view('admin.izin.show', ['izin' => $leaveRequest]);
    }

    public function approve(Request $request, LeaveRequest $leaveRequest): RedirectResponse
    {
        if ($leaveRequest->status !== 'pending') {
            return back()->with('error', 'Pengajuan ini sudah diproses.');
        }

        $sudahAbsen = Attendance::where('user_id', $leaveRequest->user_id)
            ->whereBetween('tanggal', [$leaveRequest->tanggal_mulai, $leaveRequest->tanggal_selesai])
            ->whereNotNull('waktu_masuk')
            ->exists();

        if ($sudahAbsen) {
            return back()->with('error', 'Tidak bisa disetujui: ada tanggal di rentang ini yang sudah absen GPS.');
        }

        $request->validate([
            'catatan_admin' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($leaveRequest, $request) {
            $leaveRequest->update([
                'status' => 'disetujui',
                'reviewed_by' => Auth::id(),
                'catatan_admin' => $request->input('catatan_admin'),
            ]);

            foreach ($leaveRequest->dates() as $tanggal) {
                Attendance::updateOrCreate(
                    [
                        'user_id' => $leaveRequest->user_id,
                        'tanggal' => $tanggal,
                    ],
                    [
                        'keterangan' => $leaveRequest->jenis,
                    ]
                );
            }
        });

        return redirect()->route('admin.izin.index')->with('success', 'Pengajuan disetujui.');
    }

    public function reject(Request $request, LeaveRequest $leaveRequest): RedirectResponse
    {
        if ($leaveRequest->status !== 'pending') {
            return back()->with('error', 'Pengajuan ini sudah diproses.');
        }

        $request->validate([
            'catatan_admin' => 'nullable|string|max:500',
        ]);

        $leaveRequest->update([
            'status' => 'ditolak',
            'reviewed_by' => Auth::id(),
            'catatan_admin' => $request->input('catatan_admin'),
        ]);

        return redirect()->route('admin.izin.index')->with('success', 'Pengajuan ditolak.');
    }
}

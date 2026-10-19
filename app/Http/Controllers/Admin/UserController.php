<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Filter by role (opsional)
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Search by nama atau NIS
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('role')->orderBy('nis')->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nis' => 'required|string|unique:users,nis|max:50',
            'role' => 'required|in:siswa,guru,admin',
            'password' => 'required|string|min:6',
            'bio' => 'nullable|string',
        ], [
            'nis.unique' => 'NIS sudah digunakan.',
            'password.min' => 'Password minimal 6 karakter.',
        ]);

        User::create([
            'name' => $validated['name'],
            'nis' => $validated['nis'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
            'bio' => $validated['bio'] ?? null,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nis' => ['required', 'string', 'max:50', Rule::unique('users', 'nis')->ignore($user->id)],
            'role' => 'required|in:siswa,guru,admin',
            'password' => 'nullable|string|min:6',
            'bio' => 'nullable|string',
        ]);

        $data = [
            'name' => $validated['name'],
            'nis' => $validated['nis'],
            'role' => $validated['role'],
            'bio' => $validated['bio'] ?? null,
        ];

        // Kalau password diisi, update. Kalau kosong, biarkan.
        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        // Cegah admin hapus dirinya sendiri
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus.');
    }

    /**
     * Generate NIS otomatis berdasarkan role dan tahun.
     * Format:
     * - Siswa : YYYY + 3 digit urutan     → 2026001, 2026002
     * - Guru  : G + YYYY + 3 digit urutan → G2026001
     * - Admin : A + YYYY + 3 digit urutan → A2026001
     */
    private function generateNis(string $role): string
    {
        $year = date('Y');

        $prefix = match ($role) {
            'siswa' => '',
            'guru'  => 'G',
            'admin' => 'A',
            default => '',
        };

        // Cari NIS terakhir dengan prefix & tahun yang sama
        $lastUser = User::where('nis', 'like', $prefix . $year . '%')
            ->orderBy('nis', 'desc')
            ->first();

        if ($lastUser) {
            // Ambil 3 digit terakhir, tambah 1
            $lastNumber = (int) substr($lastUser->nis, -3);
            $newNumber  = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . $year . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Endpoint AJAX untuk generate NIS (dipakai di form create).
     */
    public function generateNisAjax(Request $request)
    {
        $role = $request->input('role', 'siswa');

        return response()->json([
            'nis' => $this->generateNis($role),
        ]);
    }
}
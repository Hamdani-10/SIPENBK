<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\GuruBk;
use App\Models\Siswa;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $sort = $request->input('sort', 'asc');
        if (!in_array(strtolower($sort), ['asc', 'desc'])) {
            $sort = 'asc'; // default fallback
        }

        $query->orderBy('name', $sort);

        $users = $query->paginate(10);
        return view('content.admin.users.users', compact('users'));
    }

    public function createAdmin()
    {
        return view('content.admin.users.create-users-admin', ['role' => 'admin']);
    }

    public function createGuru()
    {
        return view('content.admin.users.create-users-gurubk', ['role' => 'guru']);
    }

    public function createSiswa()
    {
        return view('content.admin.users.create-users-siswa', ['role' => 'siswa']);
    }

    public function store(Request $request)
    {
        $role = $request->input('role');

        // Validasi umum
        $rules = [
            'name' => 'required|string|max:30',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required',
            'role' => 'required|in:admin,guru,siswa',
        ];

        // Tambahan validasi khusus guru dan siswa
        if ($role === 'guru') {
            $rules = array_merge($rules, [
                'no_whatsapp_guru_bk' => 'required|string|max:20',
                'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);
        } elseif ($role === 'siswa') {
            $rules = array_merge($rules, [
                'nisn' => 'required|numeric|digits_between:8,10',
                'kelas' => 'required|string|max:10',
                'no_whatsapp_siswa' => 'required|string|max:20',
                'nama_ortu' => 'required|string|max:50',
                'no_whatsapp_ortu' => 'required|string|max:20',
                'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);
        }

        // Pesan error kustom
        $messages = [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password_confirmation.required' => 'Konfirmasi password wajib diisi.',
            'role.required' => 'Peran pengguna wajib dipilih.',
            'role.in' => 'Peran pengguna tidak valid.',

            // Guru
            'no_whatsapp_guru_bk.required' => 'Nomor WhatsApp Guru BK wajib diisi.',
            'foto.image' => 'Foto harus berupa gambar (jpg, jpeg, png).',
            'foto.max' => 'Ukuran foto maksimal 2MB.',

            // Siswa
            'nisn.required' => 'NISN wajib diisi.',
            'nisn.numeric' => 'NISN harus berupa angka.',
            'nisn.digits_between' => 'NISN harus antara 8 sampai 10 digit.',
            'kelas.required' => 'Kelas wajib diisi.',
            'no_whatsapp_siswa.required' => 'Nomor WhatsApp siswa wajib diisi.',
            'nama_ortu.required' => 'Nama orang tua wajib diisi.',
            'no_whatsapp_ortu.required' => 'Nomor WhatsApp orang tua wajib diisi.',
        ];

        $request->validate($rules, $messages);

        try {
            $fotoPath = null;
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('foto_users', 'public');
            }

            // Simpan data user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $role,
            ]);

            // Tambahan data guru
            if ($role === 'guru') {
                GuruBk::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'nama_guru_bk' => $request->name,
                        'jabatan' => 'Guru BK',
                        'no_whatsapp_guru_bk' => $request->no_whatsapp_guru_bk,
                        'foto' => $fotoPath,
                    ]
                );
            }

            // Tambahan data siswa
            if ($role === 'siswa') {
                Siswa::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'nama_siswa' => $request->name,
                        'nisn' => $request->nisn,
                        'kelas' => $request->kelas,
                        'no_whatsapp_siswa' => $request->no_whatsapp_siswa,
                        'nama_ortu' => $request->nama_ortu,
                        'no_whatsapp_ortu' => $request->no_whatsapp_ortu,
                        'foto' => $fotoPath,
                    ]
                );
            }

            return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('content.admin.users.edit-users', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|in:admin,guru,siswa',
        ]);

        $user->update([
            'name' => $request->name,
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus!');
    }
}

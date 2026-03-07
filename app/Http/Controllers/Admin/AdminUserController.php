<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\MataLomba;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $admins = User::where('role', 'admin')->orderBy('name')->get();
        $juris = User::where('role', 'juri')->with('mataLombas')->orderBy('name')->get();
        $regus = User::where('role', 'regu')->orderBy('name')->get();
        $allMataLomba = MataLomba::orderBy('nama')->get();
        return view('admin.users.index', compact('admins', 'juris', 'regus', 'allMataLomba'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => ['required', Rule::in([User::ROLE_ADMIN, User::ROLE_JURI, User::ROLE_REGU])],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $user->load('mataLombas');
        $allMataLomba = MataLomba::orderBy('nama')->get();
        return view('admin.users.edit', compact('user', 'allMataLomba'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8',
            'role' => ['required', Rule::in([User::ROLE_ADMIN, User::ROLE_JURI, User::ROLE_REGU])],
        ];

        if ($request->input('role') === User::ROLE_JURI && $request->filled('kode_mata_lomba')) {
            $rules['kode_mata_lomba'] = 'string|size:6|alpha_num';
        }

        $validated = $request->validate($rules);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Handle mata_lomba for juri (many-to-many pivot)
        if ($validated['role'] === User::ROLE_JURI) {
            $mataLombaIds = $request->input('mata_lomba_ids', []);
            $user->mataLombas()->sync($mataLombaIds);
        } else {
            $user->mataLombas()->detach();
            $validated['mata_lomba_id'] = null;
        }

        unset($validated['kode_mata_lomba']);
        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }

    /**
     * Update mata lomba assignments for a juri user (inline from index page).
     */
    public function updateMataLomba(Request $request, User $user)
    {
        if ($user->role !== User::ROLE_JURI) {
            return back()->with('error', 'User bukan juri.');
        }

        $mataLombaIds = $request->input('mata_lomba_ids', []);
        $user->mataLombas()->sync($mataLombaIds);

        return back()->with('success', 'Mata lomba juri "' . $user->name . '" berhasil diperbarui.');
    }
}

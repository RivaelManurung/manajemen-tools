<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\JobTitle;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        // Memuat relasi untuk efisiensi query
        $users = User::with(['jobTitle', 'department'])->latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        // PERBAIKAN: Mengambil data job titles dan departments untuk dropdown
        $jobTitles = JobTitle::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
        return view('admin.users.create', compact('jobTitles', 'departments'));
    }

    public function store(Request $request)
    {
        // PERBAIKAN: Validasi disesuaikan dengan skema baru
        $request->validate([
            'fullname' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'job_title_id' => 'required|exists:job_titles,id',
            'department_id' => 'required|exists:departments,id',
            'peran' => ['required', Rule::in(['admin', 'user'])],
        ]);

        User::create([
            'fullname' => $request->fullname,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'job_title_id' => $request->job_title_id,
            'department_id' => $request->department_id,
            'peran' => $request->peran,
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $jobTitles = JobTitle::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
        return view('admin.users.edit', compact('user', 'jobTitles', 'departments'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'fullname' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'job_title_id' => 'required|exists:job_titles,id',
            'department_id' => 'required|exists:departments,id',
            'peran' => ['required', Rule::in(['admin', 'user'])],
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = $request->except('password');
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Data user berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}
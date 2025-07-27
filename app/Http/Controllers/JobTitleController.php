<?php

namespace App\Http\Controllers;

use App\Models\JobTitle;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;

class JobTitleController extends Controller
{
    public function index(): View
    {
        // PENYEMPURNAAN: Menambahkan hitungan pengguna untuk setiap job title
        $jobTitles = JobTitle::withCount('users')->latest()->paginate(10);
        return view('admin.job-titles.index', compact('jobTitles'));
    }

    public function create(): View
    {
        return view('admin.job-titles.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate(['name' => 'required|string|max:255|unique:job_titles']);
        JobTitle::create($request->all());
        return redirect()->route('admin.job-titles.index')->with('success', 'Job Title berhasil dibuat.');
    }

    public function edit(JobTitle $jobTitle): View
    {
        return view('admin.job-titles.edit', compact('jobTitle'));
    }

    public function update(Request $request, JobTitle $jobTitle): RedirectResponse
    {
        $request->validate(['name' => ['required', 'string', 'max:255', Rule::unique('job_titles')->ignore($jobTitle->id)]]);
        $jobTitle->update($request->all());
        return redirect()->route('admin.job-titles.index')->with('success', 'Job Title berhasil diperbarui.');
    }

    public function destroy(JobTitle $jobTitle): RedirectResponse
    {
        if ($jobTitle->users()->count() > 0) {
            return back()->with('error', 'Job Title tidak bisa dihapus karena masih digunakan oleh user.');
        }
        $jobTitle->delete();
        return redirect()->route('admin.job-titles.index')->with('success', 'Job Title berhasil dihapus.');
    }
}

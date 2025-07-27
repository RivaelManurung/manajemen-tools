<?php

namespace App\Http\Controllers;

use App\Models\JobTitle;
use Illuminate\Http\Request;

class JobTitleController extends Controller
{
    public function index()
    {
        $jobTitles = JobTitle::latest()->paginate(10);
        return view('job-titles.index', compact('jobTitles'));
    }

    public function create()
    {
        return view('job-titles.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:job_titles|max:255']);
        JobTitle::create($request->all());
        return redirect()->route('job-titles.index')->with('success', 'Job Title berhasil dibuat.');
    }

    public function edit(JobTitle $jobTitle)
    {
        return view('job-titles.edit', compact('jobTitle'));
    }

    public function update(Request $request, JobTitle $jobTitle)
    {
        $request->validate(['name' => 'required|unique:job_titles,name,' . $jobTitle->id . '|max:255']);
        $jobTitle->update($request->all());
        return redirect()->route('job-titles.index')->with('success', 'Job Title berhasil diperbarui.');
    }

    public function destroy(JobTitle $jobTitle)
    {
        // Tambahkan validasi jika job title masih digunakan oleh user
        if ($jobTitle->users()->count() > 0) {
            return back()->with('error', 'Job Title tidak bisa dihapus karena masih digunakan oleh user.');
        }
        $jobTitle->delete();
        return redirect()->route('job-titles.index')->with('success', 'Job Title berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        $projects = Project::all();
        return view('admin.projects.index', compact('projects', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        return view('admin.projects.create', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        Project::create($request->all());

        return redirect()->route('projects.index', compact('user'))->with('success', 'Project created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = Auth::user();
        $project = Project::findOrFail($id);
        return view('admin.projects.show', compact('project', 'user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        $user = Auth::user();
        return view('admin.projects.edit', compact('project', 'user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $project->update($request->all());

        return redirect()->route('projects.index', compact('user'))
            ->with('success', 'Project updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $user = Auth::user();

        $project->delete();
        return redirect()->route('projects.index', compact('user'))
            ->with('success', 'Project deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = User::withTrashed()
            ->with('role')
            ->where('id', Auth::id())
            ->first();

        // Base query with creator info
        $projectsQuery = Project::leftJoin('users as creators', 'projects.created_by', '=', 'creators.id')
            ->select('projects.*', 'creators.name as creator_name', 'creators.role_id as creator_role_id');

        // Apply filter if creator_role is selected
        if ($request->filled('creator_role')) {
            $projectsQuery->where('creators.role_id', $request->creator_role);
        }

        // Filter by specific creator (user id)
        if ($request->filled('created_by')) {
            $projectsQuery->where('projects.created_by', $request->created_by);
        }

        $projects = $projectsQuery->orderBy('projects.created_at', 'desc')->get();

        // Get list of creators (unique users who created projects)
        $creators = User::whereIn('id', Project::pluck('created_by')->unique())
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return view('admin.projects.index', compact('projects', 'user', 'request', 'creators'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = User::withTrashed()
            ->with('role')
            ->where('id', Auth::id())
            ->first();

        return view('admin.projects.create', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
            ]);
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        }

        Project::create([
            'name' => $request->name,
            'description' => $request->description,
            'created_by' => $user->id,
        ]);

        return redirect()->route('projects.index')
            ->with('success', 'Project created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        $user = User::withTrashed()
            ->with('role')
            ->where('id', Auth::id())
            ->first();

        // Get creator info
        $creator = DB::table('users')
            ->select('name', 'role_id')
            ->where('id', $project->created_by)
            ->first();

        return view('admin.projects.show', compact('project', 'user', 'creator'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        $user = User::withTrashed()
            ->with('role')
            ->where('id', Auth::id())
            ->first();
        return view('admin.projects.edit', compact('project', 'user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $user = Auth::user();

        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
            ]);
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        }

        $project->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

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

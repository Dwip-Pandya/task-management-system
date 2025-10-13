<?php

namespace App\Http\Controllers\ProjectManager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class ProjectManagerProjectController extends Controller
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

        $projects = $projectsQuery->orderBy('projects.created_at', 'desc')->get();

        return view('projectmanager.projects.index', compact('projects', 'user', 'request'));
    }

    /**
     * Show the form for creating a new project.
     */
    public function create()
    {
        $user = Auth::user();
        return view('projectmanager.projects.create', compact('user'));
    }

    /**
     * Store a newly created project.
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

        return redirect()->route('projectmanager.projects.index')
            ->with('success', 'Project created successfully.');
    }

    /**
     * Display the specified project.
     */
    public function show(Project $project)
    {
        $user = Auth::user();

        // Ensure the manager can only view their own projects
        // if ($project->created_by !== $user->id) {
        //     abort(403);
        // }

        $creator = DB::table('users')
            ->select('name', 'role_id')
            ->where('id', $project->created_by)
            ->first();

        return view('projectmanager.projects.show', compact('project', 'user', 'creator'));
    }

    /**
     * Show the form for editing the specified project.
     */
    public function edit(Project $project)
    {
        $user = Auth::user();

        // if ($project->created_by !== $user->id) {
        //     abort(403);
        // }

        return view('projectmanager.projects.edit', compact('project', 'user'));
    }

    /**
     * Update the specified project.
     */
    public function update(Request $request, Project $project)
    {
        $user = Auth::user();

        // if ($project->created_by !== $user->id) {
        //     abort(403);
        // }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $project->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('projectmanager.projects.index')
            ->with('success', 'Project updated successfully.');
    }

    /**
     * **No delete** for project manager (disabled)
     */
}

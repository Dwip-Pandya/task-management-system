<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Services\NotificationService;
use Mockery\Matcher\Not;

class ProjectController extends Controller
{
    /**
     * Utility: Check if the logged-in user has permission for the Project module.
     */
    private function hasPermission($action)
    {
        $user = User::withTrashed()
            ->with('role')
            ->where('id', Auth::id())
            ->first();

        if (!$user || !$user->role_id) {
            return false;
        }

        $permission = DB::table('role_permissions')
            ->where('role_id', $user->role_id)
            ->where('module_name', 'project management')
            ->first();

        if (!$permission) {
            return false;
        }

        $field = 'can_' . $action;

        if (!property_exists($permission, $field)) {
            return true;
        }

        return $permission->$field == 1;
    }

    /**
     * Utility: Fetch all permissions for current user.
     */
    private function getAllPermissions()
    {
        $user = Auth::user();

        if (!$user || !$user->role_id) {
            return [
                'can_view' => false,
                'can_add' => false,
                'can_edit' => false,
                'can_delete' => false,
            ];
        }

        $perm = DB::table('role_permissions')
            ->where('role_id', $user->role_id)
            ->where('module_name', 'project management')
            ->first();

        return [
            'can_view' => $perm->can_view ?? false,
            'can_add' => $perm->can_add ?? false,
            'can_edit' => $perm->can_edit ?? false,
            'can_delete' => $perm->can_delete ?? false,
        ];
    }

    public function index(Request $request)
    {
        $user = User::withTrashed()
            ->with('role')
            ->where('id', Auth::id())
            ->first();

        // Base query with creator info
        $projectsQuery = Project::leftJoin('users as creators', 'projects.created_by', '=', 'creators.id')
            ->select('projects.*', 'creators.name as creator_name', 'creators.role_id as creator_role_id');

        if ($request->filled('creator_role')) {
            $projectsQuery->where('creators.role_id', $request->creator_role);
        }

        if ($request->filled('created_by')) {
            $projectsQuery->where('projects.created_by', $request->created_by);
        }

        $projects = $projectsQuery->orderBy('projects.created_at', 'desc')->get();

        $creators = User::whereIn('id', Project::pluck('created_by')->unique())
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return view('admin.projects.index', compact('projects', 'user', 'request', 'creators'));
    }

    public function create()
    {
        $user = User::withTrashed()
            ->with('role')
            ->where('id', Auth::id())
            ->first();

        $permissions = $this->getAllPermissions();

        if (!$permissions['can_add']) {
            return redirect()->route('projects.index')
                ->with('error', 'You do not have permission to create a project.');
        }

        return view('admin.projects.create', compact('user', 'permissions'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$this->hasPermission('add')) {
            return redirect()->route('projects.index')
                ->with('error', 'You do not have permission to add a project.');
        }

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

    public function show(Project $project, Request $request)
    {
        $user = User::withTrashed()
            ->with('role')
            ->where('id', Auth::id())
            ->first();

        $permissions = $this->getAllPermissions();

        if (!$permissions['can_view']) {
            return redirect()->route('projects.index')
                ->with('error', 'You do not have permission to view this project.');
        }

        $creator = DB::table('users')
            ->select('name', 'role_id')
            ->where('id', $project->created_by)
            ->first();

        return view('admin.projects.show', compact('project', 'user', 'creator', 'permissions'));
    }

    public function edit(Project $project)
    {
        $user = User::withTrashed()
            ->with('role')
            ->where('id', Auth::id())
            ->first();

        $permissions = $this->getAllPermissions();

        if (!$permissions['can_edit']) {
            return redirect()->route('projects.index')
                ->with('error', 'You do not have permission to edit a project.');
        }

        return view('admin.projects.edit', compact('project', 'user', 'permissions'));
    }

    public function update(Request $request, Project $project)
    {
        $user = Auth::user();

        if (!$this->hasPermission('edit')) {
            return redirect()->route('projects.index')
                ->with('error', 'You do not have permission to update a project.');
        }

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

        NotificationService::dataUpdated($project, 'project');

        return redirect()->route('projects.index', compact('user'))
            ->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        $user = Auth::user();

        if (!$this->hasPermission('delete')) {
            return redirect()->route('projects.index')
                ->with('error', 'You do not have permission to delete a project.');
        }

        $project->delete();
        return redirect()->route('projects.index', compact('user'))
            ->with('success', 'Project deleted successfully.');
    }
}

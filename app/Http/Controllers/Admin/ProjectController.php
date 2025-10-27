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
        if (!$user) return false;

        $permission = DB::table('role_permissions')
            ->where('user_id', $user->id)
            ->where('module_name', 'project management')
            ->first();

        if (!$permission) {
            $permission = DB::table('role_permissions')
                ->where('role_id', $user->role_id)
                ->whereNull('user_id')
                ->where('module_name', 'project management')
                ->first();
        }

        if (!$permission) return false;

        $field = 'can_' . $action;
        return $permission->$field == 1;
    }

    /**
     * Utility: Fetch all permissions for current user.
     */
    private function getAllPermissions()
    {
        $user = Auth::user();
        if (!$user) {
            return [
                'can_view' => false,
                'can_add' => false,
                'can_edit' => false,
                'can_delete' => false,
            ];
        }

        $perm = DB::table('role_permissions')
            ->where('user_id', $user->id)
            ->where('module_name', 'project management')
            ->first();

        // Fallback to role defaults
        if (!$perm) {
            $perm = DB::table('role_permissions')
                ->where('role_id', $user->role_id)
                ->whereNull('user_id')
                ->where('module_name', 'project management')
                ->first();
        }

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
            return response()->view('errors.permission-denied', [], 403);
        }

        return view('admin.projects.create', compact('user', 'permissions'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$this->hasPermission('add')) {
            return response()->view('errors.permission-denied', [], 403);
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
            return response()->view('errors.permission-denied', [], 403);
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
            return response()->view('errors.permission-denied', [], 403);
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
            return response()->view('errors.permission-denied', [], 403);
        }

        $project->delete();
        return redirect()->route('projects.index', compact('user'))
            ->with('success', 'Project deleted successfully.');
    }
}

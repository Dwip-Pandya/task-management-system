<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as Trail;


/* ADMIN  */

// Dashboard
Breadcrumbs::for(
    'admin.dashboard',
    fn(Trail $trail) =>
    $trail->push('Dashboard', route('admin.dashboard'))
);

// Users
Breadcrumbs::for(
    'users.index',
    fn(Trail $trail) =>
    $trail->parent('admin.dashboard')->push('Users', route('users.index'))
);
Breadcrumbs::for(
    'users.create',
    fn(Trail $trail) =>
    $trail->parent('users.index')->push('Create User', route('users.create'))
);
Breadcrumbs::for(
    'users.edit',
    fn(Trail $trail) =>
    $trail->parent('users.index')->push('Edit User')
);
Breadcrumbs::for(
    'users.show',
    fn(Trail $trail) =>
    $trail->parent('users.index')->push('View User')
);

// Tasks
Breadcrumbs::for(
    'tasks.index',
    fn(Trail $trail) =>
    $trail->parent('admin.dashboard')->push('Tasks', route('tasks.index'))
);
Breadcrumbs::for(
    'tasks.create',
    fn(Trail $trail) =>
    $trail->parent('tasks.index')->push('Create Task', route('tasks.create'))
);
Breadcrumbs::for(
    'tasks.edit',
    fn(Trail $trail) =>
    $trail->parent('tasks.index')->push('Edit Task')
);
Breadcrumbs::for(
    'tasks.show',
    fn(Trail $trail) =>
    $trail->parent('tasks.index')->push('View Task')
);

// Projects
Breadcrumbs::for(
    'projects.index',
    fn(Trail $trail) =>
    $trail->parent('admin.dashboard')->push('Projects', route('projects.index'))
);
Breadcrumbs::for(
    'projects.create',
    fn(Trail $trail) =>
    $trail->parent('projects.index')->push('Create Project', route('projects.create'))
);
Breadcrumbs::for(
    'projects.edit',
    fn(Trail $trail) =>
    $trail->parent('projects.index')->push('Edit Project')
);
Breadcrumbs::for(
    'projects.show',
    fn(Trail $trail) =>
    $trail->parent('projects.index')->push('View Project')
);

// Comments
Breadcrumbs::for(
    'admin.comments.index',
    fn(Trail $trail) =>
    $trail->parent('admin.dashboard')->push('Comments', route('admin.comments.index'))
);

// Reports
Breadcrumbs::for(
    'admin.reports.index',
    fn(Trail $trail) =>
    $trail->parent('admin.dashboard')->push('Reports', route('admin.reports.index'))
);

/* USER */

Breadcrumbs::for(
    'user.dashboard',
    fn(Trail $trail) =>
    $trail->push('Dashboard', route('user.dashboard'))
);

Breadcrumbs::for(
    'user.tasks.index',
    fn(Trail $trail) =>
    $trail->parent('user.dashboard')->push('My Tasks', route('user.tasks.index'))
);
Breadcrumbs::for(
    'user.tasks.show',
    fn(Trail $trail) =>
    $trail->parent('user.tasks.index')->push('View Task')
);
Breadcrumbs::for(
    'user.tasks.edit',
    fn(Trail $trail) =>
    $trail->parent('user.tasks.index')->push('Edit Task')
);

Breadcrumbs::for(
    'user.calendar',
    fn(Trail $trail) =>
    $trail->parent('user.dashboard')->push('Calendar', route('user.calendar'))
);

/* PROJECT MANAGER */

Breadcrumbs::for(
    'projectmanager.dashboard',
    fn(Trail $trail) =>
    $trail->push('Dashboard', route('projectmanager.dashboard'))
);

// Projects
Breadcrumbs::for(
    'projectmanager.projects.index',
    fn(Trail $trail) =>
    $trail->parent('projectmanager.dashboard')->push('Projects', route('projectmanager.projects.index'))
);
Breadcrumbs::for(
    'projectmanager.projects.create',
    fn(Trail $trail) =>
    $trail->parent('projectmanager.projects.index')->push('Create Project', route('projectmanager.projects.create'))
);
Breadcrumbs::for(
    'projectmanager.projects.show',
    fn(Trail $trail) =>
    $trail->parent('projectmanager.projects.index')->push('View Project')
);
Breadcrumbs::for(
    'projectmanager.projects.edit',
    fn(Trail $trail) =>
    $trail->parent('projectmanager.projects.index')->push('Edit Project')
);

// Tasks
Breadcrumbs::for(
    'projectmanager.tasks.index',
    fn(Trail $trail) =>
    $trail->parent('projectmanager.dashboard')->push('Tasks', route('projectmanager.tasks.index'))
);
Breadcrumbs::for(
    'projectmanager.tasks.create',
    fn(Trail $trail) =>
    $trail->parent('projectmanager.tasks.index')->push('Create Task', route('projectmanager.tasks.create'))
);
Breadcrumbs::for(
    'projectmanager.tasks.show',
    fn(Trail $trail) =>
    $trail->parent('projectmanager.tasks.index')->push('View Task')
);
Breadcrumbs::for(
    'projectmanager.tasks.edit',
    fn(Trail $trail) =>
    $trail->parent('projectmanager.tasks.index')->push('Edit Task')
);

Breadcrumbs::for(
    'projectmanager.calendar',
    fn(Trail $trail) =>
    $trail->parent('projectmanager.dashboard')->push('Calendar', route('projectmanager.calendar'))
);

/* PROJECT MEMBER */

Breadcrumbs::for(
    'projectmember.dashboard',
    fn(Trail $trail) =>
    $trail->push('Dashboard', route('projectmember.dashboard'))
);

Breadcrumbs::for(
    'projectmember.tasks.index',
    fn(Trail $trail) =>
    $trail->parent('projectmember.dashboard')->push('Tasks', route('projectmember.tasks.index'))
);
Breadcrumbs::for(
    'projectmember.tasks.show',
    fn(Trail $trail) =>
    $trail->parent('projectmember.tasks.index')->push('View Task')
);

Breadcrumbs::for(
    'projectmember.calendar',
    fn(Trail $trail) =>
    $trail->parent('projectmember.dashboard')->push('Calendar', route('projectmember.calendar'))
);

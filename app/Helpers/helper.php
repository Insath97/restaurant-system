<?php

function setSidebarActive(array $routes): ?string
{
    foreach ($routes as $route) {
        if (request()->routeIs($route)) {
            return 'active';
        }
    }

    return '';
}

/* check role permission */
function hasPermission(array $permission)
{
    return auth()->guard('admin')->user()->hasAnyPermission($permission);
}

/* check super admin */
function isSuperAdmin()
{
    return auth()->guard('admin')->user()->hasRole('Super Admin');
}

function canAccess(array $permission)
{
    $permission = auth()->guard('admin')->user()->hasAnyPermission($permission);
    $super_admin = auth()->guard('admin')->user()->hasRole('Super Admin');

    if ($permission || $super_admin) {
        return true;
    } else {
        return false;
    }
}

/* get user role name */
function getRole()
{
    $role = auth()->guard('admin')->user()->getRoleNames();
    return $role->first();
}

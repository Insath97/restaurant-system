<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FoodController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\TableController;
use App\Http\Controllers\Admin\UserRoleController;
use Illuminate\Support\Facades\Route;

/* route for without authentication */

Route::group(["prefix" => "admin", "as" => "admin."], function () {

    /* login */
    Route::get("login", [AuthController::class, "showLoginForm"])->name("login");
    Route::post("/login", [AuthController::class, "login"])->name("login.post");

    /* forgot password */
    Route::get("forgot-password", [AuthController::class, "showForgotPasswordForm"])->name("forgot-password");
    Route::post("forgot-password", [AuthController::class, "sendResetLink"])->name("forgot-password.post");

    /* reset password */
    Route::get("reset-password/{token}", [AuthController::class, "showResetPasswordForm"])->name("reset-password");
    Route::post("reset-password", [AuthController::class, "resetPassword"])->name("reset-password.post");
});

/* route for with authentication */
Route::group(["prefix" => "admin", "as" => "admin.", 'middleware' => ['admin']], function () {

    /* logout */
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    /* dashboard */
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    /* category management */
    Route::resource('categories', CategoryController::class);

    /* food management */
    Route::resource('menus', FoodController::class);

    /* table management */
    Route::patch('tables/{id}/toggle-availability', [TableController::class, 'toggleAvailability'])->name('tables.toggle-availability');
    Route::resource('tables', TableController::class);

    /* permission management */
   Route::resource('permissions', PermissionController::class);

   /* role management */
   Route::resource('roles', RolesController::class);

   /* user role management */
   Route::resource('users', UserRoleController::class);
});

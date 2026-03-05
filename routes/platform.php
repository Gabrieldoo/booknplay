<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Tabuna\Breadcrumbs\Trail;
use App\Models\Booking as BookingModel;

use App\Orchid\Screens\PlatformScreen;
use App\Orchid\Screens\BookingScreen;
use App\Orchid\Screens\UserManagementScreen;

use App\Orchid\Screens\User\UserListScreen;
use App\Orchid\Screens\User\UserEditScreen;
use App\Orchid\Screens\User\UserProfileScreen;

use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\BookingEditScreen;

/*
|--------------------------------------------------------------------------
| Main Dashboard
|--------------------------------------------------------------------------
*/

Route::screen('/main', PlatformScreen::class)
    ->name('platform.main')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->push(__('Dashboard'), route('platform.main')));


/*
|--------------------------------------------------------------------------
| Booking Management
|--------------------------------------------------------------------------
*/

Route::screen('booking', BookingScreen::class)
    ->name('platform.booking')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.main')
        ->push(__('Booking'), route('platform.booking')));

    
Route::screen('booking/{booking}/edit', BookingEditScreen::class)
    ->name('platform.booking.edit')
    ->breadcrumbs(fn (Trail $trail, BookingModel $booking) => $trail
        ->parent('platform.booking')
        ->push(__('Edit Booking #:id', ['id' => $booking->id]), route('platform.booking.edit', $booking->id)));

/*
|--------------------------------------------------------------------------
| Custom User Management (CRUD)
|--------------------------------------------------------------------------
*/

Route::screen('users-management', UserManagementScreen::class)
    ->name('platform.users.management');


/*
|--------------------------------------------------------------------------
| Profile
|--------------------------------------------------------------------------
*/

Route::screen('profile', UserProfileScreen::class)
    ->name('platform.profile')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.main')
        ->push(__('Profile'), route('platform.profile')));


/*
|--------------------------------------------------------------------------
| Users (Orchid default)
|--------------------------------------------------------------------------
*/

Route::screen('users', UserListScreen::class)
    ->name('platform.systems.users')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.main')
        ->push(__('Users'), route('platform.systems.users')));

Route::screen('users/create', UserEditScreen::class)
    ->name('platform.systems.users.create');

Route::screen('users/{user}/edit', UserEditScreen::class)
    ->name('platform.systems.users.edit');


/*
|--------------------------------------------------------------------------
| Roles
|--------------------------------------------------------------------------
*/

Route::screen('roles', RoleListScreen::class)
    ->name('platform.systems.roles')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.main')
        ->push(__('Roles'), route('platform.systems.roles')));

Route::screen('roles/create', RoleEditScreen::class)
    ->name('platform.systems.roles.create');

Route::screen('roles/{role}/edit', RoleEditScreen::class)
    ->name('platform.systems.roles.edit');

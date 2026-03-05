<?php

declare(strict_types=1);

namespace App\Orchid;

use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\OrchidServiceProvider;
use Orchid\Screen\Actions\Menu;
use Orchid\Support\Color;

class PlatformProvider extends OrchidServiceProvider
{
    /**
     * Bootstrap application services
     */
    public function boot(Dashboard $dashboard): void
    {
        parent::boot($dashboard);
    }

    /**
     * Sidebar menu admin
     */
    public function menu(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | Main Menu
            |--------------------------------------------------------------------------
            */

            Menu::make('Dashboard')
                ->icon('bs.speedometer2')
                ->route(config('platform.index'))
                ->title('Main Menu'),

            Menu::make('Manajemen Booking')
                ->icon('bs.calendar-event')
                ->route('platform.booking'),

            /*
            |--------------------------------------------------------------------------
            | Management
            |--------------------------------------------------------------------------
            */

            Menu::make('Manajemen User')
                ->icon('bs.person-gear')
                ->route('platform.users.management')
                ->title('Management'),

            Menu::make('Users')
                ->icon('bs.people')
                ->route('platform.systems.users')
                ->permission('platform.systems.users'),

            Menu::make('Roles')
                ->icon('bs.shield-lock')
                ->route('platform.systems.roles')
                ->permission('platform.systems.roles'),

        ];
    }

    /**
     * Permissions
     */
    public function permissions(): array
    {
        return [

            ItemPermission::group(__('System'))
                ->addPermission('platform.systems.roles', __('Roles'))
                ->addPermission('platform.systems.users', __('Users')),

        ];
    }
}
<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Orchid\Filters\Types\Like;
use Orchid\Filters\Types\Where;
use Orchid\Filters\Types\WhereDateStartEnd;
use Orchid\Platform\Models\User as Authenticatable;

class User extends Authenticatable 
{
    /**
     * Mass assignable
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // penting untuk admin / user
        // 'email_verified_at',
    ];

    /**
     * Hidden attributes
     */
    protected $hidden = [
        'password',
        'remember_token',
        'permissions',
    ];

    /**
     * Cast attributes
     */
    protected $casts = [
        'permissions'       => 'array',
        'email_verified_at' => 'datetime',
    ];

    /**
     * Filters (Orchid)
     */
    protected $allowedFilters = [
        'id'         => Where::class,
        'name'       => Like::class,
        'email'      => Like::class,
        'updated_at' => WhereDateStartEnd::class,
        'created_at' => WhereDateStartEnd::class,
    ];

    /**
     * Sort (Orchid)
     */
    protected $allowedSorts = [
        'id',
        'name',
        'email',
        'updated_at',
        'created_at',
    ];

    /**
     * Access to Orchid dashboard
     */
    public function hasAccess(string $permit, bool $cache = true): bool
{
    return $this->role === 'admin';
}
}
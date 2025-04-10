<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles; // Add Spatie trait for roles and permissions

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles; // Include HasRoles trait

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'admin',
        'security_question', // Added for Lab Exercise 4/3
        'security_answer',   // Added for Lab Exercise 4/3
        'temp_password',      // Added for Lab Exercise 4/4
        'temp_password_used', // Added for Lab Exercise 4/4
        'temp_password_expires_at', // Added for expiry of temp password
        'credit', // Added for purchase system
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'security_answer', // Hide security answer for security
        'temp_password', // Hide for security
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'temp_password_used' => 'boolean',
            'credit' => 'decimal:2',
        ];
    }

    // Relationship to purchases
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Booking;


class User extends Authenticatable
{
  /** @use HasFactory<\Database\Factories\UserFactory> */
  use HasFactory, Notifiable;

  // define role-constants
  public const ROLE_ADMIN = 'admin';
  public const ROLE_CUSTOMER = 'customer';

  /**
   * The attributes that are mass assignable.
   *
   * @var list<string>
   */
  protected $fillable = [
    'name',
    'email',
    'password',
    'role',
  ];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var list<string>
   */
  protected $hidden = [
    'password',
    'remember_token',
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
    ];
  }

  public function bookings()
  {
    return $this->hasMany(Booking::class);
  }

  /**
   * proofs, if user is admin
   *
   * @return bool
   */
  public function isAdmin(): bool
  {
    return $this->role === self::ROLE_ADMIN;
  }

  /**
   * proofs, if user is customer
   *
   * @return bool
   */
  public function isCustomer(): bool
  {
    return $this->role === self::ROLE_CUSTOMER;
  }
}

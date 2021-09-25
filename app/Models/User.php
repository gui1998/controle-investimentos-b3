<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
  use HasApiTokens, HasFactory, Notifiable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name',
    'email',
    'password',
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password',
    'remember_token',
  ];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
  ];

  public function getData()
  {
    return static::orderBy('created_at', 'desc')->get();
  }

  public function storeData($input)
  {
    return static::create($input);
  }

  public function findData($id)
  {
    return static::find($id);
  }

  public function updateData($id, $input)
  {
    return static::find($id)->update($input);
  }

  public function deleteData($id)
  {
    return static::find($id)->delete();
  }

  public function assignRole(Role $role)
  {
    return $this->roles()->save($role);
  }

  public function roles()
  {
    return $this->belongsToMany(Role::class);
  }

  public function stocks()
  {
    return $this->hasMany(Stock::class);
  }

  /*** @param string|array $roles */
  public function authorizeRoles($user, $roles)
  {
    if (is_array($roles)) {
      return $this->hasAnyRole($user, $roles);
    }
    return $this->hasRole($user, $roles);
  }

  /*** Check multiple roles* @param array $roles */
  public function hasAnyRole($user, $roles)
  {
    return (!blank(DB::table('users')->where('users.id', $user->id)
      ->join('role_user', 'role_user.user_id', '=', 'users.id')
      ->join('roles', 'roles.id', '=', 'role_user.role_id')
      ->whereIn('roles.name', $roles)
      ->pluck('roles.name')));
  }

  /*** Check one role* @param string $role */
  public function hasRole($user, $role)
  {
    return (!blank(DB::table('users')->where('users.id', $user->id)
      ->join('role_user', 'role_user.user_id', '=', 'users.id')
      ->join('roles', 'roles.id', '=', 'role_user.role_id')
      ->where('roles.name', $role)
      ->pluck('roles.name')));
  }
}

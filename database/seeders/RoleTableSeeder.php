<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $role_employee = new Role();
    $role_employee->name = 'user';
    $role_employee->description = 'Usuário Padrão';
    $role_employee->save();

    $role_manager = new Role();
    $role_manager->name = 'admin';
    $role_manager->description = 'Administrador';
    $role_manager->save();
  }
}

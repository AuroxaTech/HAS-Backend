<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        
        Role::create([
            'name' => 'Landlord',
            'slug' => 'landlord',
            'status' => 1,
        ]);
        Role::create([
            'name' => 'Tenant',
            'slug' => 'tenant',
            'status' => 1,
        ]);
        Role::create([
            'name' => 'Service Provider',
            'slug' => 'serviceprovider',
            'status' => 1,
        ]);
        Role::create([
            'name' => 'Visitor',
            'slug' => 'visitor',
            'status' => 1,
        ]);
        Role::create([
            'name' => 'Admin',
            'slug' => 'admin',
            'status' => 1,
        ]);
    }
}

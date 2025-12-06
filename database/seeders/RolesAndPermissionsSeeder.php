<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Bersihkan cache permission agar seeder idempotent
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Definisikan permission granular sesuai kebutuhan aplikasi organisasi
        $permissions = [
            // users
            'users.create', 'users.read', 'users.update', 'users.delete',

            // roles & permissions
            'roles.manage',

            // organizations
            'organizations.create', 'organizations.read', 'organizations.update', 'organizations.delete',

            // departments/teams/members
            'departments.manage', 'teams.manage', 'members.manage',

            // announcements/documents/events/tasks
            'announcements.create', 'announcements.publish', 'announcements.delete',
            'documents.upload', 'documents.download', 'documents.delete',
            'events.create', 'events.manage',
            'tasks.create', 'tasks.assign', 'tasks.update', 'tasks.complete',

            // audit
            'logs.view',
        ];

        // Create permissions
        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // Define roles and assign permissions
        $roles = [
            'super-admin' => Permission::pluck('name')->toArray(), // semua permission
            'admin' => [
                'users.create','users.read','users.update','users.delete',
                'roles.manage',
                'organizations.update',
                'departments.manage','teams.manage','members.manage',
                'announcements.create','announcements.publish','announcements.delete',
                'documents.upload','documents.download','documents.delete',
                'events.create','events.manage',
                'tasks.create','tasks.assign','tasks.update','tasks.complete',
                'logs.view',
            ],
            'manager' => [
                'members.manage',
                'teams.manage',
                'tasks.create','tasks.assign','tasks.update','tasks.complete',
                'events.create','events.manage',
            ],
            'staff' => [
                'tasks.create','tasks.update',
                'documents.upload','documents.download',
                'announcements.create'
            ],
            'member' => [
                'documents.download','announcements.create'
            ],
        ];

        // Create roles and sync permissions
        foreach ($roles as $roleName => $perms) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $role->syncPermissions($perms);
        }
    }
}

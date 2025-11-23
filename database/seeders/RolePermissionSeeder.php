<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // User management
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            'users.import',
            'users.export',
            
            // Course management
            'courses.view',
            'courses.create',
            'courses.edit',
            'courses.delete',
            'courses.publish',
            
            // Content management
            'content.view',
            'content.create',
            'content.edit',
            'content.delete',
            'content.moderate',
            
            // Student features
            'courses.enroll',
            'courses.learn',
            'assignments.submit',
            'quizzes.take',
            
            // Teacher features
            'groups.manage',
            'assignments.grade',
            'students.view',
            
            // Admin features
            'analytics.view',
            'settings.manage',
            'roles.manage',
            
            // Monetization
            'payments.process',
            'subscriptions.manage',
            'promocodes.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        $student = Role::create(['name' => 'student']);
        $student->givePermissionTo([
            'courses.view',
            'courses.enroll',
            'courses.learn',
            'assignments.submit',
            'quizzes.take',
        ]);

        $teacher = Role::create(['name' => 'teacher']);
        $teacher->givePermissionTo([
            'courses.view',
            'courses.create',
            'courses.edit',
            'courses.publish',
            'content.view',
            'content.create',
            'content.edit',
            'groups.manage',
            'assignments.grade',
            'students.view',
        ]);

        $curator = Role::create(['name' => 'curator']);
        $curator->givePermissionTo([
            'courses.view',
            'groups.manage',
            'students.view',
            'analytics.view',
        ]);

        $moderator = Role::create(['name' => 'moderator']);
        $moderator->givePermissionTo([
            'courses.view',
            'content.view',
            'content.moderate',
            'users.view',
        ]);

        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());
    }
}

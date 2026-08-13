<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $muzakkiRole = Role::firstOrCreate(['name' => 'muzakki']);

        // Migrate existing users
        \Illuminate\Database\Eloquent\Model::preventLazyLoading(false);
        User::query()->chunk(100, function ($users) use ($adminRole, $muzakkiRole) {
            foreach ($users as $user) {
                if (isset($user->getAttributes()['role'])) {
                    $roleString = $user->getAttributes()['role'];
                    if ($roleString === 'admin') {
                        $user->assignRole($adminRole);
                    } elseif ($roleString === 'muzakki') {
                        $user->assignRole($muzakkiRole);
                    }
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spatie', function (Blueprint $table) {
            //
        });
    }
};

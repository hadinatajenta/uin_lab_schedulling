<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone_number')->nullable()->after('password');
            $table->string('nim')->nullable()->after('phone_number');
            $table->string('nip')->nullable()->after('nim');
            $table->foreignId('department_id')->nullable()->after('nip')->constrained('departments')->nullOnDelete();
            $table->integer('entry_year')->nullable()->after('department_id');
            $table->foreignId('supervisor_id')->nullable()->after('entry_year')->constrained('users')->nullOnDelete();
            $table->string('avatar')->nullable()->after('supervisor_id');
            
            // Rename last_login to last_login_at if it exists
            if (Schema::hasColumn('users', 'last_login')) {
                $table->renameColumn('last_login', 'last_login_at');
            } else {
                $table->timestamp('last_login_at')->nullable()->after('is_active');
            }
            
            $table->boolean('must_change_password')->default(true)->after('is_active');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropForeign(['supervisor_id']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);

            $table->dropColumn([
                'phone_number',
                'nim',
                'nip',
                'department_id',
                'entry_year',
                'supervisor_id',
                'avatar',
                'must_change_password',
                'created_by',
                'updated_by',
            ]);

            if (Schema::hasColumn('users', 'last_login_at')) {
                $table->renameColumn('last_login_at', 'last_login');
            }
        });
    }
};

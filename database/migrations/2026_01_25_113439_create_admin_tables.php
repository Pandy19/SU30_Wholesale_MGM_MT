<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('admin_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 150);
            $table->string('email', 160)->unique();
            $table->string('password', 255);
            $table->string('phone', 30)->nullable();
            $table->string('avatar_path', 255)->nullable();
            $table->enum('status', ['Pending','Active','Inactive','Blocked'])->default('Pending');
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->timestamps(); // created_at, updated_at
        });

        Schema::create('admin_roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 60)->unique();   // admin, staff, supplier, ...
            $table->string('label', 80);
            $table->string('description', 200)->nullable();
            $table->enum('status', ['Active','Inactive'])->default('Active');
            $table->timestamps();
        });

        Schema::create('admin_user_roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('assigned_by')->nullable();
            $table->timestamp('assigned_at')->useCurrent();

            $table->unique(['user_id','role_id']);

            $table->foreign('user_id')->references('id')->on('admin_users');
            $table->foreign('role_id')->references('id')->on('admin_roles');
            $table->foreign('assigned_by')->references('id')->on('admin_users');
        });

        Schema::create('admin_login_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('role_attempt', 60);
            $table->string('email_attempt', 160);
            $table->string('ip_address', 45);
            $table->string('user_agent', 255)->nullable();
            $table->boolean('success')->default(false);
            $table->string('fail_reason', 120)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('user_id')->references('id')->on('admin_users');
        });

        Schema::create('supplier_profiles', function (Blueprint $table) {
            $table->bigIncrements('supplier_profile_id');
            $table->unsignedBigInteger('user_id');
            $table->string('company_name', 200);
            $table->string('company_license', 120);
            $table->string('license_file', 255);
            $table->enum('verification_status', ['Pending','Approved','Rejected'])->default('Pending');
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->string('note', 255)->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('admin_users');
            $table->foreign('verified_by')->references('id')->on('admin_users');
        });

        Schema::create('supplier_approval_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('supplier_profile_id');
            $table->enum('action', ['Approved','Rejected']);
            $table->unsignedBigInteger('action_by');
            $table->string('remark', 255)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('supplier_profile_id')->references('supplier_profile_id')->on('supplier_profiles');
            $table->foreign('action_by')->references('id')->on('admin_users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supplier_approval_logs');
        Schema::dropIfExists('supplier_profiles');
        Schema::dropIfExists('admin_login_logs');
        Schema::dropIfExists('admin_user_roles');
        Schema::dropIfExists('admin_roles');
        Schema::dropIfExists('admin_users');
    }
};

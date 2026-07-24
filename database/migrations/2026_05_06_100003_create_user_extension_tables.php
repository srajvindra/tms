<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'b2b_schema';

    public function up(): void
    {
        Schema::create('user_metadata', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('phone_number', 50)->nullable();
            $table->foreignId('profile_photo_id')->nullable()->constrained('master_documents')->nullOnDelete();
            $table->string('uploaded_status', 50)->nullable();
            $table->timestamps();
        });

        Schema::create('user_availabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedTinyInteger('day_of_week');
            $table->timestamps();
            $table->unique(['user_id', 'day_of_week']);
        });

        Schema::create('availability_time_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('availability_id')->constrained('user_availabilities')->cascadeOnDelete();
            $table->time('start_time');
            $table->time('end_time');
            $table->timestamps();
        });

        Schema::create('availability_share_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('token', 64)->unique();
            $table->json('filters')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });

        Schema::create('user_module_view', function (Blueprint $table) {
            $table->id();
            $table->enum('module', ['Lease prospects', 'Onwer prospects', 'Owner', 'tenant']);
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->json('columns')->nullable();
            $table->json('filters')->nullable();
            $table->timestamps();
        });

        Schema::create('google_calendar_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->json('token');
            $table->string('google_email')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('google_calendar_tokens');
        Schema::dropIfExists('user_module_view');
        Schema::dropIfExists('availability_share_tokens');
        Schema::dropIfExists('availability_time_slots');
        Schema::dropIfExists('user_availabilities');
        Schema::dropIfExists('user_metadata');
    }
};

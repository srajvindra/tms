<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'b2b_schema';

    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->foreignId('event_legend_id')->nullable()->constrained('event_legends')->nullOnDelete();
            $table->dateTime('start_datetime');
            $table->dateTime('end_datetime');
            $table->text('description')->nullable();
            $table->text('internal_note')->nullable();
            $table->enum('status', ['active', 'done', 'cancelled', 'confirmed'])->default('active')->nullable();
            $table->string('google_event_id')->nullable();
            $table->foreignId('event_type_id')->nullable()->constrained('event_types')->nullOnDelete();
            $table->enum('type', ['property', 'staff'])->default('property');
            $table->timestamps();
        });

        Schema::create('event_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
            $table->unsignedBigInteger('contact_id');
            $table->timestamps();
        });

        Schema::create('event_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
            $table->text('note');
            $table->timestamps();
        });

        Schema::create('event_share_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
            $table->string('token', 64)->unique();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });

        Schema::create('event_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('role', ['owner', 'staff'])->default('staff');
        });

        Schema::create('external_event_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('email', 150)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('external_event_contacts');
        Schema::dropIfExists('event_users');
        Schema::dropIfExists('event_share_links');
        Schema::dropIfExists('event_notes');
        Schema::dropIfExists('event_contacts');
        Schema::dropIfExists('events');
    }
};

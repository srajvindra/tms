<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'b2b_schema';

    public function up(): void
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->string('coid', 20)->nullable();
            $table->enum('type', ['private', 'group'])->default('private');
            $table->string('conversationable_type', 100)->nullable();
            $table->unsignedBigInteger('conversationable_id')->nullable();
            $table->string('subject')->nullable();
            $table->enum('status', ['active', 'archived', 'escalated'])->default('active');
            $table->dateTime('last_message_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['conversationable_type', 'conversationable_id']);
        });

        Schema::create('conversation_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained('conversations')->cascadeOnDelete();
            $table->string('participantable_type', 100);
            $table->unsignedBigInteger('participantable_id');
            $table->enum('role', ['agent', 'contact', 'owner', 'tenant'])->default('agent');
            $table->dateTime('last_read_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['conversation_id', 'participantable_type', 'participantable_id'], 'conv_part_unique');
            $table->index(['participantable_type', 'participantable_id'], 'conv_part_morph_idx');
        });

        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->string('mid', 20)->nullable();
            $table->foreignId('conversation_id')->constrained('conversations')->cascadeOnDelete();
            $table->enum('channel', ['email', 'sms', 'call', 'note', 'meeting'])->default('email');
            $table->enum('direction', ['inbound', 'outbound'])->default('outbound');
            $table->string('from_type', 100)->nullable();
            $table->unsignedBigInteger('from_id')->nullable();
            $table->string('from_name', 200)->nullable();
            $table->string('from_phone', 30)->nullable();
            $table->string('to_name', 200)->nullable();
            $table->string('to_phone', 30)->nullable();
            $table->string('subject')->nullable();
            $table->longText('body')->nullable();
            $table->unsignedInteger('call_duration_seconds')->nullable();
            $table->enum('call_status', ['answered', 'voicemail', 'missed', 'no_answer'])->nullable();
            $table->boolean('is_pinned')->default(false);
            $table->boolean('is_read')->default(false);
            $table->dateTime('sent_at')->nullable();
            $table->string('external_id')->nullable();
            $table->enum('status', ['draft', 'sent', 'delivered', 'failed', 'received'])->default('sent');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['from_type', 'from_id']);
            $table->index(['conversation_id', 'channel']);
            $table->index('is_pinned');
        });

        Schema::create('message_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('message_id')->constrained('messages')->cascadeOnDelete();
            $table->foreignId('master_document_id')->constrained('master_documents')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('message_recipients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('message_id')->constrained('messages')->cascadeOnDelete();
            $table->enum('type', ['to', 'cc', 'bcc'])->default('to');
            $table->string('recipientable_type', 100)->nullable();
            $table->unsignedBigInteger('recipientable_id')->nullable();
            $table->string('email')->nullable();
            $table->string('name', 200)->nullable();
            $table->timestamps();
            $table->index(['recipientable_type', 'recipientable_id'], 'msg_recipients_morph_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('message_recipients');
        Schema::dropIfExists('message_attachments');
        Schema::dropIfExists('messages');
        Schema::dropIfExists('conversation_participants');
        Schema::dropIfExists('conversations');
    }
};

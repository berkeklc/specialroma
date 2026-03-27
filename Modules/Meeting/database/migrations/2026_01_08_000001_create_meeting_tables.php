<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meeting_staff', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('photo')->nullable();
            $table->json('title')->nullable();
            $table->json('bio')->nullable();
            $table->json('expertise')->nullable();
            $table->json('working_hours')->nullable();
            $table->integer('meeting_duration')->default(30);
            $table->integer('buffer_time')->default(15);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('meeting_appointments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('staff_id')->constrained('meeting_staff')->cascadeOnDelete();
            $table->string('client_name');
            $table->string('client_email');
            $table->string('client_phone')->nullable();
            $table->text('notes')->nullable();
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
            $table->string('timezone')->default('Europe/Istanbul');
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
            $table->enum('meeting_type', ['zoom', 'google_meet', 'teams', 'phone', 'in_person'])->default('zoom');
            $table->string('meeting_link')->nullable();
            $table->string('meeting_id')->nullable();
            $table->string('meeting_password')->nullable();
            $table->timestamp('reminder_sent_at')->nullable();
            $table->string('cancellation_reason')->nullable();
            $table->timestamps();

            $table->index(['staff_id', 'starts_at', 'status']);
        });

        Schema::create('meeting_blocked_slots', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('staff_id')->nullable()->constrained('meeting_staff')->cascadeOnDelete();
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
            $table->string('reason')->nullable();
            $table->boolean('is_recurring')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meeting_blocked_slots');
        Schema::dropIfExists('meeting_appointments');
        Schema::dropIfExists('meeting_staff');
    }
};

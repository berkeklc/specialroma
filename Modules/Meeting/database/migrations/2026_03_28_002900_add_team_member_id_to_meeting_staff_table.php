<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('meeting_staff', function (Blueprint $table): void {
            $table->unsignedBigInteger('team_member_id')->nullable()->after('id')->index();
        });
    }

    public function down(): void
    {
        Schema::table('meeting_staff', function (Blueprint $table): void {
            $table->dropColumn('team_member_id');
        });
    }
};

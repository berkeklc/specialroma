<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qr_menu_restaurants', function (Blueprint $table): void {
            $table->id();
            $table->json('name');
            $table->json('description')->nullable();
            $table->string('slug')->unique();
            $table->string('logo')->nullable();
            $table->json('working_hours')->nullable();
            $table->string('currency', 10)->default('TRY');
            $table->string('primary_color', 7)->default('#1a1a2e');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('qr_menu_tables', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('restaurant_id')->constrained('qr_menu_restaurants')->cascadeOnDelete();
            $table->string('name');
            $table->string('qr_code_url')->nullable();
            $table->string('qr_code_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['restaurant_id', 'is_active']);
        });

        Schema::create('qr_menu_categories', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('restaurant_id')->constrained('qr_menu_restaurants')->cascadeOnDelete();
            $table->json('name');
            $table->json('description')->nullable();
            $table->string('image')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['restaurant_id', 'sort_order', 'is_active']);
        });

        Schema::create('qr_menu_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('category_id')->constrained('qr_menu_categories')->cascadeOnDelete();
            $table->foreignId('restaurant_id')->constrained('qr_menu_restaurants')->cascadeOnDelete();
            $table->json('name');
            $table->json('description')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->string('image')->nullable();
            $table->json('allergens')->nullable();
            $table->json('badges')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_available')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['category_id', 'sort_order', 'is_available']);
            $table->index(['restaurant_id', 'is_featured']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qr_menu_items');
        Schema::dropIfExists('qr_menu_categories');
        Schema::dropIfExists('qr_menu_tables');
        Schema::dropIfExists('qr_menu_restaurants');
    }
};

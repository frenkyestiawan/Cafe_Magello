<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_variants', function (Blueprint $table) {
            $table->id();

            $table->foreignId('menu_id')
                ->constrained('menus')
                ->cascadeOnDelete();

            $table->enum('name', ['Small', 'Medium', 'Large']);
            $table->decimal('price', 12, 2);
            $table->boolean('is_available')->default(true);
            $table->timestamps();

            $table->unique(['menu_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_variants');
    }
};

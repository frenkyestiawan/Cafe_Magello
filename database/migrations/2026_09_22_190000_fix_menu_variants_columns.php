<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('menu_variants')) {
            Schema::create('menu_variants', function (Blueprint $table) {
                $table->id();
                $table->foreignId('menu_id')->constrained('menus')->cascadeOnDelete();
                $table->string('name');
                $table->decimal('price', 12, 2);
                $table->boolean('is_available')->default(true);
                $table->timestamps();
                $table->unique(['menu_id', 'name']);
            });

            return;
        }

        Schema::table('menu_variants', function (Blueprint $table) {
            if (!Schema::hasColumn('menu_variants', 'menu_id')) {
                $table->foreignId('menu_id')->nullable()->after('id');
            }

            if (!Schema::hasColumn('menu_variants', 'name')) {
                $table->string('name')->after('menu_id');
            }

            if (!Schema::hasColumn('menu_variants', 'price')) {
                $table->decimal('price', 12, 2)->default(0)->after('name');
            }

            if (!Schema::hasColumn('menu_variants', 'is_available')) {
                $table->boolean('is_available')->default(true)->after('price');
            }
        });

        if (Schema::hasColumn('menu_variants', 'menu_id')) {
            try {
                DB::statement('ALTER TABLE `menu_variants` ADD CONSTRAINT `menu_variants_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE');
            } catch (\Throwable $e) {
                // Ignore if the foreign key already exists.
            }
        }

        try {
            Schema::table('menu_variants', function (Blueprint $table) {
                $table->unique(['menu_id', 'name']);
            });
        } catch (\Throwable $e) {
            // Ignore if unique constraint already exists.
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('menu_variants')) {
            return;
        }

        try {
            Schema::table('menu_variants', function (Blueprint $table) {
                $table->dropUnique(['menu_id', 'name']);
            });
        } catch (\Throwable $e) {
            // Ignore if index does not exist.
        }

        Schema::table('menu_variants', function (Blueprint $table) {
            if (Schema::hasColumn('menu_variants', 'is_available')) {
                $table->dropColumn('is_available');
            }

            if (Schema::hasColumn('menu_variants', 'price')) {
                $table->dropColumn('price');
            }

            if (Schema::hasColumn('menu_variants', 'name')) {
                $table->dropColumn('name');
            }

            if (Schema::hasColumn('menu_variants', 'menu_id')) {
                $table->dropColumn('menu_id');
            }
        });
    }
};

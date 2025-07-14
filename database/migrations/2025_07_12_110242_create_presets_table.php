<?php

declare(strict_types=1);

use App\Models\User;
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
        Schema::create('presets', function (Blueprint $table): void {
            $table->id();

            $table->string('name')->unique();
            $table->jsonb('data')->nullable();
            $table->timestamp('activated_at')->nullable();

            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();

            $table->timestamps();

            $table->index('activated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presets');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('performer_aliases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('performer_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('locale', 5);
            $table->boolean('primary')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('performer_aliases');
    }
};

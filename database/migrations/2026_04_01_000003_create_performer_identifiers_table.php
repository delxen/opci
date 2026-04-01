<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('performer_identifiers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('performer_id')->constrained()->cascadeOnDelete();
            $table->string('type');
            $table->string('value');
            $table->timestamps();

            $table->unique(['performer_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('performer_identifiers');
    }
};

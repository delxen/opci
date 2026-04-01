<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('performers', function (Blueprint $table) {
            $table->id();
            $table->string('source');
            $table->string('name');
            $table->string('type')->nullable();
            $table->string('gender')->nullable();
            $table->string('country', 2)->nullable();
            $table->string('disambiguation')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('performers');
    }
};

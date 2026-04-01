<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('api_cache', function (Blueprint $table) {
            $table->id();
            $table->string('source');
            $table->string('endpoint');
            $table->text('response');
            $table->timestamp('expires_at');
            $table->timestamps();

            $table->unique(['source', 'endpoint']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_cache');
    }
};

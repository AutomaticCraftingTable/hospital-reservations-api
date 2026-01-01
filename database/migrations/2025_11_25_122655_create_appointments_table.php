<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('doctor_id')->nullable();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->string('title')->nullable();
            $table->timestamp('starting_at')->nullable();
            $table->timestamp('ending_at')->nullable();
            $table->string('room')->nullable();
            $table->timestamps();

            $table->foreign('doctor_id')
                ->references('id')->on('doctors')
                ->onDelete('set null');

            $table->foreign('client_id')
                ->references('id')->on('clients')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};

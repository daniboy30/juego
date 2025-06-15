<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['waiting', 'playing', 'finished'])->default('waiting');
            $table->foreignId('player_one_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('player_two_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('current_turn')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('winner_id')->nullable()->constrained('users')->onDelete('set null');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('games');
    }
};

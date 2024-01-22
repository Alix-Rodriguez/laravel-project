<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->integer('platform');
            $table->decimal('price', 8, 2);
            $table->decimal('discount', 8, 2);
            $table->text('video')->nullable();
            $table->text('summary');
            $table->string('cover')->nullable();
            $table->string('wallpaper')->nullable();
            $table->string('screenshots')->nullable();
            $table->string('slug')->nullable();
            $table->date('releaseDate')->nullable();
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
}

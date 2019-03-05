<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostPhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_photos', function (Blueprint $table) {
            $table->integer('postId')->unsigned();
            $table->foreign('postId')->references('id')->on('posts')->onUpdate('cascade');
            $table->integer('photoId')->unsigned();
            $table->foreign('photoId')->references('id')->on('photos')->onUpdate('cascade');
            /*$table->integer('photoId01');
            $table->integer('photoId02')->nullable();
            $table->integer('photoId03')->nullable();
            $table->integer('photoId04')->nullable();*/
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post_photos');
    }
}

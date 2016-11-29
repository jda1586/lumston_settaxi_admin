<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CatalogoImagesMigration extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::dropIfExists('producto_image');
        
	    Schema::create('producto_image', function($table){
	       $table->increments('id');
           $table->integer('id_producto')->unsigned();
           $table->string('image');
           $table->integer('sort')->default(0);
           $table->timestamps();
           $table->softDeletes();
           
           $table->foreign('id_producto')->references('id')->on('producto');
	    });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::dropIfExists('producto_image');
		//
	}

}

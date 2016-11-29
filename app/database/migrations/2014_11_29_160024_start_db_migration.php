<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StartDbMigration extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::dropIfExists('admin_account');
        Schema::dropIfExists('contenido');
        Schema::dropIfExists('producto');
        Schema::dropIfExists('categoria');
        Schema::dropIfExists('proyecto_image');
        Schema::dropIfExists('proyecto');
        
        Schema::create('admin_account', function($table){
            $table->increments('id');
            $table->string('username');
            $table->string('password');
            $table->string('role');
            $table->timestamps();
            $table->softDeletes();
        });
        
        Schema::create('contenido', function($table){
            $table->increments('id');
            $table->string('key');
            $table->string('title');
            $table->text('content');
            $table->string('type');
            $table->integer('sort')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
        
        Schema::create('categoria', function($table){
            $table->increments('id');
            $table->integer('id_parent')->unsigned()->nullable();
            $table->string('name');
            $table->integer('sort');
            $table->string('status')->default('inactive');
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('id_parent')->references('id')->on('categoria');
        });
        
        Schema::create('producto', function($table){
            $table->increments('id');
            $table->integer('id_categoria')->unsigned();
            $table->string('name');
            $table->string('description');
            $table->string('image');
            $table->integer('sort');
            $table->string('status')->default('inactive');
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('id_categoria')->references('id')->on('categoria');
        });
        
        Schema::create('proyecto', function($table){
            $table->increments('id');
            $table->string('title');
            $table->string('subtitle');
            $table->text('description');
            $table->string('image');
            $table->integer('sort');
            $table->string('status')->default('inactive');
            $table->timestamps();
            $table->softDeletes();
        });
        
        Schema::create('proyecto_image', function($table){
            $table->increments('id');
            $table->integer('id_proyecto')->unsigned();
            $table->string('image');
            $table->integer('sort');
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('id_proyecto')->references('id')->on('proyecto');
        });
        
		
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::dropIfExists('admin_account');
        Schema::dropIfExists('contenido');
        Schema::dropIfExists('producto');
        Schema::dropIfExists('categoria');
        Schema::dropIfExists('proyecto_image');
        Schema::dropIfExists('proyecto');
		
		//
	}

}

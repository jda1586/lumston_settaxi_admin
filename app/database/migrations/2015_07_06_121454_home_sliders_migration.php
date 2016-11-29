<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class HomeSlidersMigration extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	    Schema::dropIfExists('slide');
        
        Schema::table('producto_image', function($table){
            $table->dropColumn(array('created_at', 'updated_at', 'deleted_at'));
        });
        
	    Schema::create("slide", function($table){
	        $table->increments('id');
            $table->string("title");
            $table->string("subtitle");
            $table->string("area");
            $table->string("image");
            $table->string("status");
            $table->integer("sort");
            $table->timestamps();
            $table->softDeletes();
	    });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('producto_image', function($table){
            $table->timestamps();
            $table->softDeletes();
        });
        
	    Schema::dropIfExists('slide');
        
		//
	}

}

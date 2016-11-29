<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CatalogoVideosMigration extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	    Schema::table('producto_image', function($table){
	       $table->string("thumb")->after("image");
           $table->string("type")->after("thumb"); 
	    });
        
        ProductoImage::query()->update(array("thumb" => DB::raw("image"), "type" => "image"));
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('producto_image', function($table){
           $table->dropColumn("thumb"); 
           $table->dropColumn("type"); 
        });
	}

}

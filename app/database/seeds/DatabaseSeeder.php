<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();
		
		$this->seedTable('admin_account');
        $this->seedTable('contenido');
        $this->seedTable('categoria');
        $this->seedTable('producto');
        $this->seedTable('proyecto');
        $this->seedTable('proyecto_image');
        $this->seedTable('slide');
	}
    
    public function seedTable($tableName){
        $rowSet = static::csvToAssocArray(__DIR__ . "/csv/$tableName.csv");
        
        foreach($rowSet as $row){
            var_dump($row);
            DB::table($tableName)->insert($row);
        }
    }


	public static function csvToAssocArray($filename){
        $file = fopen($filename, 'r');

        $keys = Array();
        foreach(fgetcsv($file, null, '|') as $cell){
                $keys[] = $cell;
        }

        $arr = Array();
        while($row = fgetcsv($file, null, '|')){

                $arrRow = Array();
                foreach($row as $k => $cell){
                        if(strtolower($cell) == 'null'){
                                $arrRow[$keys[$k]] = null;
                        }else{
                                $arrRow[$keys[$k]] = $cell;
                        }
                }

                $arr[] = $arrRow;
        }

        return $arr;
    }



}

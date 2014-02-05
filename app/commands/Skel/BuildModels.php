<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class BuildModels extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'skel:models';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Build the models based on the database.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{	
		$dbName = DB::getDatabaseName();
		$tables = DB::select('SHOW TABLES');
		
		foreach( $tables as $table ){
			$primary = 'id';
			$timestamps = 'false';
			$hasTimestamps = 0;
			
			$fullTableName = 'Tables_in_'.$dbName;
			$tableName = $table->$fullTableName;
			
			$modelName = ucwords( str_replace('_', ' ', $tableName) );
			$modelName = str_replace(' ', '', $modelName);
		
			
			$columns = DB::select('SHOW COLUMNS FROM `'.$tableName.'`');
			foreach( $columns as $column ){
				if( $column->Key == 'PRI' )
					$primary = $column->Field;
				
				if( $column->Field=='created_at' || $column->Field=='updated_at' )
					$hasTimestamps ++;
				
				if( $hasTimestamps>=2 )
					$timestamps = 'true';
				
			}
			
			$createFile = false;
			$template = file_get_contents(__DIR__.'/templates/model.txt');
			$template = str_replace(array('{model}', '{table}', '{primary}', '{timestamps}'), array($modelName, $tableName, $primary, $timestamps), $template);
			
			$modelPath = app_path().'/models/'.$modelName.'.php';
			if( file_exists($modelPath) ){
				if( $this->confirm('Model '.$modelName.'.php exists, replace it? [y|n] n ', false) ){
					$createFile = true;
				}
			}else{
				$createFile = true;
			}
			
			if( $createFile ){
				file_put_contents($modelPath, $template);
				$this->info('created model "'.$modelName.'" with table "'.$tableName.'"');
			}			
		}
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			//array('example', InputArgument::REQUIRED, 'An example argument.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			//array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}
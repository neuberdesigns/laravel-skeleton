<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class BuildControllers extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'skel:controllers';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Build controllers based on tables on database.';

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
		$targetTable = $this->option('table');
		
		foreach( $tables as $table ){
			$primary = 'id';
			$timestamps = 'false';
			$hasTimestamps = 0;
			
			$fullTableName = 'Tables_in_'.$dbName;
			$tableName = $table->$fullTableName;
			
			if( !empty($targetTable) && $tableName!=$targetTable)
				continue;
			
			$controllerName = ucwords( str_replace('_', ' ', $tableName) );
			$controllerName = str_replace(' ', '', $controllerName);
			
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
			
			$modelPath = app_path().'/controllers/admin/'.$controllerName.'Controller.php';
			if( file_exists($modelPath) ){
				if( $this->confirm('Controller '.$controllerName.'Controller.php exists, replace it? [y|n] n ', false) ){
					$createFile = true;
				}
			}else{
				$createFile = true;
			}
			
			if( $createFile ){
				$template = file_get_contents(__DIR__.'/templates/controller.txt');
				$template = str_replace(array('{controller}'), array($controllerName), $template);
			
				file_put_contents($modelPath, $template);
				$this->info('created controller "'.$controllerName.'"');
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
			array('table', 't', InputOption::VALUE_OPTIONAL, 'Create views for epecified Table', null),
		);
	}

}
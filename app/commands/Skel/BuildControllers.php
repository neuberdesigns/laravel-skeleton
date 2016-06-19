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
	
	protected $database;
	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(){
		parent::__construct();
		$this->database = new DatabaseInfo();
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire(){
		$targetTable = $this->option('table');
		if( !empty($targetTable) ){
			$table = $this->database->find($targetTable);
			if( $table ){
				$this->buildController($table);
			}else{
				$this->error('table "'.$targetTable.'" was not found');
			}
		}else{
			foreach( $this->database->getTables() as $table){
				$this->buildController($table);
			}
		}
	}
	
	
	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function buildController($table){
		$primary = 'id';
		$timestamps = 'false';
		$hasTimestamps = 0;
		
		$controllerName = $table->getNameForClass();
		
		$columns = $table->getFields();
		foreach( $columns as $column ){
			if( $column->isPrimaryKey() )
				$primary = $column->getName();
			
			if( $column->getName()=='created_at' || $column->getName()=='updated_at' )
				$hasTimestamps ++;
			
			if( $hasTimestamps>=2 )
				$timestamps = 'true';
			
		}
		
		$createFile = false;
		$modelPath = app_path().'/controllers/admin/'.$controllerName.'Controller.php';
		if( file_exists($modelPath) ){
			if( $this->confirm('Controller '.$controllerName.'Controller.php exists, replace it? [y|N] ', false) ){
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

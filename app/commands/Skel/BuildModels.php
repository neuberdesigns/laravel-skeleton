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
				$this->buildModel($table);
			}else{
				$this->error('table "'.$targetTable.'" was not found');
			}
		}else{
			foreach( $this->database->getTables() as $table){
				$this->buildModel($table);
			}
		}
	}
	
	public function buildModel($table){
		$hasRelation = false;
		$relationTable = null;
		$relationType = null;
		$relationName = null;
		$relationsStr = '';
		
		$tableName = $table->getName();
		$primary = 'id';
		$timestamps = 'false';
		$hasTimestamps = 0;
		
		$modelName = $table->getNameForClass();
		
		$columns = $table->getFields();
		foreach( $columns as $column ){
			if( $column->isPrimaryKey())
				$primary = $column->getName();
			
			if( $column->getName()=='created_at' || $column->getName()=='updated_at' )
				$hasTimestamps ++;
			
			if( $hasTimestamps>=2 )
				$timestamps = 'true';
			
		}
		
		$createFile = false;
		$template = file_get_contents(__DIR__.'/templates/model.txt');
		$template = str_replace(array('{model}', '{table}', '{primary}', '{timestamps}', '{relations}'), array($modelName, $tableName, $primary, $timestamps, $relationsStr), $template);
		
		$modelPath = app_path().'/models/'.$modelName.'.php';
		if( file_exists($modelPath) ){
			if( $this->confirm('Model '.$modelName.'.php exists, replace it? [y|N] ', false) ){
				$createFile = true;
			}
		}else{
			$createFile = true;
		}
		
		if( $createFile ){
			file_put_contents($modelPath, $template);
			$this->info('created model "'.$modelName.'" with table "'.$tableName.'"');
			$relationsStr = '';
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
			array('table', 't', InputOption::VALUE_OPTIONAL, 'Create the model for epecified Table', null),
		);
	}
	
}

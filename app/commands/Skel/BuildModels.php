<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class BuildModels extends AbstractSkel {

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
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire(){
		$this->init('buildModel');
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
				
		$modelPath = app_path().'/models/'.$modelName.'.php';
		if( file_exists($modelPath) ){
			if( $this->confirm('Model '.$modelName.'.php exists, replace it? [y|N] ', false) ){
				$createFile = true;
			}
		}else{
			$createFile = true;
		}
		
		if( $createFile ){
			$template = new SkelTemplate('model');
			$template
					->mark('model', $modelName)
					->mark('table', $tableName)
					->mark('primary', $primary)
					->mark('timestamps', $timestamps)
					->mark('relations', $relationsStr)
					->save($modelPath);
			$this->info('created model "'.$modelName.'" with table "'.$tableName.'"');
			$relationsStr = '';
		}
		
		$this->generateAutoload();
		$this->info('done');
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments(){
		return array(
			//array('example', InputArgument::REQUIRED, 'An example argument.'),
		);
	}
	
	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions(){
		return $this->getDefaultOptions();
	}
	
}

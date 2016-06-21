<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class BuildControllers extends AbstractSkel {

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
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire(){
		$this->init('buildController');
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
		$controllerSeg = $this->canUseSegment() ? $this->getSegment() : $table->getNameForSegment();
		$controllerTitle = $table->getNameForTitle();
		
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
			$template = str_replace(array('{controller}', '{controller_seg}', '{controller_title}'), array($controllerName, $controllerSeg, $controllerTitle), $template);
		
			file_put_contents($modelPath, $template);
			$this->info('created controller "'.$controllerName.'"');
		}
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

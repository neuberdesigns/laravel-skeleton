<?php
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class AbstractSkel extends Command {
	protected $database;
	
	public function __construct(){
		parent::__construct();
		$this->database = new DatabaseInfo();
	}
	
	
	protected function getDefaultOptions(){
		return array(
			array('table', 't', InputOption::VALUE_OPTIONAL, 'Run command for a single table', null),
			array('segment', 's', InputOption::VALUE_OPTIONAL, 'Specify the controller segment', null),
			array('skipautoload', 'na', InputOption::VALUE_OPTIONAL, 'To skip generation of composer autoload', null),
		);
	}
	
	protected function init($methodname){
		$targetTable = $this->getTarget();
		
		if( $this->hasTarget() ){
			$table = $this->database->find($targetTable);
			if( $table ){
				$this->callFunc($methodname, $table);
			}else{
				$this->error('table "'.$targetTable.'" was not found');
			}
		}else{
			foreach( $this->database->getTables() as $table){
				$this->callFunc($methodname, $table);
			}
		}
	}
	
	protected function generateAutoload(){
		if(!$this->hasSkip()){
			$this->info('generating autoload');
			Artisan::call('dump-autoload');
		}
	}
	
	protected function hasTarget(){
		return !empty($this->option('table'));
	}
	
	protected function getTarget(){
		return $this->option('table');
	}
	
	protected function hasSegment(){
		return !empty($this->option('segment'));
	}
	
	protected function getSegment(){
		return $this->option('segment');
	}
	
	protected function canUseSegment(){
		return $this->hasTarget() && $this->hasSegment();
	}
	
	protected function hasSkip(){
		return !empty($this->option('skipautoload'));
	}
	
	protected function getSkip(){
		return $this->option('skipautoload');
	}
	
	private function callFunc($method, $table){
		call_user_func_array(array($this, $method), array($table));
	}
}

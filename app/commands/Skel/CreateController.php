<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CreateController extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'skel:create';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create a controllers';

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
		$controllerName = $this->option('name');
		
		/*$timestamps = 'false';
		$hasTimestamps = 0;*/
		
		
		$controllerName = ucwords( str_replace('_', ' ', $controllerName) );
		$controllerName = str_replace(' ', '', $controllerName);
		
		
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
			array('name', 'N', InputOption::VALUE_REQUIRED, 'Create the controller with the given name', null),
		);
	}

}

<?php
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class All extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'skel:all';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create controllers, models and views';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(){
		parent::__construct();
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions(){
		return array(
			array('table', 't', InputOption::VALUE_OPTIONAL, 'Create controller, model and views for epecified table', null),
			array('segment', 's', InputOption::VALUE_OPTIONAL, 'The name for controller segment and views dir', null),
		);
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire(){
		$target 		= $this->option('table');
		$seg 			= $this->option('segment');
		$skipautoload 	= $this->option('skipautoload');
		$data 			= array('-t' => $target, '-s'=>$seg, '-na'=>true);
		
		$this->call('skel:controller', $data);
		$this->call('skel:model', $data);
		$this->call('skel:view', $data);
		
		$this->info('generating autoload');
		Artisan::call('dump-autoload');
		$this->info('done');
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

}

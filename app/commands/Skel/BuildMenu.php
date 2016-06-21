<?php
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class BuildMenu extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'skel:menu';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create the sidemenu for the admin area';

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
		return array();
	}
	
	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments(){
		return array();
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire(){
		$menuTemplate = new SkelTemplate('menu');
		$viewsDir = app_path().'/views/';
		$adminViews = $viewsDir.'admin/';
		$menuFile = $viewsDir.'/layout/menu.blade.php';
		$entriesStr = '';
		
		$dirs = glob($adminViews.'*');
		foreach($dirs as $dirPath){
			$dirname = basename($dirPath);
			if( is_dir($dirPath) && ($dirname!='admin' && $dirname!='partial') ){
				$entryTemplate = new SkelTemplate('menu-entry');
				$title = str_replace(array('_', '-'), array(' ', ''), $dirname);
				$title = ucwords($title);
				$icon = '';
				$entriesStr .= $entryTemplate->mark('seg', $dirname)->mark('title', $title)->replace()->getOutput();
			}
		}
		
		$menuTemplate->mark('entries', $entriesStr)->replace()->save($menuFile);
		$this->info('done');
	}

}

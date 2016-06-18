<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class BuildLayout extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'skel:layout';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Build the layout file by joining files.';

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
		//$this->argument('css-dir')
		$html = new simple_html_dom();
		$layout = '';
		$layoutPath = app_path().'/views/layout/site.blade.php';
		$files = $this->option('file');
		
		if( file_exists($layoutPath) ){
			if( $this->confirm('Layout site.blade.php exists, replace it? [y|N] ', false) ){
				
				foreach( $files as $i=>$file ){
					if( file_exists($file) ){
						$layoutPart = file_get_contents($file);
						$html->load($layoutPart, true, false);
						
						$styles = $html->find('link');
						$scripts = $html->find('script');
						
						$replaceSrc = array();
						$replaceTgt = array();
						
						foreach( $styles as $k => $css ){
							if( $css->type=='text/css' && !empty($css->href) && strpos($css->href, 'http')===false ){
								$pathParts = explode('/', $css->href);
								$base = array_shift($pathParts);
								$path = implode('/', $pathParts);
								
								$laravelTag = '{{HTML::style(\''.$this->argument('css-dir').'/'.$path.'\')}}';
								$replaceSrc[] = $css;
								$replaceTgt[] = $laravelTag;
								//$css->href = '{{HTML::style(\''.$this->argument('css-dir').'/'.$path.'\')}}';
							}
						}
						
						foreach( $scripts as $js ){
							if( !empty($js->src) && strpos($js->src, 'http')===false ){
								$pathParts = explode('/', $js->src);
								$base = array_shift($pathParts);
								$path = implode('/', $pathParts);
								
								$laravelTag = '{{HTML::script(\''.$this->argument('js-dir').'/'.$path.'\')}}';
								$replaceSrc[] = $js;
								$replaceTgt[] = $laravelTag;
								//$js->src = $laravelTag;
							}
						}
						
						$layoutPart = str_replace($replaceSrc, $replaceTgt, $layoutPart);
						
						if( $i >0 ){
							$layout .= "\n\n<!-- JOINED HERE -->\n\n";
						}
						
						$layout .= $layoutPart;
					}else{
						$this->error('The file "'.$file.'" not exists');
					}
				}
				
				file_put_contents($layoutPath, $layout);
				$this->info('done');
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
			array('css-dir', InputArgument::OPTIONAL, 'CSS directory name', 'styles'),
			array('js-dir', InputArgument::OPTIONAL, 'Java Script directory name', 'scripts'),
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
			array('file', 'f', InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'file to join in order they are passed', array('../template/includes/topo.php', '../template/includes/rodape.php') ),
		);
	}

}

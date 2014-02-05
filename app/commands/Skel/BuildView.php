<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class BuildView extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'skel:views';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Build the view, list and add for each controller';

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
		
		foreach( $tables as $table ){
			$primary = 'id';
			$timestamps = 'false';
			$hasTimestamps = 0;
			
			$fullTableName = 'Tables_in_'.$dbName;
			$tableName = $table->$fullTableName;
			
			//studly_case()
			$controllerName = ucwords( str_replace('_', ' ', $tableName) );
			$controllerName = str_replace(' ', '', $controllerName);
			$dirName = str_replace('_', '-', $tableName);
			
			$viewPath = app_path().'/views/admin/';
			
			$fieldsList = array();
			
			$columns = DB::select('SHOW COLUMNS FROM `'.$tableName.'`');
			foreach( $columns as $k=>$column ){
				if( $column->Key == 'PRI' || $column->Key == 'MUL' || $column->Field=='updated_at' || $column->Field=='deleted_at' )
					continue;
				
				$size = 5;
				$inputType = 'text';
				$inputList = array();
				
				//starts_with();
				$type = substr($column->Type, 0, strpos($column->Type, '('));
								
				//get content between () if exists
				$start = strpos($column->Type, '(');
				$end = strrpos($column->Type, ')');
				if( $start!==false && $end!==false ){
					$length = ( strlen($column->Type)-$start-2);
					$parentesisContent = substr($column->Type, $start+1, $length );
					
					//var_dump($column->Field, "$start | $end | $length | $parentesisContent");
					//1 2 3 4 5 6 7 8 9 10 11 12 13 14 15 16 17 18 19 20 21 22 23 24 25 26 27 28 29 30 31 32 33 34 35 36 37
					//e n u m ( ' r e q  u  e  s  t  '  ,  '  c  a  n  c  e  l ' , '  i  n  v  i  t  e  '  )
				}
				
				//if is a numeric value set the size
				if( is_numeric($parentesisContent) ){
					$size = $parentesisContent;
					if( $size>12 )
						$size = 12;
					
				}else{
					//set the $inputList based on the contents of $parentesisContent
					if( strpos($parentesisContent, ',') !== false ){
						$enumValues = explode(',', $parentesisContent);
						foreach ($enumValues as $v){
							$inputList[] = $v;
						}
					}
				}
				
				if( $type=='text' || $type=='longtext' || $type=='mediumtext' || $type=='tinytext' ){
					$inputType = 'textarea';
					$size = 10;
				
				}else if( $type=='enum' ){
					$inputType = 'select';
					
				}elseif( str_contains($type, 'image') ){
					$inputType = 'file';
					$size = 10;
				}
				
				$fieldsList[$k]['name'] = $column->Field;
				$fieldsList[$k]['type'] = $inputType;
				$fieldsList[$k]['size'] = $size;
				$fieldsList[$k]['values'] = $inputList;
				
			}
			
			if( file_exists($viewPath.$dirName) ){
				if( $this->confirm('Directory "'.$dirName.'" exists, replace its contents? [y|n] n ', false) ){
					$createFile = true;
				}
			}else{
				$createFile = true;
			}
			
			if( $createFile ){
				$templateAdd = file(__DIR__.'/templates/view-add.txt');
				$templateList = file_get_contents(__DIR__.'/templates/view-list.txt');
				
				$fieldsListStr = '';
				$fieldsNameStr = '';
				$fieldsFormStr = '';
				
				sort($fieldsList);
				foreach($fieldsList as $key=>$field){
					
					if( $key>0 ){
						$fieldsListStr .= "\t\t\t\t";
						$fieldsNameStr .= "\t\t\t\t";
						$fieldsFormStr .= "\t\t";
					}
					
					$fieldsListStr .= "<th>{{OrderLink::make('$field[name], '$field[name]')}}</th>".PHP_EOL;
					$fieldsNameStr .= '<td>{{$row->'.$field['name'].'}}</td>'.PHP_EOL;
					
					//make($fieldName, $label, $size=2, $fieldType='text', $fieldParams=array(), $labelParams=array(), $aditionalParams=array()){
					$fieldsFormStr .= "{{BsFormField::make('$field[name], '$field[name]', $field[size], '$field[type]'";
					
					
					if( !empty($field['values']) ){
						$fieldsFormStr .= ", null, null, array('list'=>array(".PHP_EOL;
						
						foreach($field['values'] as $v){
							$fieldsFormStr .= "\t\t\t\t\t$v=>$v,".PHP_EOL;
						}
											
						$fieldsFormStr .= "\t\t\t\t) ".PHP_EOL;
						$fieldsFormStr .= "\t\t)}}".PHP_EOL;
					}else{
						$fieldsFormStr .= ')}}'.PHP_EOL;
					}
					
					
				}
							
				$templateList = str_replace( array('{fieldsList}', '{fieldsName}'), array($fieldsListStr, $fieldsNameStr), $templateList);
				$templateAdd = str_replace( '{fieldsForm}', $fieldsFormStr, $templateAdd);
				
				mkdir($viewPath.$dirName, 0766);
				file_put_contents($viewPath.$dirName.'/list.blade.php', $templateList);
				file_put_contents($viewPath.$dirName.'/add.blade.php', $templateAdd);
				$this->info('created views in "'.$dirName.'"');
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
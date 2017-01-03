<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class BuildViews extends AbstractSkel {
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
	
	public function fire(){
		$this->init('buildView');
	}
	
	public function buildView($table){
		$primary = 'id';
		$timestamps = 'false';
		$hasTimestamps = 0;
		
		$tableName = $table->getName();
		$createFile = false;
		
		$dirName = $this->canUseSegment() ? $this->getSegment() : $table->getNameForSegment();
		
		$viewPath = app_path().'/views/admin/';
		
		$fieldsList = array();
		
		$columns = $table->getFields();
		foreach( $columns as $k=>$column ){
			if( $column->isPrimaryKey() || $column->isMultiKey() || $column->getName()=='updated_at' || $column->getName()=='deleted_at' )
				continue;
			
			$size = 5; //size of the field in columns (for bootstrap)
			$inputType = 'text';
			$type = $column->getType();
			$inputList = $column->getOptions();
			
			if( !$column->hasOptions(true) ){
				$size = $column->getSize();
				if( $size > 10 ){
					while( $size>10 ){
						$size = $size/4;
					}
					$size = floor($size);
				}
			}
			
			$isFile = str_contains($column->getName(), 'image') || str_contains($column->getName(), 'icon') || str_contains($column->getName(), 'file');
			$isEnum = $column->typeOf('enum');
			$isStatus = str_contains($column->getName(), 'status');
			
			if( $column->typeOf(array('text','longtext','mediumtext','tinytext')) ){
				$inputType = 'textarea';
				$size = 10;
			
			}else if( $isStatus || $isEnum ){
				$inputType = 'select';
				
			}else if( $isFile ){
				$inputType = 'file';
				$size = 10;
			}
			
			$fieldInfo = array();		
			$fieldInfo['name'] = $column->getName();
			$fieldInfo['displayName'] = $column->getDisplayName();
			$fieldInfo['type'] = $inputType;
			$fieldInfo['size'] = $size;
			$fieldInfo['values'] = $inputList;
			$fieldInfo['typeCheck'] = (object)array(
				'isFile'=>$isFile,
				'isEnum'=>$isEnum,
				'isStatus'=>$isStatus,
			);
			
			$fieldsList[] = (object)$fieldInfo;
		}
		
		if( file_exists($viewPath.$dirName) ){
			if( $this->confirm('Directory "'.$dirName.'" exists, replace its contents? [y|N] ', false) ){
				$createFile = true;
			}
		}else{
			mkdir($viewPath.$dirName, 0775);
			$createFile = true;
		}
		
		if( $createFile ){
			$templateIndex 	= new SkelTemplate('view-index');
			$templateAdd 	= new SkelTemplate('view-add');
			$templateList 	= new SkelTemplate('view-list');
						
			$fieldsListStr = '';
			$fieldsNameStr = '';
			$fieldsFormStr = '';
			
			//sort($fieldsList);
			foreach($fieldsList as $key=>$field){				
				if( $key>0 ){
					$fieldsListStr .= "\t\t\t\t\t\t";
					$fieldsNameStr .= "\t\t\t\t\t\t";
					$fieldsFormStr .= "";
				}
				
				$inputFactoryCommands = array();
				$fieldsListStr .= "<th>{{OrderLink::make(trans('project.$field->name'), '$field->name')}}</th>".PHP_EOL;
				$fieldsNameStr .= '<td>{{$row->'.$field->name.'}}</td>'.PHP_EOL;
				
				$inputFactoryCommands[] = "InputFactory::create('$field->type')";
				$inputFactoryCommands[] = "->name('$field->name', trans('project.$field->name'))";
				$inputFactoryCommands[] = "->size(".$field->size.")";
				
				if( $field->typeCheck->isStatus ){
					$inputFactoryCommands[] = PHP_EOL."\t\t\t\t\t\t->addListItem('1', 'Ativo')";
					$inputFactoryCommands[] = PHP_EOL."\t\t\t\t\t\t->addListItem('0', 'Inativo')";

				}else if( !empty($field->values) ){
					foreach($field->values as $v){
						$inputFactoryCommands[] = PHP_EOL."\t\t\t\t\t\t->addListItem('$v', '$v')";
					}
				}
				
				$inputFactoryCommands[] = '->build()';
				$fieldsFormStr .= ($key==0?'':"\t\t\t\t\t").'{{'.implode('', $inputFactoryCommands).'}}'.PHP_EOL;
				if( $field->typeCheck->isFile ){
					$fieldsFormStr .= "\t\t@include('admin.partial.image-preview', array('field'=>'".$field->name."') )".PHP_EOL;
				}
			}
						
			$templateList->mark('fieldsList', $fieldsListStr)->mark('fieldsName', $fieldsNameStr);
			$templateAdd->mark('fieldsForm', $fieldsFormStr);
			$templateIndex->mark('dirName', $dirName);
			
			$templateIndex->save($viewPath.$dirName.'/index.blade.php');
			$templateAdd->save($viewPath.$dirName.'/add.blade.php');
			$templateList->save($viewPath.$dirName.'/list.blade.php');
			
			$this->info('created views in "'.$dirName.'"');
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

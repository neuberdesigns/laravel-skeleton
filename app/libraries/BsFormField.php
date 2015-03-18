<?php
class BsFormField {
	public static function make($fieldName, $label, $size=2, $fieldType='text', $fieldParams=array(), $labelParams=array(), $aditionalParams=array()){
		$htmlField = '';
		$type = strtolower($fieldType);
		$fieldAttributes = array('class'=>'form-control');
		$labelAttributes = array('class'=>'col-xs-2 col-md-2 control-label');
		
		if( !is_array($fieldParams) )
			$fieldParams = array();
		
		if( !is_array($labelParams) )
			$labelParams = array();
		
		if( isset($fieldParams['class']) )
			$fieldParams['class'] = $fieldAttributes['class'].' '.$fieldParams['class'];
		
		if( isset($labelParams['class']) )
			$labelParams['class'] = $fieldAttributes['class'].' '.$labelParams['class'];
		
		$finalFieldParams = array_merge($fieldAttributes, $fieldParams);
		$finalLabelParams = array_merge($labelAttributes, $labelParams);
		
		if( $type=='checkbox' ){
			$checked = isset($aditionalParams['checked']) ? $aditionalParams['checked'] : null;
			$value = isset($aditionalParams['value']) ? $aditionalParams['value'] : null;
			
			$htmlField = Form::$type($fieldName, $value, $checked ,$finalFieldParams );
		
		}elseif( $type=='select' ){
			$list = isset($aditionalParams['list']) ? $aditionalParams['list'] : array();
			$selected = isset($aditionalParams['selected']) ? $aditionalParams['selected'] : null;
			
			$htmlField = Form::$type($fieldName, $list, $selected, $finalFieldParams );
			
		}elseif( $type=='password' || $type=='file' ){
			$htmlField = Form::$type($fieldName, $finalFieldParams );
		}else{
			$htmlField = Form::$type($fieldName, null, $finalFieldParams );
		}
		
		
		$html = '';
		$html .= '<div class="form-group">';
		$html .= 	Form::label($fieldName, $label, $finalLabelParams );
		$html .= '	<div class="col-xs-'.$size.' col-md-'.$size.'">';
		$html .= 		$htmlField;
		$html .= '	</div>';
		$html .= '</div>';
		
		return $html;
	}
}
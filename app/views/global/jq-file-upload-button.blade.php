<?php
$uploaderClass 	= '';
$uploaderId 	= 'bt-jq-fileupload';
$uploaderLabel 	= 'Enviar Imagens';
$uploaderUrl 	= '/ajax-jq-uploader';

if( isset($newId) )
	$uploaderId = $newId;

if( isset($newLabel) )
	$uploaderLabel = $newLabel;

if( isset($newClass) )
	$uploaderClass = $newClass;

if( isset($newUrl) )
	$uploaderUrl = $newUrl;
?>

<span class="glyphicon glyphicon-upload"></span>
<span>{{$uploaderLabel}}</span>
<input id="{{$uploaderId}}" type="file" name="files[]" multiple=""
	data-url="{{URL::to('/admin/'.$controllerSegment.$uploaderUrl)}}"
	data-limitMultiFileUploads="20"
/>

@section('styles')
	{{HTML::style('scripts/jq-file-upload/jquery.fileupload.css')}}
@append

@section('scripts')
	{{HTML::script('scripts/jq-file-upload/jquery.iframe-transport.js')}}
	{{HTML::script('scripts/jq-file-upload/vendor/jquery.ui.widget.js')}}
	{{HTML::script('scripts/jq-file-upload/jquery.fileupload.js')}}
	{{HTML::script('scripts/jq-file-upload/uploader.js')}}
@append

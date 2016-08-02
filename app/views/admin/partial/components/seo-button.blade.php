@if( isset($model) && $model->id > 0 )
	<button type="button" class="btn btn-primary btn-lg bt-seo-load" data-id="{{$model->id}}">
		SEO
		<span class="seo-load-loader"></span>
	</button>
@endif

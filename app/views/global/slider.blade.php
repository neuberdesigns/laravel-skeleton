@if( !$slider->isEmpty() )
	<div id="carousel-example-generic" class="carousel slide margin-bottom" data-ride="carousel">
		<!-- Indicators -->
		@if( isset($controls) && $controls==true )
			<ol class="carousel-indicators">
				@foreach( $slider as $i=>$item )
					<li data-target="#carousel-example-generic" data-slide-to="{{$i}}" class="{{$i==0 ? 'active' : ''}}"></li>
				@endforeach
		 	</ol>
		@endif
		
	
		<!-- Wrapper for slides -->
		<div class="carousel-inner">
			@foreach( $slider as $i=>$item )
				@include('global.slider-item', array('item'=>$item))
			@endforeach
		</div><!-- /.carousel-inner -->
	
		@if( isset($controls) && $controls==true )
			<!-- Controls -->
			<a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
				<span class="glyphicon glyphicon-chevron-left"></span>
			</a>
			<a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
				<span class="glyphicon glyphicon-chevron-right"></span>
			</a>
		@endif
	</div><!-- /.carousel -->
@endif
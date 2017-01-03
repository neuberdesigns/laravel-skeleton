	<!-- Default box -->
	<div class="box">
		<div class="box-header with-border">
			<h3 class="box-title">{{trans('admin.new')}} {{$controllerTitle}}</h3>

			<div class="box-tools pull-right">
				<button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
					<i class="fa fa-minus"></i>
				</button>
			</div>
		</div>
		
		{{Form::model($model, array('url'=>BaseAdminController::urlToSave($controllerSegment, $model?$model->getKey():null), 'files'=>false) )}}
			<!-- /.box-header -->
			<div class="box-body">
				<div class="row">
					{{InputFactory::create('text')->name('name', trans('project.name'))->size(4)->build()}}
					{{InputFactory::create('text')->name('email', trans('project.email'))->size(4)->build()}}
					{{InputFactory::create('text')->name('password', trans('project.password'))->size(3)->build()}}
				</div>
			</div>
			<!-- /.box-body -->
			
			<div class="box-footer">
				{{Form::submit(trans('admin.save'), array('class'=>'btn btn-primary pull-right') )}}
			</div>
			<!-- /.box-footer-->
		{{Form::close()}}
	</div>
	<!-- /.box -->


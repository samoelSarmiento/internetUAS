@extends(Auth::user() ? 'app' : 'appPublic')

@section('content')

<div class="row">
	<div class="col-md-12">
		<div class="page-title">
	        <div class="title_left">
	            <h3>Investigador</h3>
	        </div>
	    </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
		<div class="panel panel-info">
			<div class="panel-heading">
		    	<h3 class="panel-title">Información</h3>
			</div>
		  	<div class="panel-body">
		  		<div class="col-md-6">
			  		<div class="form-horizontal">
			    		<div class="form-group">
			    			{{Form::label('Nombre',null,['class'=>'control-label col-md-4 col-sm-3 col-xs-12'])}}
			    			<div class="col-md-8 col-xs-12">
			    				@if(Auth::user())
			    				{{Form::text('nombre',$investigador->nombre,['class'=>'form-control', 'readonly', 'maxlength' => 10])}}
			    				@else
			    				<div class="form-control no-border">
			    					{{$investigador->nombre}}
			    				</div>
			    				@endif
			    			</div>
			    		</div>

			    		<div class="form-group">
			    			{{Form::label('Apellido Paterno',null,['class'=>'control-label col-md-4 col-sm-3 col-xs-12'])}}
			    			<div class="col-md-8 col-xs-12">
			    				@if(Auth::user())
			    				{{Form::text('apellido_paterno',$investigador->ape_paterno,['class'=>'form-control', 'readonly'])}}
			    				@else
			    				<div class="form-control no-border">
			    					{{$investigador->ape_paterno}}
			    				</div>
			    				@endif
			    			</div>
			    		</div>

			    		<div class="form-group">
			    			{{Form::label('Apellido Materno',null,['class'=>'control-label col-md-4 col-sm-3 col-xs-12'])}}
			    			<div class="col-md-8 col-xs-12">
			    				@if(Auth::user())
			    				{{Form::text('apellido_materno',$investigador->ape_materno,['class'=>'form-control', 'readonly'])}}
			    				@else
			    				<div class="form-control no-border">
			    					{{$investigador->ape_materno}}
			    				</div>
			    				@endif
			    			</div>
			    		</div>

			    		<div class="form-group">
			    			{{Form::label('Correo',null,['class'=>'control-label col-md-4 col-sm-3 col-xs-12'])}}
			    			<div class="col-md-8 col-xs-12">
			    				@if(Auth::user())
			    				{{Form::email('correo',$investigador->correo,['class'=>'form-control', 'readonly'])}}
			    				@else
			    				<div class="form-control no-border">
			    					{{$investigador->correo}}
			    				</div>
			    				@endif
			    			</div>
			    		</div>

			    		<div class="form-group">
			    			{{Form::label('Celular',null,['class'=>'control-label col-md-4 col-sm-3 col-xs-12'])}}
			    			<div class="col-md-8 col-xs-12">
			    				@if(Auth::user())
			    				{{Form::number('celular',$investigador->celular,['class'=>'form-control', 'readonly'])}}
			    				@else
			    				<div class="form-control no-border">
			    					{{$investigador->celular}}
			    				</div>
			    				@endif
			    			</div>
			    		</div>

			    		<div class="form-group">
			    			{{Form::label('Especialidad',null,['class'=>'control-label col-md-4 col-sm-3 col-xs-12'])}}
			    			<div class="col-md-8 col-xs-12">
			    				@if(Auth::user())
			    				{{Form::text('especialidad', $investigador->faculty->Nombre, ['class' => 'form-control', 'readonly'])}}
			    				@else
			    				<div class="form-control no-border">
			    					{{$investigador->faculty->Nombre}}
			    				</div>
			    				@endif		    				
			    			</div>
			    		</div>

			    		<div class="form-group">
			    			{{Form::label('Área',null,['class'=>'control-label col-md-4 col-sm-3 col-xs-12'])}}
			    			<div class="col-md-8 col-xs-12">
			    				@if(Auth::user())
			    				{{Form::text('area', $investigador->area->nombre, ['class' => 'form-control', 'readonly'])}}
			    				@else
			    				<div class="form-control no-border">
			    					{{$investigador->area->nombre}}
			    				</div>
			    				@endif
			    			</div>
			    		</div>
			    		<div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12">
								@if(Auth::user() && (Auth::user()->IdUsuario == $investigador->id_usuario || Auth::user()->IdPerfil == Config::get('constants.admin')))
								<a class="btn btn-success pull-right" href="{{ route('investigador.edit',$investigador->id) }}">Editar</a>
								@endif
								<a class="btn btn-default pull-right" href="{{ route('investigador.index') }}">Regresar</a>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					{{Html::image(asset('images/researcher.jpg'), null, ['class'=>'img-thumbnail'])}}
				</div>
		  	</div>

		  	<div class="panel-heading">
		    	<h3 class="panel-title">Grupos de investigación</h3>
			</div>
		  	<div class="panel-body">
				<table class="table table-striped responsive-utilities jambo_table bulk_action"> 
					<thead> 
						<tr class="headings"> 
							<th>Nombre</th> 
							<th>Especialidad</th> 
							<th>Acciones</th> 
						</tr> 
					</thead> 
					<tbody> 
						@foreach($investigador->groups as $group)
						<tr> 
							<td>{{$group->nombre}}</td> 
							<td>{{$group->descripcion}}</td> 
                            <td>
                                <a href="{{route('grupo.show', $group->id)}}" class="btn btn-primary btn-xs" title="Visualizar"><i class="fa fa-search"></i></a>
                            </td>
						</tr> 
						@endforeach
					</tbody> 
				</table>		  	
		  	</div>

		  	<div class="panel-heading">
		    	<h3 class="panel-title">Proyectos</h3>
			</div>
		  	<div class="panel-body">
		  		<div class="table-responsive">
					<table class="table table-striped responsive-utilities jambo_table bulk_action"> 
						<thead> 
							<tr class="headings"> 
								<th>Nombre</th> 
								<th>Fecha Inicio</th> 
								<th>Fecha Fin</th> 
								<th>Grupo</th> 
								<th>Especialidad</th> 
								<th>Acciones</th>
							</tr> 
						</thead> 
						<tbody> 
							@foreach($investigador->projects as $proyecto)
							<tr> 
								<td>{{$proyecto->nombre}}</td> 
								<td>{{$proyecto->fecha_ini}}</td>
								<td>{{$proyecto->fecha_fin}}</td>
								<td>{{$proyecto->group->nombre}}</td>
								<td>{{$proyecto->group->faculty->Nombre}}</td> 
	                            <td>
	                                <a href="{{route('proyecto.show', $proyecto->id)}}" class="btn btn-primary btn-xs" title="Visualizar"><i class="fa fa-search"></i></a>
	                            </td>
							</tr> 
							@endforeach
						</tbody> 
					</table>		  	
				</div>
		  	</div>
		</div>
    </div>
</div>

@endsection
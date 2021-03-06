@extends('app')
@section('content')

<div class="row">
	<div class="col-md-12">
		<div class="page-title">
			<div class="title_left">
				<h3>Supervisores</h3>
			</div>
		</div>
    </div>
</div>

<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">

        <div class="x_content">
            <div class="clearfix"></div>
            <h4>Agregar supervisores y profesores a cursos PSP</h4>
            <div class="form-group">
                <div class="col-md-4">
                    {{Form::label('Seleccionar curso',null,['class'=>'control-label col-md-4 col-sm-3 col-xs-12'])}}
                </div>    
                <div class="col-md-4">
                    {{ Form::select('IdProceso', $procesos, null, ['class'=>'form-control', 'id'=>'sel']) }}
                </div>    
                <div class="col-md-4">
                    <a id="ruta" href="#" class="btn btn-success"><i class="fa fa-plus">Ver</i></a>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>

        <div class="col-md-12">
            <hr>
        </div>
        <div class="x_content">
            
            <div class="clearfix"></div>
            <h4>Crear supervisores</h4>
            <div class="row">
                
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <a href="#filter" class="btn btn-warning pull-left"><i class="fa fa-filter"></i> Filtrar</a>
                    <a href="{{ route('supervisor.create') }}" class="btn btn-success pull-right"><i class="fa fa-plus"></i>Nuevo Supervisor</a>
                </div>
            </div>
            <div class="clearfix"></div>

            <table class="table table-striped responsive-utilities jambo_table bulk_action">
                <thead>
                <tr class="headings">
                    <th class="column-title">Código</th>
                    <th class="column-title">Nombre</th>
                    <th class="column-title">Apellido</th>
                    <th class="column-title">Correo</th>
                    <th class="column-title last">Acciones</th>
                </tr>
                </thead>
                <tbody>
                	@foreach($supervisores as $supervisor)      	
                	<tr class="even pointer">
                        <td >{{$supervisor->codigo_trabajador}}</td>
                        <td >{{$supervisor->nombres}}</td>
                        <td >{{$supervisor->apellido_paterno}}</td>
                        <td >{{$supervisor->correo}}</td>
                        <td >
                    		<a href="{{ route('supervisor.show', $supervisor->id) }}"  class="btn btn-primary btn-xs" ><i class="fa fa-search"></i></a>
                            <a href="{{ route('supervisor.edit', $supervisor->id) }}" class="btn btn-primary btn-xs" title="Editar"><i class="fa fa-pencil"></i></a>
                            <!--<a class="btn btn-danger btn-xs delete-teacher" data-toggle="modal" data-target=".bs-example-modal-sm" title="Eliminar"><i class="fa fa-remove"></i></a>-->
                            <a href="" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#{{$supervisor->id}}" title="Eliminar"><i class="fa fa-remove"></i></a>
                        </td>
                    </tr>
                    @include('modals.delete', ['id'=> $supervisor->id, 'message' => '¿Esta seguro que desea eliminar este supervisor?', 'route' => route('supervisor.delete', $supervisor->id)])
                    @endforeach
                </tbody>
            </table>
            {{$supervisores->links()}}
        </div>

    </div>
</div>

<script src="{{ URL::asset('js/myvalidations/pspParticipant.js')}}"></script>
@include('psp.supervisor.filter_supervisor', ['title' => 'Filtrar', 'route' => 'supervisor.index'])    
@endsection
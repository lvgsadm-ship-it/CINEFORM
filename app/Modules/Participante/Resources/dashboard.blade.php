@extends('kaiadmin::layouts.master')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">🏠 Dashboard Participante</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ $stats['solicitudes_talleres'] }}</h3>
                                <p>Solicitudes Talleres</p>
                            </div>
                            <a href="{{ route('participante.solicitar-taller') }}" class="small-box-footer">Ver <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ $stats['cursos_inscrito'] }}</h3>
                                <p>Cursos Inscrito</p>
                            </div>
                            <a href="{{ route('participante.cursos.oferta') }}" class="small-box-footer">Ver <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{ $stats['evaluaciones_pendientes'] }}</h3>
                                <p>Eval. Pendientes</p>
                            </div>
                            <a href="{{ route('participante.evaluaciones') }}" class="small-box-footer">Ver <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>{{ $stats['certificados'] }}</h3>
                                <p>Certificados</p>
                            </div>
                            <a href="{{ route('participante.certificados') }}" class="small-box-footer">Ver <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

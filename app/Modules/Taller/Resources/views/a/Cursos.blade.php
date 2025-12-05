@extends('layouts.kaiadmin-menu')

@section('title', 'Todos los Cursos')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-gradient-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-chalkboard-teacher me-2"></i>Todos los Cursos</h5>
                        <span class="badge bg-white text-primary">{{ $cursos->total() }} cursos disponibles</span>
                    </div>
                </div>
                <div class="card-body">    
                    @if($cursos->isEmpty())
                        <div class="text-center p-5">
                            <div class="mb-3">
                                <i class="fas fa-chalkboard-teacher fa-4x text-muted"></i>
                            </div>
                            <h5 class="text-muted mb-3">No hay cursos disponibles</h5>
                        </div>
                    @else
                        <div class="row">
                            @php
                                $hasActiveCourses = false;
                            @endphp
                            
                            @foreach($cursos as $curso)
                                @if($curso->estado_actual->id_estado == 6)
                                    @php
                                        $hasActiveCourses = true;
                                        $modalidad = $curso->modalidad->nombre_modalidad ?? 'No especificada';
                                        $modalidadIcon = $modalidad === 'Presencial' ? 'fa-building' : 'fa-laptop';
                                    @endphp
                                    <div class="col-md-6 col-lg-4 mb-4">
                                        <div class="card h-100 border-0 shadow-sm hover-shadow transition-all">
                                            <div class="position-relative">
                                                @if($curso->imagen)
                                                    <img src="{{ asset($curso->imagen) }}" class="card-img-top" alt="{{ $curso->nombre }}" style="height: 180px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 180px;">
                                                        <i class="fas fa-image fa-4x text-muted"></i>
                                                    </div>
                                                @endif
                                                <div class="position-absolute top-0 end-0 m-2">
                                                    <span class="badge bg-primary">
                                                        <i class="fas {{ $modalidadIcon }} me-1"></i>
                                                        {{ $modalidad }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <h5 class="card-title text-truncate" title="{{ $curso->nombre }}">
                                                    {{ $curso->nombre }}
                                                </h5>

                                                <p class="card-text text-muted">
                                                    {{ Str::limit($curso->descripcion ?? 'Sin descripción', 120) }}
                                                    @if(isset($curso->descripcion) && strlen($curso->descripcion) > 120)
                                                        <a href="#" class="text-primary" data-bs-toggle="tooltip" title="{{ $curso->descripcion }}">
                                                            <i class="fas fa-info-circle"></i>
                                                        </a>
                                                    @endif
                                                </p>
                                                <div class="d-flex justify-content-between align-items-center mt-3">
                                                    <div>
                                                        <small class="text-muted me-3">
                                                            <i class="fas fa-book me-1"></i>
                                                            {{ $curso->contenidos_count ?? 0 }} contenidos
                                                        </small>
                                                        <small class="text-muted">
                                                            <i class="fas fa-users me-1"></i>
                                                            {{ $curso->inscripciones_count ?? 0 }} participantes
                                                        </small>
                                                    </div>
                                                    <div class="btn-group">
                                                        <a href="{{ route('taller.cursos.show', $curso->id_curso) }}" class="btn btn-success" data-bs-toggle="tooltip" >
                                                            <i class="fas fa-eye">Detalles </i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                            
                            @if(!$hasActiveCourses)
                                <div class="col-12 text-center py-5">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        No hay cursos disponibles para inscripción en este momento.
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                @if($cursos->hasPages())
                    <div class="card-footer bg-transparent border-top">
                        <div class="d-flex justify-content-center">
                            {{ $cursos->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
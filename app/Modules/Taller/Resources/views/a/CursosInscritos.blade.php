@extends('layouts.kaiadmin-menu')

@section('title', 'Mis Cursos Inscritos')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-gradient-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-book-reader me-2"></i>Mis Cursos Inscritos</h5>
                        @if($cursosInscritos->count() > 0)
                            <span class="badge bg-white text-primary">{{ $cursosInscritos->count() }} cursos</span>
                        @endif
                    </div>
                </div>

                <div class="card-body">
                    @if($cursosInscritos->count() > 0)
                        <div class="row">
                            @foreach($cursosInscritos as $curso)
                                @php
                                    $modalidad = $curso->modalidad->nombre_modalidad ?? 'No especificada';
                                    $modalidadIcon = strtolower($modalidad) === 'presencial' ? 'fa-building' : 'fa-laptop';
                                @endphp
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card h-100 border-0 shadow-sm hover-shadow transition-all">
                                        <div class="position-relative">
                                            @if($curso->imagen)
                                                <img src="{{ asset($curso->imagen) }}" class="card-img-top" alt="{{ $curso->nombre }}" style="height: 180px; object-fit: cover;">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 180px;">
                                                    <i class="fas fa-book-open fa-4x text-muted"></i>
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
                                            <h5 class="card-title">{{ $curso->nombre }}</h5>
                                            <p class="card-text text-muted">
                                                {{ Str::limit($curso->descripcion ?? 'Sin descripción', 100) }}
                                            </p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <small class="text-muted me-3">
                                                        <i class="far fa-calendar-alt me-1"></i>
                                                        {{ \Carbon\Carbon::parse($curso->fecha_inicio)->format('d/m/Y') }}
                                                    </small>
                                                </div>
                                                <a href="{{ route('taller.cursos.show', $curso->id_curso) }}" 
                                                   class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye me-1"></i> Ver Curso
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center p-5">
                            <div class="mb-3">
                                <i class="fas fa-book-reader fa-4x text-muted"></i>
                            </div>
                            <h5 class="text-muted mb-3">No estás inscrito en ningún curso</h5>
                            <p class="text-muted mb-4">Explora nuestros cursos disponibles y comienza a aprender algo nuevo hoy mismo.</p>
                            <a href="{{ route('taller.cursos.index') }}" class="btn btn-primary">
                                <i class="fas fa-search me-1"></i> Explorar Cursos
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layouts.kaiadmin-menu')

@section('title', 'Mis Cursos Asignados')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-gradient-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-chalkboard-teacher me-2"></i>Mis Cursos Asignados</h5>
                        <span class="badge bg-white text-primary">{{ $cursos->count() }} cursos asignados</span>
                    </div>
                </div>
                <div class="card-body">    
                    @if($cursos->isEmpty())
                        <div class="text-center p-5">
                            <div class="mb-3">
                                <i class="fas fa-chalkboard-teacher fa-4x text-muted"></i>
                            </div>
                            <h5 class="text-muted mb-3">No tienes cursos asignados</h5>
                        </div>
                    @else 
                        <div class="row">
                            @foreach($cursos as $curso)
                                @php
                                    $modalidad = $curso->modalidad->nombre_modalidad ?? 'No especificada';
                                    $modalidadIcon = $modalidad === 'Presencial' ? 'fa-building' : 'fa-laptop';
                                    $estado = $curso->estado_id;
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
                                            
                                                @if($curso->estado_actual)
                                                    @if($curso->estado_actual->id_estado == 1)
                                                        <span class="badge bg-success">Por Aceptar</span>
                                                    @elseif($curso->estado_actual->id_estado == 2)
                                                        <span class="badge bg-danger">Rechazado</span>
                                                    @elseif($curso->estado_actual->id_estado == 3)
                                                        <span class="badge bg-warning">Declinado</span>
                                                    @elseif($curso->estado_actual->id_estado == 4)
                                                        <span class="badge bg-warning">En edición</span>
                                                    @elseif($curso->estado_actual->id_estado == 5)
                                                        <span class="badge bg-warning">En Evaluación</span>
                                                    @elseif($curso->estado_actual->id_estado == 6)
                                                        <span class="badge bg-success">Abierto a inscripciones</span>
                                                    @elseif($curso->estado_actual->id_estado == 7)
                                                        <span class="badge bg-success">En curso</span>
                                                    @elseif($curso->estado_actual->id_estado == 8)
                                                        <span class="badge bg-danger">Finalizado</span>
                                                    @elseif($curso->estado_actual->id_estado == 9)
                                                        <span class="badge bg-danger">Cerrado</span>
                                                    @endif
                                                @else
                                                    <span class="badge bg-secondary">Sin estado</span>
                                                @endif
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
                                                        {{ $curso->total_contenidos ?? 0 }} contenidos
                                                    </small>
                                                    <small class="text-muted">
                                                        <i class="fas fa-users me-1"></i>
                                                        {{ $curso->inscripciones_count ?? 0 }} participantes
                                                    </small>
                                                </div>
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('taller.cursos.show', $curso->id_curso) }}" class="btn btn-secondary btn-sm" title="Ver detalles">
                                                    <i class="fas fa-info-circle"></i> Detalles
                                                    </a>
                                                </div>
                                            </div>
                                        </div>  
                                    </div>
                                </div>
                            @endforeach
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

@push('scripts')
<script>
    // Inicializar tooltips de Bootstrap
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush

@push('styles')
<style>
   /* .BtnCurso{
        margin-left: 5px;
    }*/
</style>
@endpush

@foreach($cursos as $curso)
    @if($curso->estado_actual->id_estado == 1) {{-- Solo mostrar para cursos en estado Por Aprobar --}}
    <!-- Modal para Aceptar Curso -->
    <div class="modal fade" id="aceptarcurso{{ $curso->id_curso }}" tabindex="-1" aria-labelledby="aceptarCursoLabel{{ $curso->id_curso }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
          <form action="{{ route('taller.cursos.aceptar', $curso->id_curso) }}" method="POST">
    @csrf
    <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="aceptarCursoLabel{{ $curso->id_curso }}">
            <i class="fas fa-check-circle me-2"></i>Confirmar Aceptación
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
    </div>
    <div class="modal-body">
        <p>¿Estás seguro de que deseas aceptar el curso <strong>{{ $curso->nombre }}</strong>?</p>
       
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="fas fa-times me-1"></i> Cancelar
        </button>
        <button type="submit" class="btn btn-success">
            <i class="fas fa-check me-1"></i> Sí, Aceptar Curso
        </button>
    </div>
</form>
            </div>
        </div>
    </div>
    @elseif($curso->estado_actual->id_estado == 3) {{-- Solo mostrar para cursos en estado Rechazado --}}
    <!-- Modal para Rechazar Curso -->
    <div class="modal fade" id="motivoRechazo{{ $curso->id_curso }}" tabindex="-1" aria-labelledby="motivoRechazoLabel{{ $curso->id_curso }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
    
                    @csrf
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="motivoRechazoLabel{{ $curso->id_curso }}">
                            <i class="fas fa-times-circle me-2"></i>Motivo de Rechazo
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>{{ $curso->estado_actual->pivot->motivo ?? 'No se especificó un motivo' }}</strong></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i> Cerrar
                        </button>
                    </div>
                
            </div>
        </div>
    </div>
    @endif
@endforeach

@endsection
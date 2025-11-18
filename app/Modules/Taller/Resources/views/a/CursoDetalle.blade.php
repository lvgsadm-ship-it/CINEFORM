@extends('layouts.kaiadmin-menu')

@section('title', 'Detalles del Curso')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Columna principal -->
        <div class="col-lg-8">
            <!-- Tarjeta de información principal -->
            <div class="card shadow-sm mb-4 border-0">
                <div class="card-header bg-gradient-primary text-white">
                    <h2 class="h4 mb-0">{{ $curso->nombre }}</h2>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h4 class="text-primary">Descripción</h4>
                        <p class="lead">{{ $curso->descripcion ?? 'Sin descripción disponible' }}</p>
                    </div>

                    <!-- Información del curso -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded-circle p-3 me-3">
                                    <i class="fas fa-clock fa-2x text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Duración</h6>
                                    <p class="mb-0 fw-bold">{{ $curso->duracion }} días</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded-circle p-3 me-3">
                                    <i class="fas fa-hourglass-half fa-2x text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Horas Totales</h6>
                                    <p class="mb-0 fw-bold">{{ $curso->horas }} horas</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded-circle p-3 me-3">
                                    <i class="fas fa-user-tie fa-2x text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Modalidad</h6>
                                    <p class="mb-0 fw-bold">{{ $curso->modalidad->nombre_modalidad ?? 'No especificada' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección de Contenido del Curso -->
            <div class="card shadow-sm mb-4 border-0">
                <div class="card-header bg-white border-bottom">
                    <h4 class="mb-0">Contenido del Curso</h4>
                </div>
                <div class="card-body">
                    @if($curso->contenidos && $curso->contenidos->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($curso->contenidos as $contenido)
                            <div class="list-group-item border-0 px-0 py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $contenido->titulo }}</h6>
                                        <p class="mb-0 text-muted small">{{ $contenido->descripcion }}</p>
                                    </div>
                                    <span class="badge bg-primary rounded-pill">{{ $contenido->tipo_contenido }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Aún no hay contenido disponible para este curso.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Tarjeta del Instructor -->
            <div class="card shadow-sm mb-4 border-0">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Instructor</h5>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <img src="{{ asset('assets/img/avatar.png') }}" 
                             alt="Instructor" 
                             class="rounded-circle img-thumbnail" 
                             style="width: 120px; height: 120px; object-fit: cover;">
                    </div>
                    <h5 class="mb-1">{{ $curso->persona->nombre_completo ?? 'Instructor no asignado' }}</h5>
                    <p class="text-muted mb-3">Instructor</p>
                    <div class="d-flex justify-content-center gap-2">
                        <a href="#" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-envelope me-1"></i> Contactar
                        </a>
                    </div>
                </div>
            </div>

            <!-- Información Adicional -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Información del Curso</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span><i class="fas fa-calendar-alt text-muted me-2"></i> Fecha de inicio</span>
                            <span class="fw-bold">{{ $curso->fecha_inicio ? \Carbon\Carbon::parse($curso->fecha_inicio)->format('d/m/Y') : 'Por definir' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span><i class="fas fa-calendar-check text-muted me-2"></i> Fecha de fin</span>
                            <span class="fw-bold">{{ $curso->fecha_fin ? \Carbon\Carbon::parse($curso->fecha_fin)->format('d/m/Y') : 'Por definir' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span><i class="fas fa-users text-muted me-2"></i> Cupos</span>
                            <span class="badge bg-primary rounded-pill">25/30</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span><i class="fas fa-tag text-muted me-2"></i> Categoría</span>
                            <span class="fw-bold">{{ $curso->categoria ?? 'General' }}</span>
                        </li>
                    </ul>
                </div>
                <div class="card-footer bg-white border-top-0">
                    <a href="#" class="btn btn-primary w-100">
                        <i class="fas fa-pencil-alt me-2"></i> Inscribirse al Curso
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .card {
        border-radius: 10px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .bg-gradient-primary {
        background: linear-gradient(87deg, #5e72e4 0, #825ee4 100%) !important;
    }
    .list-group-item {
        border-left: 0;
        border-right: 0;
    }
    .list-group-item:first-child {
        border-top: 0;
    }
    .list-group-item:last-child {
        border-bottom: 0;
    }
</style>
@endpush

@push('scripts')
<script>
    // Aquí puedes agregar cualquier funcionalidad JavaScript necesaria
</script>
@endpush

@endsection
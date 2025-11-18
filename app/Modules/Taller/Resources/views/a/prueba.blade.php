@extends('layouts.kaiadmin-menu')

@section('title', 'Mis Cursos')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-gradient-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Mis Cursos</h5>
                        <span class="badge bg-white text-primary">{{ $cursos->total() }} cursos encontrados</span>
                    </div>
                </div>
                <div class="card-body">
                    @if($cursos->isEmpty())
                        <div class="text-center p-5">
                            <div class="mb-3">
                                <i class="fas fa-book-open fa-4x text-muted"></i>
                            </div>
                            <h5 class="text-muted mb-3">No tienes cursos registrados</h5>
                            <a href="#" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Explorar Cursos
                            </a>
                        </div>
                    @else
                        <div class="row">
                            @foreach($cursos as $curso)
                                @php
                                    $modalidad = $curso->modalidad->nombre_modalidad ?? 'No especificada';
                                    $badgeClass = [
                                        'Presencial' => 'bg-info',
                                        'Online' => 'bg-success',
                                        'Híbrido' => 'bg-warning',
                                    ][$modalidad] ?? 'bg-secondary';
                                    
                                    $modalidadIcon = [
                                        'Presencial' => 'fa-building',
                                        'Online' => 'fa-laptop',
                                        'Híbrido' => 'fa-random',
                                    ][$modalidad] ?? 'fa-book';
                                @endphp
                                
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card h-100 border-0 shadow-sm hover-lift">
                                        <div class="card-header bg-light">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h5 class="card-title mb-0">{{ $curso->nombre }}</h5>
                                                <span class="badge {{ $badgeClass }} rounded-pill px-3">
                                                    <i class="fas {{ $modalidadIcon }} me-1"></i>
                                                    {{ $modalidad }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <p class="card-text text-muted">
                                                {{ Str::limit($curso->descripcion ?? 'Sin descripción', 120) }}
                                                @if(isset($curso->descripcion) && strlen($curso->descripcion) > 120)
                                                    <a href="#" class="text-primary" data-bs-toggle="tooltip" title="{{ $curso->descripcion }}">
                                                        <i class="fas fa-info-circle"></i>
                                                    </a>
                                                @endif
                                            </p>
                                            <div class="d-flex justify-content-between align-items-center mt-3">
                                                <small class="text-muted">
                                                    <i class="far fa-calendar-alt me-1"></i>
                                                    {{ $curso->creado_en ? \Carbon\Carbon::parse($curso->creado_en)->format('d/m/Y') : 'Fecha no disponible' }}
                                                </small>
                                                <div class="btn-group">
                                                    
                                                    <button href="{{ route('taller.curso.show', $curso->id_curso) }}" class="btn btn-success" data-bs-toggle="tooltip" title="Acceder al curso">
                                                        <i class="fas fa-external-link-alt"></i>
                                                    </button>
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

@push('styles')
<style>
    .card {
        border-radius: 10px;
        transition: all 0.3s ease;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
    }
    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        padding: 1.25rem 1.5rem;
    }
    .card-title {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0;
    }
    .card-text {
        color: #4a5568;
        line-height: 1.6;
    }
    .badge {
        font-weight: 500;
        letter-spacing: 0.3px;
        font-size: 0.75rem;
        padding: 0.4em 0.8em;
    }
    .btn-group .btn {
        padding: 0.25rem 0.5rem;
    }
    .bg-gradient-primary {
        background: linear-gradient(87deg, #5e72e4 0, #825ee4 100%) !important;
    }
    .hover-lift {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1.5rem 0.5rem rgba(0, 0, 0, 0.075) !important;
    }
    .pagination {
        margin-bottom: 0;
    }
</style>
@endpush

@push('scripts')
<script>
    // Inicializar tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush
@endsection
@extends('layouts.kaiadmin-menu')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-white border-0 shadow-sm overflow-hidden" style="border-radius: 1rem;">
                    <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div>
                            <div class="d-flex align-items-center mb-1">
                                <span class="badge bg-primary-soft text-primary me-2 px-3 py-1 rounded-pill"
                                    style="font-weight: 600; font-size: 0.85rem;">
                                    <i class="fas fa-microscope me-1"></i> Evaluación
                                </span>
                                <small class="text-muted text-uppercase fw-bold"
                                    style="letter-spacing: 1px; font-size: 0.75rem;">{{ $curso->nombre_curso }}</small>
                            </div>
                            <h3 class="fw-bold text-dark mb-0">{{ $contenido->titulo }}</h3>
                        </div>
                        <a href="{{ route('taller.cursos.contenido', ['curso' => $curso->id_curso, 'contenido_id' => $contenido->id_contenido_curso]) }}"
                            class="btn btn-outline-light text-dark border-0 bg-gray-100 hover-lift">
                            <i class="fas fa-arrow-left me-2"></i> Volver al contenido
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-3 d-flex align-items-center mb-4 fade show" role="alert">
                <div class="icon-shape icon-sm bg-success-light text-success rounded-circle me-3">
                    <i class="fas fa-check"></i>
                </div>
                <div>
                    <h6 class="mb-0 fw-bold">¡Éxito!</h6>
                    <small>{{ session('success') }}</small>
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger border-0 shadow-sm rounded-3 d-flex align-items-center mb-4 fade show" role="alert">
                <div class="icon-shape icon-sm bg-danger-light text-danger rounded-circle me-3">
                    <i class="fas fa-exclamation"></i>
                </div>
                <div>
                    <h6 class="mb-0 fw-bold">Error</h6>
                    <small>{{ session('error') }}</small>
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 1.5rem;">
                    <!-- Toolbar & Filters -->
                    <div class="card-header bg-white border-0 py-4 px-4 pb-0" style="border-radius: 1.5rem 1.5rem 0 0;">
                        <div class="row g-3 align-items-center justify-content-between">
                            <div class="col-12 col-md-6">
                                <h5 class="mb-1 fw-bold text-dark">Estudiantes Inscritos</h5>
                                <p class="text-muted small mb-0">Gestiona las calificaciones y retroalimentación.</p>
                            </div>
                            <div class="col-12 col-md-auto d-flex gap-3 align-items-center">
                                <span
                                    class="badge bg-warning-soft text-warning px-3 py-2 rounded-pill border border-warning border-opacity-25">
                                    <i class="fas fa-weight-hanging me-1"></i> Ponderación:
                                    <strong>{{ $contenido->ponderacion }}%</strong>
                                </span>
                            </div>
                        </div>

                        <!-- Search Bar -->
                        <div class="mt-4 mb-2">
                            <form
                                action="{{ route('taller.calificaciones.index', ['curso' => $curso->id_curso, 'contenido' => $contenido->id_contenido_curso]) }}"
                                method="GET">
                                <div
                                    class="input-group input-group-lg shadow-none border bg-light rounded-pill overflow-hidden">
                                    <span class="input-group-text border-0 bg-transparent ps-4 text-muted"><i
                                            class="fas fa-search"></i></span>
                                    <input type="text" name="search"
                                        class="form-control border-0 bg-transparent shadow-none ps-2"
                                        placeholder="Buscar por nombre, apellido o cédula..."
                                        value="{{ request('search') }}">
                                    @if(request('search'))
                                        <a href="{{ route('taller.calificaciones.index', ['curso' => $curso->id_curso, 'contenido' => $contenido->id_contenido_curso]) }}"
                                            class="btn btn-link text-muted pe-4 text-decoration-none">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    @else
                                        <button class="btn btn-primary px-4 rounded-pill m-1" type="submit">Buscar</button>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card-body px-0 pt-2">
                        <form
                            action="{{ route('taller.calificaciones.store', ['curso' => $curso->id_curso, 'contenido' => $contenido->id_contenido_curso]) }}"
                            method="POST">
                            @csrf

                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0"
                                    style="border-collapse: separate; border-spacing: 0;">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="ps-4 text-uppercase text-muted small fw-bold py-3"
                                                style="letter-spacing: 0.5px;">Estudiante</th>
                                            <th class="text-uppercase text-muted small fw-bold py-3 text-center"
                                                style="width: 180px;">Calificación</th>
                                            <th class="text-uppercase text-muted small fw-bold py-3">Feedback</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($estudiantes as $estudiante)
                                            <tr class="align-middle position-relative transition-hover">
                                                <td class="ps-4 py-3">
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar avatar-md me-3 bg-gradient-primary text-white rounded-circle shadow-sm d-flex align-items-center justify-content-center fw-bold"
                                                            style="width: 45px; height: 45px; font-size: 1.1rem;">
                                                            {{ substr($estudiante->primer_nombre, 0, 1) }}{{ substr($estudiante->primer_apellido, 0, 1) }}
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0 fw-bold text-dark">{{ $estudiante->primer_nombre }}
                                                                {{ $estudiante->segundo_nombre }}
                                                                {{ $estudiante->primer_apellido }}
                                                                {{ $estudiante->segundo_apellido }}
                                                            </h6>
                                                            <div class="small text-muted d-flex align-items-center mt-1">
                                                                <i class="far fa-id-card me-1"></i>
                                                                {{ $estudiante->document ?? 'N/A' }}
                                                                <!-- Asumiendo que el campo Cédula está disponible en el join -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div
                                                        class="input-group input-group-sm border rounded-3 overflow-hidden {{ isset($estudiante->calificacion) ? ($estudiante->calificacion >= 60 ? 'border-success' : 'border-danger') : 'border-light' }}">
                                                        <input type="number"
                                                            name="calificaciones[{{ $estudiante->id_persona }}][nota]"
                                                            class="form-control border-0 text-center fw-bold fs-6 py-2 {{ isset($estudiante->calificacion) ? ($estudiante->calificacion >= 60 ? 'text-success' : 'text-danger') : '' }}"
                                                            value="{{ $estudiante->calificacion }}" min="0" max="100"
                                                            step="0.01" placeholder="-" style="background: #f8f9fa;">
                                                        <span
                                                            class="input-group-text border-0 bg-white text-muted small px-2">/100</span>
                                                    </div>
                                                </td>
                                                <td class="pe-4">
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-white border-end-0 text-muted ps-3"><i
                                                                class="far fa-comment-dots"></i></span>
                                                        <input type="text"
                                                            name="calificaciones[{{ $estudiante->id_persona }}][observacion]"
                                                            class="form-control border-start-0 ps-0"
                                                            value="{{ $estudiante->observacion }}"
                                                            placeholder="Escribe una observación..."
                                                            style="background-color: transparent;">
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center py-5">
                                                    <div class="py-4">
                                                        <div class="mb-3">
                                                            <div class="icon-shape icon-lg bg-light text-muted rounded-circle">
                                                                <i class="fas fa-search fa-2x"></i>
                                                            </div>
                                                        </div>
                                                        <h5 class="fw-bold text-dark">No se encontraron resultados</h5>
                                                        <p class="text-muted mb-0">Intenta ajustar los filtros de búsqueda.</p>
                                                        @if(request('search'))
                                                            <a href="{{ route('taller.calificaciones.index', ['curso' => $curso->id_curso, 'contenido' => $contenido->id_contenido_curso]) }}"
                                                                class="btn btn-sm btn-outline-primary mt-3">Limpiar búsqueda</a>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            @if(count($estudiantes) > 0)
                                <div class="card-footer bg-white py-4 px-4 border-0 d-flex justify-content-end">
                                    <button type="submit"
                                        class="btn btn-dark btn-lg px-5 rounded-pill shadow-lg hover-transform">
                                        <i class="fas fa-save me-2"></i> Guardar Cambios
                                    </button>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .bg-primary-soft {
                background-color: rgba(94, 114, 228, 0.1) !important;
                color: #5e72e4 !important;
            }

            .bg-warning-soft {
                background-color: rgba(251, 99, 64, 0.1) !important;
                color: #fb6340 !important;
            }

            .bg-gray-100 {
                background-color: #f6f9fc !important;
            }

            .hover-lift {
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }

            .hover-lift:hover {
                transform: translateY(-2px);
                box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
            }

            .hover-transform:hover {
                transform: translateY(-1px);
            }

            .bg-gradient-primary {
                background: linear-gradient(87deg, #5e72e4 0, #825ee4 100%) !important;
            }

            .transition-hover:hover {
                background-color: #fbfcfd;
            }

            input[type=number]::-webkit-inner-spin-button,
            input[type=number]::-webkit-outer-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }

            .form-control:focus {
                box-shadow: none;
                border-color: #5e72e4;
            }
        </style>
    @endpush
@endsection
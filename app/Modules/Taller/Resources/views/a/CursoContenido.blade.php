@extends('layouts.kaiadmin-menu')

@section('title', 'Contenido del Curso')

{{--
Vista: CursoContenido
Descripción: Muestra el reproductor/visor de contenido para un curso específico.
Incluye una barra lateral de navegación entre lecciones y un área principal para el contenido seleccionado.
--}}

@section('content')
    {{-- Contenedor fluido con altura mínima para asegurar footer al fondo --}}
    <div class="container-fluid py-4" style="min-height: 85vh;">
        <div class="mb-3">
            <a href="{{ route('taller.cursos.show', $curso->id_curso) }}" class="btn btn-light shadow-sm border">
                <i class="fas fa-arrow-left me-2"></i> Volver a Detalles del Curso
            </a>
        </div>
        <div class="row">
            <!-- Sidebar de Navegación (Izquierda en pantallas grandes) -->
            <div class="col-lg-4 order-lg-2 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0">Contenido del Curso</h5>
                        <small class="text-muted">{{ $curso->nombre }}</small>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush" style="max-height: 500px; overflow-y: auto;">
                            @forelse($curso->contenidos as $contenido)
                                @php
                                    $esActivo = $contenidoActual && $contenido->id_contenido_curso == $contenidoActual->id_contenido_curso;
                                    $icono = 'fa-file-alt';
                                    if (strtolower($contenido->tipo_contenido) == 'video')
                                        $icono = 'fa-play-circle';
                                    if (strtolower($contenido->tipo_contenido) == 'archivo')
                                        $icono = 'fa-download';
                                    if (strtolower($contenido->tipo_contenido) == 'enlace')
                                        $icono = 'fa-link';

                                    // Sobrescribir icono si es evaluación
                                    if ($contenido->es_evaluacion)
                                        $icono = 'fa-clipboard-list';
                                @endphp
                                <a href="{{ route('taller.cursos.contenido', ['curso' => $curso->id_curso, 'contenido_id' => $contenido->id_contenido_curso]) }}"
                                    class="list-group-item list-group-item-action d-flex align-items-center p-3 {{ $esActivo ? 'active bg-primary text-white border-primary' : '' }}">
                                    <div class="me-3">
                                        <i class="fas {{ $icono }} {{ $esActivo ? 'text-white' : 'text-primary' }}"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0 {{ $esActivo ? 'text-white' : '' }}">
                                                {{ $contenido->titulo }}
                                                @if($contenido->es_evaluacion && $contenido->ponderacion > 0)
                                                    <span
                                                        class="badge {{ $esActivo ? 'bg-white text-primary' : 'bg-warning text-dark' }} ms-1"
                                                        style="font-size: 0.7em;">
                                                        {{ $contenido->ponderacion }}%
                                                    </span>
                                                @endif
                                            </h6>
                                            <small class="{{ $esActivo ? 'text-white-50' : 'text-muted' }} ms-2">
                                                {{ $contenido->orden }}
                                            </small>
                                        </div>
                                        <small class="{{ $esActivo ? 'text-white-50' : 'text-muted' }} d-block text-truncate"
                                            style="max-width: 250px;">
                                            {{ $contenido->descripcion_breve }}
                                        </small>
                                    </div>
                                </a>
                            @empty
                                <div class="p-4 text-center">
                                    <i class="fas fa-box-open fa-2x text-muted mb-2"></i>
                                    <p class="text-muted mb-0">No hay contenidos disponibles.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Área Principal de Contenido -->
            <div class="col-lg-8 order-lg-1">
                @if($contenidoActual)
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body p-4">
                            <h2 class="h3 mb-3">{{ $contenidoActual->titulo }}</h2>

                            @php
                                // Determinar el tipo de contenido y configurar el botón de acción
                                $tipo = strtolower($contenidoActual->tipo_contenido);
                                $url = $contenidoActual->url_contenido;

                                // Configuración por defecto (Enlace)
                                $btnClass = 'btn-primary';
                                $btnIcon = 'fa-external-link-alt';
                                $btnText = 'Contenido sugerido';

                                // Personalización según tipo
                                if ($tipo == 'video') {
                                    $btnClass = 'btn-danger';
                                    $btnIcon = 'fa-play';
                                    // $btnText = 'Ver Video'; // Texto personalizado por usuario
                                } elseif ($tipo == 'archivo') {
                                    $btnClass = 'btn-info text-white';
                                    $btnIcon = 'fa-download';
                                    $btnText = 'Contenido sugerido';
                                }

                                // Personalización para evaluaciones
                                if ($contenidoActual->es_evaluacion) {
                                    $tipoNombre = $contenidoActual->tipoEvaluacion ? $contenidoActual->tipoEvaluacion->nombre : 'Evaluación';
                                    $tipo = $tipoNombre; // Para el badge

                                    if (isset($esFacilitador) && $esFacilitador) {
                                        $btnClass = 'btn-primary text-white';
                                         $btnIcon = 'fa-check-double';
                                        $btnText = 'Calificar ' . $tipoNombre;
                                        $url = route('taller.calificaciones.index', ['curso' => $curso->id_curso, 'contenido' => $contenidoActual->id_contenido_curso]);
                                    } else {
                                        if ($tipo == 'video') {
                                            $btnClass = 'btn-danger';
                                            $btnIcon = 'fa-play';
                                            $btnText = 'Ver Video';
                                        } elseif ($tipo == 'archivo') {
                                            $btnClass = 'btn-info text-white';
                                            $btnIcon = 'fa-download';
                                            $btnText = 'Contenido sugerido';
                                        } else {
                                            $btnClass = 'btn-primary';
                                            $btnIcon = 'fa-external-link-alt';
                                            $btnText = 'Contenido sugerido';
                                        }

                                    }
                                }
                            @endphp

                            <!-- Encabezado -->
                            <div class="d-flex flex-wrap align-items-center justify-content-between mb-4 pb-3 border-bottom">
                                <div class="d-flex align-items-center mb-2 mb-md-0">
                                    <span class="badge bg-light text-dark border me-3 px-3 py-2 rounded-pill fw-normal">
                                        {{ ucfirst($tipo) }}
                                        @if($contenidoActual->es_evaluacion && $contenidoActual->ponderacion > 0)
                                            <span class="ms-1 px-2 py-0 badge bg-success text-white">Valor:
                                                {{ $contenidoActual->ponderacion }}%</span>
                                        @elseif(!$contenidoActual->es_evaluacion)
                                            <span class="ms-1 px-2 py-0 badge bg-secondary text-white">Contenido no evaluado</span>
                                        @endif
                                    </span>
                                    <span class="text-muted small"><i class="far fa-calendar-alt me-1"></i>
                                        {{ $contenidoActual->created_at ? $contenidoActual->created_at->format('d/m/Y') : 'N/A' }}</span>
                                </div>
                                <a href="{{ $url }}" class="btn {{ $btnClass }} rounded-pill px-4 btn-action">
                                    <i class="fas {{ $btnIcon }} me-2"></i> {{ $btnText }}
                                </a>
                            </div>

                            <!-- Resultado de Evaluación (Solo Estudiantes con nota) -->
                            @if(isset($calificacion) && $calificacion)
                                <div
                                    class="alert {{ $calificacion->calificacion >= 60 ? 'alert-success' : 'alert-danger' }} border-0 shadow-sm mt-4 rounded-4">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="h3 mb-0 me-3">
                                            @if($calificacion->calificacion >= 60)
                                                <i class="fas fa-check-circle text-success"></i>
                                            @else
                                                <i class="fas fa-times-circle text-danger"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <h5 class="alert-heading fw-bold mb-0">Evaluación Calificada</h5>
                                            <p class="mb-0 text-muted small">Fecha de calificación:
                                                {{ \Carbon\Carbon::parse($calificacion->actualizado_en ?? $calificacion->creado_en)->format('d/m/Y') }}
                                            </p>
                                        </div>
                                        <div class="ms-auto text-end">
                                            <span class="display-4 fw-bold">{{ floatval($calificacion->calificacion) }}/100</span>
                                            <span class="text-muted d-block small">Nota Final</span>
                                        </div>
                                    </div>

                                    @if($calificacion->observacion)
                                        <hr>
                                        <p class="mb-1 fw-bold"><i class="fas fa-comment-alt me-2"></i>Feedback del Facilitador:</p>
                                        <p class="mb-0 fst-italic">{{ $calificacion->observacion }}</p>
                                    @endif
                                </div>
                            @endif

                            <!-- Descripción y Detalles -->
                            <div class="contenido-descripcion mt-4">
                                <h5 class="text-dark fw-bold mb-3" style="font-family: 'Poppins', sans-serif;">Sobre este
                                    contenido</h5>
                                <div class="text-muted lead" style="font-size: 1.05rem; line-height: 1.8;">
                                    {{ $contenidoActual->descripcion }}
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="empty-state text-center py-5">
                        <div class="empty-icon bg-light rounded-circle mx-auto mb-4 d-flex align-items-center justify-content-center"
                            style="width: 100px; height: 100px;">
                            <i class="fas fa-play fa-2x text-muted"></i>
                        </div>
                        <h4 class="fw-bold text-dark">Selecciona un contenido</h4>
                        <p class="text-muted text-center" style="max-width: 400px; margin: 0 auto;">Elige una lección del menú
                            de navegación para comenzar tu aprendizaje.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .card {
                border-radius: 16px;
                border: 1px solid rgba(0, 0, 0, 0.05);
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.02);
                transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
            }

            .card:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
            }

            /* Estilo del Sidebar */
            .list-group-item {
                border: none;
                border-radius: 8px !important;
                margin-bottom: 4px;
                padding: 1rem 1.25rem;
                transition: all 0.2s ease;
                color: #525f7f;
            }

            .list-group-item:hover {
                background-color: #f8f9fa;
                color: #212529;
            }

            .list-group-item.active {
                background-color: #f6f9fc !important;
                color: #2dce89 !important;
                /* Color primario suave o verde éxito */
                font-weight: 600;
                border: 1px solid #e9ecef;
                box-shadow: inset 4px 0 0 #2dce89;
                /* Borde izquierdo activo */
            }

            .list-group-item.active .text-muted {
                color: #8898aa !important;
            }

            .list-group-item.active i {
                color: #2dce89 !important;
            }

            /* Scrollbar personalizado para la lista */
            .list-group-flush::-webkit-scrollbar {
                width: 6px;
            }

            .list-group-flush::-webkit-scrollbar-track {
                background: #f1f1f1;
            }

            .list-group-flush::-webkit-scrollbar-thumb {
                background: #c1c1c1;
                border-radius: 4px;
            }

            .list-group-flush::-webkit-scrollbar-thumb:hover {
                background: #a8a8a8;
            }

            /* Botón de Acción */
            .btn-action {
                border-width: 2px;
                font-weight: 600;
                letter-spacing: 0.5px;
                transition: all 0.3s ease;
            }

            .btn-action:hover {
                background-color: #212529;
                color: #fff;
                transform: translateY(-1px);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            }

            /* Header Gradiente (si se usa) */
            .bg-gradient-primary {
                background: linear-gradient(87deg, #5e72e4 0, #825ee4 100%) !important;
            }
        </style>
    @endpush
@endsection
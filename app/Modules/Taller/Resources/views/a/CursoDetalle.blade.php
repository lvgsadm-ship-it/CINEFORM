@extends('layouts.kaiadmin-menu')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-xl-11">
            
            <div class="row g-4">
                <!-- Columna Principal (8) -->
                <div class="col-lg-8">
                    <!-- Tarjeta Hero de Información Principal -->
                    <div class="card border-0 shadow-card rounded-4 mb-4 overflow-hidden border-top border-4 border-primary">
                        <div class="card-header bg-white py-4 px-4 border-bottom">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                <div>
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb mb-1 p-0 bg-transparent" style="font-size: 0.75rem;">
                                            <li class="breadcrumb-item"><a href="{{ route('taller.cursos.index') }}" class="text-decoration-none text-muted">Explorar Cursos</a></li>
                                            <li class="breadcrumb-item active text-primary fw-bold" aria-current="page">Ficha Técnica</li>
                                        </ol>
                                    </nav>
                                    <h2 class="fw-bold text-dark mb-0">{{ $curso->nombre }}</h2>
                                </div>
                                
                                @if(isset($inscripcion) && $inscripcion && $debeMostrarPromedio)
                                    <div class="d-flex align-items-center bg-light p-2 px-3 rounded-pill border shadow-xs animate__animated animate__fadeInRight">
                                        <div class="text-end me-3">
                                            <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 0.6rem; letter-spacing: 0.5px;">Tu Promedio</small>
                                            <span class="fw-bold text-primary fs-5">{{ number_format($promedioEstudiante, 2) }}</span><small class="text-muted">/100</small>
                                        </div>
                                        <div class="icon-box {{ $promedioEstudiante >= 80 ? 'bg-success' : 'bg-warning' }} text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                                            <i class="fas {{ $promedioEstudiante >= 80 ? 'fa-chart-line' : 'fa-exclamation-triangle' }} small"></i>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <!-- Descripción -->
                            <div class="mb-5">
                                <h6 class="text-muted small fw-bold mb-3 d-flex align-items-center">
                                    <i class="fas fa-align-left me-2 text-primary opacity-50"></i> SÍNTESIS DEL PROGRAMA
                                </h6>
                                <p class="text-secondary mb-0 fs-5" style="line-height: 1.7;">
                                    {{ $curso->descripcion ?? 'Este programa académico no cuenta con una descripción detallada en este momento.' }}
                                </p>
                            </div>

                            <!-- Información Rápida en Columnas -->
                            <div class="row g-4">
                                <div class="col-md-4">
                                    <div class="p-3 rounded-4 bg-primary-soft border border-primary-soft h-100 d-flex align-items-center">
                                        <div class="bg-white rounded-circle p-3 me-3 shadow-xs">
                                            <i class="fas fa-clock text-primary fs-4"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.65rem;">Duración</small>
                                            <h6 class="mb-0 fw-bold text-dark">{{ $curso->duracion }} Días</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 rounded-4 bg-success-soft border border-success-soft h-100 d-flex align-items-center">
                                        <div class="bg-white rounded-circle p-3 me-3 shadow-xs">
                                            <i class="fas fa-hourglass-half text-success fs-4"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.65rem;">Intensidad</small>
                                            <h6 class="mb-0 fw-bold text-dark">{{ $curso->horas }} Horas</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 rounded-4 bg-info-soft border border-info-soft h-100 d-flex align-items-center">
                                        <div class="bg-white rounded-circle p-3 me-3 shadow-xs">
                                            <i class="fas fa-laptop-house text-info fs-4"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.65rem;">Modalidad</small>
                                            <h6 class="mb-0 fw-bold text-dark text-truncate">{{ $curso->modalidad->nombre_modalidad ?? 'No especificada' }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Lista de Contenidos: Estilo Learning Path -->
                    <div class="card border-0 shadow-card rounded-4 overflow-hidden mb-4">
                        <div class="card-header bg-white py-4 px-4 border-bottom d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold mb-0 text-dark">
                                <i class="fas fa-layer-group me-2 text-primary opacity-50"></i> Itinerario del Programa
                            </h5>
                            <span class="badge bg-light text-muted border py-2 px-3 rounded-pill fw-bold" style="font-size: 0.75rem;">
                                {{ $curso->contenidos->count() }} Módulos
                            </span>
                        </div>
                        <div class="card-body p-4 pt-1">
                            @if($curso->contenidos && $curso->contenidos->count() > 0)
                                <div class="curriculum-path mt-3 position-relative ps-4">
                                    <!-- Línea de conexión vertical -->
                                    <div class="path-line position-absolute h-100 bg-light rounded-pill" style="width: 4px; left: 18px; top: 0; opacity: 0.5;"></div>

                                    @foreach($curso->contenidos as $index => $contenido)
                                        <div class="path-item position-relative mb-4 animate__animated animate__fadeInUp" style="animation-delay: {{ $index * 0.1 }}s;">
                                            <!-- Indicador de Punto -->
                                            <div class="path-marker position-absolute bg-white border border-4 rounded-circle bg-white shadow-sm d-flex align-items-center justify-content-center fw-bold transition-all z-index-1 {{ $contenido->es_evaluacion ? 'border-success text-success' : 'border-primary text-primary' }}" 
                                                 style="width: 32px; height: 32px; left: -30px; top: 10px; font-size: 0.75rem;">
                                                {{ $index + 1 }}
                                            </div>

                                            <!-- Card del Contenido -->
                                            <div class="card border-0 shadow-xs rounded-4 ms-3 transition-all hover-translate overflow-hidden border-start border-4 {{ $contenido->es_evaluacion ? 'border-success' : 'border-primary' }}">
                                                <div class="card-body p-3 ps-4">
                                                    <div class="row align-items-center g-3">
                                                        <div class="col-md-9 overflow-hidden">
                                                            <div class="d-flex align-items-center mb-1">
                                                                <h6 class="mb-0 fw-bold text-dark fs-5 text-truncate">{{ $contenido->titulo }}</h6>
                                                                @if($contenido->es_evaluacion)
                                                                    <span class="badge bg-success text-white rounded-pill scale-80 ms-2 px-2" style="font-size: 0.6rem;">EVAL</span>
                                                                @else
                                                                    <span class="badge bg-primary text-white rounded-pill scale-80 ms-2 px-2" style="font-size: 0.6rem;">CONTENIDO</span>
                                                                @endif
                                                            </div>
                                                            <p class="mb-0 text-muted small opacity-75" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.5;">
                                                                {{ $contenido->descripcion_breve ?: 'Sin descripción del módulo.' }}
                                                            </p>
                                                        </div>
                                                        <div class="col-md-3 text-end">
                                                            @if($contenido->es_evaluacion)
                                                                <div class="text-success fw-bold small mb-2">{{ $contenido->ponderacion }}% del total</div>
                                                            @endif
                                                            <a href="{{ route('taller.cursos.contenido', ['curso' => $curso->id_curso, 'contenido_id' => $contenido->id_contenido_curso]) }}" 
                                                               class="btn btn-outline-dark btn-sm rounded-pill px-4 fw-bold hvr-push border-2">
                                                                Acceder <i class="fas fa-arrow-right ms-1 small"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-route fa-3x text-muted opacity-25 mb-3"></i>
                                    <h6 class="fw-bold text-muted mb-0">Ruta de aprendizaje no definida.</h6>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Barra Lateral (4) -->
                <div class="col-lg-4">
                    <!-- Instructor -->
                    <div class="card border-0 shadow-card rounded-4 mb-4 text-center">
                        <div class="card-header bg-white py-3 border-bottom">
                            <h6 class="fw-bold mb-0 text-dark">Facilitador</h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="avatar-xl mx-auto mb-3 position-relative">
                                <img src="{{ asset('assets/img/avatar.png') }}" alt="Instructor"
                                    class="rounded-circle img-thumbnail border-2 border-primary-soft"
                                    style="width: 120px; height: 120px; object-fit: cover;">
                            </div>
                            <h5 class="fw-bold text-dark mb-1">
                                @if($curso->persona)
                                    {{ $curso->persona->primer_nombre }} {{ $curso->persona->primer_apellido }}
                                @else
                                    Pendiente por asignar
                                @endif
                            </h5>
                            <p class="text-muted small mb-4">Instructor Titular</p>
                            
                            @if($curso->persona)
                                <button onclick="mostrarContactoProfesor('{{ $curso->persona->nombre_completo }}', '{{ $curso->persona->user->email ?? 'N/D' }}', '{{ $curso->persona->telefono ?? 'N/D' }}')"
                                        class="btn btn-primary rounded-pill px-5 w-100 fw-bold shadow-sm transition-all hvr-push">
                                    <i class="fas fa-paper-plane me-2"></i> Contactar
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Otros Detalles y Acciones -->
                    <div class="card border-0 shadow-card rounded-4">
                        <div class="card-header bg-white py-3 border-bottom">
                            <h6 class="fw-bold mb-0 text-dark">Detalles Logísticos</h6>
                        </div>
                        <div class="card-body p-4 pt-2">
                            <div class="list-group list-group-flush">
                                <div class="list-group-item border-0 px-0 py-3 d-flex justify-content-between align-items-center">
                                    <span class="text-muted small fw-bold text-uppercase"><i class="far fa-calendar-alt me-2 text-primary opacity-50"></i> Inicio</span>
                                    <span class="fw-bold text-dark">{{ $curso->fecha_inicio ? \Carbon\Carbon::parse($curso->fecha_inicio)->format('d/m/Y') : '--/--/----' }}</span>
                                </div>
                                <div class="list-group-item border-0 px-0 py-3 d-flex justify-content-between align-items-center">
                                    <span class="text-muted small fw-bold text-uppercase"><i class="far fa-calendar-check me-2 text-success opacity-50"></i> Cierre</span>
                                    <span class="fw-bold text-dark">{{ $curso->fecha_fin ? \Carbon\Carbon::parse($curso->fecha_fin)->format('d/m/Y') : '--/--/----' }}</span>
                                </div>
                                <div class="list-group-item border-0 px-0 py-3 d-flex justify-content-between align-items-center border-bottom-light">
                                    <span class="text-muted small fw-bold text-uppercase"><i class="fas fa-users me-2 text-warning opacity-50"></i> Cupos</span>
                                    <span class="badge bg-primary rounded-pill px-3">{{ $curso->cantidad_cupos ?? '0' }} disponibles</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-light bg-opacity-50 border-0 p-4 rounded-bottom-4">
                            @auth
                                @if($vistaParcial)
                                    @include("taller::a.$vistaParcial", [
                                        'curso' => $curso,
                                        'inscripcion' => $inscripcion
                                    ])
                                @endif
                                <div class="text-center mt-3">
                                    <a href="{{ route('taller.cursos.index') }}" class="text-muted text-decoration-none small fw-bold hvr-underline-from-left">
                                        Cursos disponibles <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@push('styles')
<style>
    .bg-primary-soft { background-color: rgba(30, 58, 138, 0.05); }
    .bg-success-soft { background-color: rgba(16, 185, 129, 0.05); }
    .bg-info-soft { background-color: rgba(14, 165, 233, 0.05); }
    .bg-danger-soft { background-color: rgba(239, 68, 68, 0.1); }
    .border-bottom-light { border-bottom: 1px solid #f1f5f9; }
    .hover-bg-light:hover { background-color: #fafbfc; }
    
    .shadow-card { box-shadow: 0 10px 40px rgba(0,0,0,0.06); }
    .shadow-xs { box-shadow: 0 4px 10px rgba(0,0,0,0.02); }
    
    .hvr-push { transition: transform 0.2s; }
    .hvr-push:active { transform: scale(0.97); }
    
    .hvr-underline-from-left:hover { color: #1e3a8a !important; }
    
    .hover-translate:hover { transform: translateX(8px); background-color: #fff !important; box-shadow: 0 5px 15px rgba(0,0,0,0.08) !important; }
    .scale-80 { transform: scale(0.85); transform-origin: left; }
    .path-item { transition: all 0.3s ease; }
</style>
@endpush

@push('scripts')
<script>
    // Lógica conservada al 100% de la arquitectura original
    function verMotivoRechazo(cursoId, motivo, cursoNombre = '') {
        Swal.fire({
            html: `<div class="p-2">
                <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                <h4 class="fw-bold text-dark">Propuesta Declinada</h4>
                <div class="text-start bg-light p-3 rounded-4 mt-3" style="border: 1px solid #eee;">
                    <small class="text-muted fw-bold d-block mb-1">OBSERVACIÓN:</small>
                    <p class="mb-0 text-secondary">${motivo || 'No se suministró motivo.'}</p>
                </div>
            </div>`,
            confirmButtonText: 'Entendido',
            confirmButtonColor: '#1e3a8a',
            customClass: { popup: 'rounded-4' }
        });
    }

    function mostrarContactoProfesor(nombre, email, telefono) {
        Swal.fire({
            title: '<h5 class="fw-bold mb-0">Contacto de Facilitador</h5>',
            html: `<div class="p-3 text-start">
                <div class="mb-3">
                    <small class="text-muted fw-bold">NOMBRE:</small>
                    <p class="mb-0 text-dark fw-bold">${nombre}</p>
                </div>
                <div class="mb-3">
                    <small class="text-muted fw-bold">EMAIL:</small>
                    <p class="mb-0 text-primary">${email}</p>
                </div>
                <div>
                    <small class="text-muted fw-bold">TELÉFONO:</small>
                    <p class="mb-0 text-dark">${telefono}</p>
                </div>
            </div>`,
            confirmButtonText: 'Cerrar',
            confirmButtonColor: '#1e3a8a',
            customClass: { popup: 'rounded-4' }
        });
    }

    function processStatusAction(idEstado, title, successMsg) {
        fetch('{{ route("taller.cursos.updateStatus", ["curso" => $curso->id_curso]) }}', {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
            body: JSON.stringify({ id_estado: idEstado })
        }).then(r => r.json()).then(data => {
            if (data.success) {
                Swal.fire({ icon: 'success', title: title, text: successMsg, timer: 3000, showConfirmButton: false }).then(() => window.location.reload());
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: data.message });
            }
        });
    }

    function aprobarCurso(id) { processStatusAction(6, 'Aprobado', 'El curso ahora está público.'); }
    function finalizarEdicion(id) { processStatusAction(5, 'Solicitud Enviada', 'La propuesta ha sido enviada.'); }
    function finalizarInscripciones(id) { processStatusAction(7, 'Inscripciones Finalizadas', 'Proceso de captación cerrado.'); }
    function finalizarCurso(id) { processStatusAction(8, 'Curso Finalizado', 'Programa concluido.'); }
    function cerrarCurso(id) { processStatusAction(9, 'Curso Cerrado', 'Programa archivado.'); }

    function aceptarCursoFacilitador(id) {
        if(event) event.target.disabled = true;
        processStatusAction(4, 'Aceptado', 'Has aceptado la asignación.');
    }

    function rechazarContenido(idCurso) {
        Swal.fire({
            title: 'Declinar Propuesta',
            input: 'textarea',
            inputPlaceholder: 'Motivos...',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            preConfirm: (motivo) => {
                if (!motivo) return Swal.showValidationMessage('Debe ingresar un motivo');
                return fetch('{{ route("taller.cursos.updateStatus", ["curso" => $curso->id_curso]) }}', {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                    body: JSON.stringify({ id_estado: 3, motivo: motivo })
                }).then(r => r.json()).then(d => { if(!d.success) throw new Error(d.message); return d; });
            }
        }).then((result) => { if (result.isConfirmed) window.location.reload(); });
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.inscribir-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const id = this.getAttribute('data-curso-id');
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                fetch('{{ route('taller.inscripciones.store') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                    body: JSON.stringify({ id_curso: id })
                }).then(r => r.json()).then(data => { if(data.success) window.location.reload(); });
            });
        });

        document.querySelectorAll('.cancelar-inscripcion-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const id = this.getAttribute('data-inscripcion-id');
                fetch(`{{ url('taller/inscripciones') }}/${id}/cancelar`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                }).then(() => window.location.reload());
            });
        });
    });
</script>
@endpush
@endsection
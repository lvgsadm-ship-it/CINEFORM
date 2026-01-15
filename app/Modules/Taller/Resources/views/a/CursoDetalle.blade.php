@extends('layouts.kaiadmin-menu')

@section('title', 'Detalles del Curso')

{{-- 
    Vista: CursoDetalle
    Descripción: Página principal de información del curso.
    Funcionalidades:
    - Muestra detalles generales (descripción, instructor, horarios).
    - Gestiona lógica de inscripción (inscribirse/cancelar).
    - Muestra acciones administrativas para Facilitadores y Coordinadores.
    - Lista el contenido del curso con enlaces al visor (CursoContenido).
--}}

 @auth
                            @php
                                // Obtener el ID de la persona desde los datos personales
                                $user = auth()->user();
                                $personalData = \Modules\Comun\Entities\PersonalData::where('document', $user->document)->first();
                                $idPersona = $personalData ? $personalData->id : null;

                                // --- Lógica de Estados y Roles ---
                                
                                // El faciliatador debe acetar el curso
                                $PorAceptar = $curso->estado_actual->id_estado == 1;

                                // Verificar si el usuario es el coordinador
                                $esCoordinador = $user->profile_id == 4;

                                // Verificar si el usuario es el instructor del curso
                                $esFacilitador = $curso->id_persona == $idPersona;

                                // Verificar si el curso está en edicion
                                $EnEdicion = $curso->estado_actual->id_estado == 4;

                                $Declinado = $curso->estado_actual->id_estado == 3;

                                // Verificar si el curso está en evaluación por coordinacion     
                                $EnAprobacion = $curso->estado_actual->id_estado == 5;

                                // Verificar si el curso está finalizado
                                $Finalizado = $curso->estado_actual->id_estado == 8;

                                // Verificar si el curso esta en progreso
                                $EnProgreso = $curso->estado_actual->id_estado == 7;

                                // Verificar si el curso esta cerrado
                                $Cerrado = $curso->estado_actual->id_estado == 9;

                                $CuposDisponibles = $curso->cantidad_cupos;

                                // Verificar si el usuario ya está inscrito
                                $inscripcion = $idPersona ?
                                    \Modules\Taller\Entities\Inscripcion::where('id_curso', $curso->id_curso)
                                        ->where('id_persona', $idPersona)
                                        ->first() : null;
                            @endphp
@endauth

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
                                        <p class="mb-0 fw-bold">
                                            {{ $curso->modalidad->nombre_modalidad ?? 'No especificada' }}</p>
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
                                                <h6 class="mb-1">
                                                    <a href="{{ route('taller.cursos.contenido', ['curso' => $curso->id_curso, 'contenido_id' => $contenido->id_contenido_curso]) }}" class="text-decoration-none">
                                                        {{ $contenido->titulo }}
                                                    </a>
                                                </h6>
                                                <p class="mb-0 text-muted small">{{ $contenido->descripcion_breve }}</p>
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
                            <img src="{{ asset('assets/img/avatar.png') }}" alt="Instructor"
                                class="rounded-circle img-thumbnail"
                                style="width: 120px; height: 120px; object-fit: cover;">
                        </div>
                        <h5 class="mb-1">
                            @if($curso->persona)
                                {{ $curso->persona->primer_nombre ?? 'Nombre no disponible' }}
                                {{ $curso->persona->primer_apellido ?? '' }}
                            @else
                                Instructor no asignado
                            @endif
                        </h5>
                        <p class="text-muted mb-3">Instructor</p>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="#" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-envelope me-1"></i> Contactar
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Información Adicional 
                (fecha de inicio, fecha de fin, cupos, categoria) -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0">Información del Curso</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span><i class="fas fa-calendar-alt text-muted me-2"></i> Fecha de inicio</span>
                                <span
                                    class="fw-bold">{{ $curso->fecha_inicio ? \Carbon\Carbon::parse($curso->fecha_inicio)->format('d/m/Y') : 'Por definir' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span><i class="fas fa-calendar-check text-muted me-2"></i> Fecha de fin</span>
                                <span
                                    class="fw-bold">{{ $curso->fecha_fin ? \Carbon\Carbon::parse($curso->fecha_fin)->format('d/m/Y') : 'Por definir' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span><i class="fas fa-users text-muted me-2"></i> Cupos</span>
                                <span
                                    class="badge bg-primary rounded-pill">{{ $curso->cantidad_cupos ?? 'Por definir' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span><i class="fas fa-tag text-muted me-2"></i> Categoría</span>
                                <span class="fw-bold">{{ $curso->categoria ?? 'General' }}</span>
                            </li>
                        </ul>
                    </div>
                    <div class="card-footer bg-white border-top-0">
                            {{-- Bloque de Botones de Acción según Estado y Rol --}}
                            @auth
                            @if($PorAceptar && $esFacilitador)
                                <a class="btn btn-info w-100 mb-2" disabled>
                                    <i class="fas fa-user-tie me-2"></i> Eres el instructor de este curso
                                </a>
                                <button class="btn btn-success w-100 mb-2"
                                    onclick="AceptarCursoFacilitador({{ $curso->id_curso }})">
                                    <i class="fas fa-user-tie me-2"></i> Aceptar Curso
                                </button>

                            @elseif($EnEdicion && $esFacilitador)
                                <button class="btn btn-success w-100 mb-2" onclick="finalizarEdicion({{ $curso->id_curso }})">
                                    <i class="fas fa-user-tie me-2"></i> Finalizar edicion 
                                </button>
                                <a class="btn btn-primary w-100 mb-2" href="{{ route('taller.cursos.edit', $curso->id_curso) }}">
                                    <i class="fas fa-user-tie me-2"></i> Editar 
                                </a>
                            @elseif($Declinado && $esCoordinador)
                            <i class="fas fa-user-tie me-2"></i> Contenido sugerido Declinado

                            <button class="btn btn-danger w-100 mb-2" 
                                data-motivo="{{ $curso->estado_actual->pivot->motivo ?? '' }}"
                                data-nombre="{{ $curso->nombre }}"
                                onclick="verMotivoRechazo({{ $curso->id_curso }}, this.dataset.motivo, this.dataset.nombre)">
                                Motivo de rechazo
                            </button>
                            @elseif($Declinado && $esFacilitador)
                            <i class="fas fa-user-tie me-2"></i> Contenido sugerido Declinado

                            <button class="btn btn-danger w-100 mb-2" 
                                data-motivo="{{ $curso->estado_actual->pivot->motivo ?? '' }}"
                                data-nombre="{{ $curso->nombre }}"
                                onclick="verMotivoRechazo({{ $curso->id_curso }}, this.dataset.motivo, this.dataset.nombre)">
                                Motivo de rechazo
                            </button>

                            <a class="btn btn-primary w-100 mb-2" href="{{ route('taller.cursos.edit', $curso->id_curso) }}">
                                <i class="fas fa-user-tie me-2"></i> Editar 
                            </a>

                            <button class="btn btn-success w-100 mb-2" onclick="finalizarEdicion({{ $curso->id_curso }})">
                                    <i class="fas fa-user-tie me-2"></i> Finalizar edicion 
                                </button>
                                
                            @elseif($EnAprobacion && $esCoordinador)
                            
                            <button class="btn btn-success w-100 mb-2" onclick="AprobarCurso({{ $curso->id_curso }})">Aprobar Curso</button>
                            
                            <button class="btn btn-danger w-100 mb-2" onclick="RechazarContenido({{ $curso->id_curso }})">Rechazar Curso</button>

                            @elseif($EnAprobacion && $esFacilitador)

                            <a class="btn btn-info w-100 mb-2" disabled>
                                    <i class="fas fa-user-tie me-2"></i> Contenido sugerido en evaluación
                                </a>        
                            @elseif($EnProgreso)
                            <a class="btn btn-success w-100 mb-2" href="{{ route('taller.cursos.contenido', ['curso' => $curso->id_curso]) }}">
                                    <i class="fas fa-user-tie me-2"></i> Ver contenidos
                                </a>
                            @elseif($Cerrado)
                             
                            <i class="fas fa-user-tie me-2"></i> Curso cerrado
                            
                            @elseif($Finalizado)
                                <a class="btn btn-warning w-100 mb-2" disabled>
                                    <i class="fas fa-user-tie me-2"></i> El curso ya se finalizo, contactar con el profesor para
                                    cualquier necesidad.
                                </a>
                                <a class="btn btn-success w-100 mb-2" disabled>
                                    <i class="fas fa-user-tie me-2"></i> Emitir Certificado
                                </a>
                            @elseif ($EnEdicion)
                                <a class="btn btn-warning w-100 mb-2" disabled>
                                    Curso siendo evaluado por el Facilitador
                                </a>
                            @elseif($inscripcion)
                                <a class="btn btn-success w-100 mb-2" disabled>
                                    <i class="fas fa-check-circle me-2"></i> Ya estás inscrito
                                </a>
                                <button class="btn btn-outline-danger w-100 mb-2 cancelar-inscripcion-btn"
                                    data-inscripcion-id="{{ $inscripcion->id_inscripcion }}">
                                    <i class="fas fa-times-circle me-2"></i> Cancelar inscripción
                                </button>

                            @elseif($CuposDisponibles > 0)
                                <button class="btn btn-primary w-100 mb-2" onclick="inscribirAlCurso({{ $curso->id_curso }})">
                                    <i class="fas fa-check-circle me-2"></i> Inscribirse
                                </button>
                            @else
                                <a class="btn btn-secondary w-100 mb-2" disabled>
                                    <i class="fas fa-times-circle me-2"></i> No hay cupos disponibles
                                </a>
                            @endif
                        @endauth
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
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
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
            function verMotivoRechazo(cursoId, motivo, cursoNombre = '') {
                Swal.fire({
                    html: `
                        <div class="text-center mb-4">
                            <div class="icon-box mb-3 mx-auto bg-danger-subtle text-danger rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                <i class="fas fa-exclamation-triangle fa-3x"></i>
                            </div>
                            <h3 class="fw-bold text-dark">Motivo del Rechazo</h3>
                            ${cursoNombre ? `<p class="text-muted small text-uppercase fw-bold mb-0">${cursoNombre}</p>` : ''}
                        </div>

                        <div class="card border-0 bg-light shadow-sm mb-3">
                            <div class="card-body text-start p-4">
                                <h6 class="text-danger fw-bold mb-2">
                                    <i class="fas fa-comment-dots me-2"></i>Observación del Coordinador:
                                </h6>
                                <p class="mb-0 text-dark" style="font-size: 1.1rem; line-height: 1.6; white-space: pre-line;">
                                    ${motivo || 'No se ha especificado un motivo detallado para el rechazo.'}
                                </p>
                            </div>
                        </div>

                        <p class="text-muted small mb-0">
                            Por favor, realiza las correcciones necesarias y envía el curso a revisión nuevamente.
                        </p>
                    `,
                    showCloseButton: true,
                    showConfirmButton: true,
                    confirmButtonText: 'Entendido, corregiré el curso',
                    confirmButtonColor: '#343a40',
                    buttonsStyling: true,
                    customClass: {
                        popup: 'rounded-4 shadow-lg',
                        confirmButton: 'btn btn-dark px-4 py-2 rounded-pill fw-bold',
                        closeButton: 'focus-ring focus-ring-danger'
                    },
                    width: '550px',
                    padding: '2rem',
                    background: '#ffffff',
                    backdrop: `rgba(0,0,0,0.4)`
                });
            }
            function RechazarContenido(idCurso, btnElement) {
                const btn = btnElement || event?.target;
                const originalText = btn.innerHTML;

                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

                Swal.fire({
                    title: 'Motivo del rechazo',
                    text: 'Ingrese el motivo del rechazo (mínimo 10 caracteres):',
                    input: 'textarea',
                    inputPlaceholder: 'Escriba aquí...',
                    showCancelButton: true,
                    confirmButtonText: 'Rechazar',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#d33',
                    showLoaderOnConfirm: true,

                    inputValidator: (value) => {
                        if (!value || value.trim().length < 10) {
                            return 'Debe ingresar al menos 10 caracteres';
                        }
                        return null;
                    },

                    preConfirm: async (motivo) => {
                        try {
                            const url = '{{ route("taller.cursos.updateStatus", ["curso" => ":id"]) }}'
                                .replace(':id', idCurso);

                            const response = await fetch(url, {
                                method: 'PUT',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    id_estado: 3,
                                    motivo: motivo.trim()
                                })
                            });

                            if (!response.ok) throw new Error(`Error: ${response.status}`);

                            const data = await response.json();
                            if (!data.success) throw new Error(data.message);

                            return data;

                        } catch (error) {
                            Swal.showValidationMessage(error.message);
                            throw error;
                        }
                    }

                }).then((result) => {
                    // Restaurar botón siempre
                    btn.disabled = false;
                    btn.innerHTML = originalText;

                    if (result.isConfirmed) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Rechazado!',
                            text: 'Curso rechazado exitosamente.',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    }

                }).catch(() => {
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                });
            }
            function finalizarEdicion(idCurso) {



                fetch('{{ route("taller.cursos.updateStatus", ["curso" => $curso->id_curso]) }}', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        id_estado: 5 // El ID del estado al que quieres cambiar
                    })

                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Tu solicitud esta en proceso!',
                                text: 'En la brevedad posible te daremos respuesta de tu propuesta',
                                showConfirmButton: false,
                                timer: 5000
                            }).then(() => {
                                // Recargar la página para actualizar la vista
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message || 'Ocurrió un error al actualizar el estado del curso',
                                confirmButtonText: 'Entendido'
                            });
                            btn.disabled = false;
                            btn.innerHTML = originalText;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Ocurrió un error al procesar la solicitud',
                            confirmButtonText: 'Entendido'
                        });
                        btn.disabled = false;
                        btn.innerHTML = originalText;
                    });

            }

            function AprobarCurso(idCurso) {



                fetch('{{ route("taller.cursos.updateStatus", ["curso" => $curso->id_curso]) }}', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        id_estado: 6 // El ID del estado al que quieres cambiar
                    })

                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Tu solicitud esta en proceso!',
                                text: 'En la brevedad posible te daremos respuesta de tu propuesta',
                                showConfirmButton: false,
                                timer: 5000
                            }).then(() => {
                                // Recargar la página para actualizar la vista
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message || 'Ocurrió un error al actualizar el estado del curso',
                                confirmButtonText: 'Entendido'
                            });
                            btn.disabled = false;
                            btn.innerHTML = originalText;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Ocurrió un error al procesar la solicitud',
                            confirmButtonText: 'Entendido'
                        });
                        btn.disabled = false;
                        btn.innerHTML = originalText;
                    });

            }

            // Función para actualizar el estado del curso
            function AceptarCursoFacilitador(idCurso) {


                const btn = event.target;
                const originalText = btn.innerHTML;

                // Mostrar indicador de carga
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Actualizando...';

                // Realizar la petición AJAX
                fetch('{{ route("taller.cursos.updateStatus", ["curso" => $curso->id_curso]) }}', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        id_estado: 4 // El ID del estado al que quieres cambiar
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Gracias por trabajar con nosotros!',
                                text: 'Esperamos que disfrutes de tu curso.',
                                showConfirmButton: false,
                                timer: 5000
                            }).then(() => {
                                // Recargar la página para actualizar la vista
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message || 'Ocurrió un error al actualizar el estado del curso',
                                confirmButtonText: 'Entendido'
                            });
                            btn.disabled = false;
                            btn.innerHTML = originalText;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Ocurrió un error al procesar la solicitud',
                            confirmButtonText: 'Entendido'
                        });
                        btn.disabled = false;
                        btn.innerHTML = originalText;
                    });
            }



            // Esperar a que el DOM esté completamente cargado
            document.addEventListener('DOMContentLoaded', function () {
                // Manejador de eventos para el botón de inscribir
                document.querySelectorAll('.inscribir-btn').forEach(button => {
                    button.addEventListener('click', function () {
                        const idCurso = this.getAttribute('data-curso-id');
                        inscribirAlCurso(idCurso, this);
                    });
                });

                // Manejador de eventos para el botón de cancelar inscripción
                document.querySelectorAll('.cancelar-inscripcion-btn').forEach(button => {
                    button.addEventListener('click', function () {
                        const idInscripcion = this.getAttribute('data-inscripcion-id');
                        cancelarInscripcion(idInscripcion, this);
                    });
                });
            });

            // Función para inscribir al usuario en el curso
            function inscribirAlCurso(idCurso, button) {
                const btn = button || event?.target;
                const originalText = btn.innerHTML;

                // Mostrar indicador de carga
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Procesando...';

                // Realizar la petición AJAX
                fetch('{{ route('taller.inscripciones.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ id_curso: idCurso })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Inscripción exitosa!',
                                text: 'Te has inscrito correctamente al curso.',
                                showConfirmButton: false,
                                timer: 2000
                            }).then(() => {
                                // Recargar la página para actualizar el estado
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message || 'Ocurrió un error al procesar tu solicitud',
                                confirmButtonText: 'Entendido'
                            });
                            btn.disabled = false;
                            btn.innerHTML = originalText;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Ocurrió un error al procesar tu solicitud',
                            confirmButtonText: 'Entendido'
                        });
                        btn.disabled = false;
                        btn.innerHTML = originalText;
                    });
            }

            // Función para cancelar la inscripción
            function cancelarInscripcion(idInscripcion, button) {
                const btn = button || event?.target;
                const originalText = btn.innerHTML;

                // Mostrar indicador de carga
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Procesando...';

                // Realizar la petición AJAX
                fetch(`/taller/inscripciones/${idInscripcion}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Inscripción cancelada',
                                text: 'Tu inscripción ha sido cancelada correctamente.',
                                showConfirmButton: false,
                                timer: 2000
                            }).then(() => {
                                // Recargar la página para actualizar el estado
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message || 'Ocurrió un error al cancelar tu inscripción',
                                confirmButtonText: 'Entendido'
                            });
                            btn.disabled = false;
                            btn.innerHTML = originalText;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Ocurrió un error al procesar tu solicitud',
                            confirmButtonText: 'Entendido'
                        });
                        btn.disabled = false;
                        btn.innerHTML = originalText;
                    });
            }
        </script>
    @endpush

    <!-- Modal para ver detalles del contenido -->
@endsection
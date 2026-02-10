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

NOTA: Todas las consultas y cálculos se realizan en el controlador (CursoDetalleController).
      Esta vista solo recibe y presenta los datos.
--}}

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


@section('content')
    <div class="container py-5">
        <div class="row">
            <!-- Columna principal -->
            <div class="col-lg-8">
                <!-- Tarjeta de información principal -->
                <div class="card shadow-sm mb-4 border-0">
                    <div class="card-header bg-gradient-primary text-white">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <h2 class="h4 mb-0">{{ $curso->nombre }}</h2>
                            @if(isset($inscripcion) && $inscripcion && $debeMostrarPromedio)
                                <div class="mt-2 mt-md-0 d-flex align-items-center">
                                    <div class="me-3 text-end">
                                        <div class="small opacity-75">Tu Promedio Actual</div>
                                        <div class="fw-bold fs-5">{{ number_format($promedioEstudiante, 2) }} / 100</div>
                                    </div>
                                    <span
                                        class="badge {{ $promedioEstudiante >= 80 ? 'bg-success' : 'bg-danger' }} px-3 py-2 rounded-pill shadow-sm">
                                        <i
                                            class="fas {{ $promedioEstudiante >= 80 ? 'fa-chart-line' : 'fa-exclamation-triangle' }} me-1"></i>
                                        {{ $promedioEstudiante >= 80 ? 'Por encima del promedio necesario' : 'Por debajo del promedio necesario' }}
                                    </span>
                                </div>
                            @endif
                        </div>
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
                                            {{ $curso->modalidad->nombre_modalidad ?? 'No especificada' }}
                                        </p>
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
                                                    <a href="{{ route('taller.cursos.contenido', ['curso' => $curso->id_curso, 'contenido_id' => $contenido->id_contenido_curso]) }}"
                                                        class="text-decoration-none">
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
                            <button
                                onclick="mostrarContactoProfesor('{{ $curso->persona->nombre_completo ?? 'No disponible' }}', '{{ $curso->persona->user->email ?? 'No disponible' }}', '{{ $curso->persona->user->cell_phone ?? 'No disponible' }}')"
                                class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-envelope me-1"></i> Contactar
                            </button>
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
                        @auth
                            @if($vistaParcial)
                                @include("taller::a.$vistaParcial", [
                                    'curso' => $curso,
                                    'inscripcion' => $inscripcion
                                ])
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function verMotivoRechazo(cursoId, motivo, cursoNombre = '') {
                Swal.fire({
                    html: `
                                <div class="rejection-container text-center">
                                    <!-- Cabecera con icono dinámico -->
                                    <div class="mb-4">
                                        <div class="d-inline-flex align-items-center justify-content-center bg-danger-subtle text-danger rounded-circle mb-3 shadow-sm" style="width: 90px; height: 90px;">
                                            <i class="fas fa-exclamation-circle fa-4x animate__animated animate__pulse animate__infinite"></i>
                                        </div>
                                        <h2 class="fw-bold text-dark mb-1">Propuesta Declinada</h2>
                                        ${cursoNombre ? `<span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill small fw-bold mt-2 shadow-sm">${cursoNombre}</span>` : ''}
                                    </div>

                                    <!-- Caja de observación estilo "Feedback Card" -->
                                    <div class="feedback-card text-start p-4 mb-4 rounded-4 position-relative" style="background: #fffcfc; border: 1px solid #ffebeb; box-shadow: 0 10px 30px rgba(220, 53, 69, 0.05);">
                                        <div class="position-absolute top-0 end-0 p-3 opacity-10">

                                        </div>
                                        <h6 class="text-danger fw-bold text-uppercase small mb-3 letter-spacing-1">
                                            <i></i> Observaciones de Coordinación
                                        </h6>
                                        <div class="observation-text text-secondary" style="font-size: 1.1rem; line-height: 1.7; min-height: 60px; max-height: 300px; overflow-y: auto; white-space: pre-wrap;">${motivo || 'El curso no cumple con los requisitos actuales del programa. Por favor, revise el contenido detalladamente.'}</div>
                                    </div>

                                    <!-- Mensaje de acción -->
                                    <div class="d-flex align-items-center justify-content-center bg-light p-3 rounded-4 mb-2 border border-white shadow-sm">
                                        <div class="me-3 p-2 bg-white rounded-circle">
                                            <i class="fas fa-lightbulb text-warning"></i>
                                        </div>
                                        <p class="text-muted small mb-0 text-start">
                                            Realiza los ajustes solicitados y <strong>vuelve a enviar el curso</strong> desde el botón de edición.
                                        </p>
                                    </div>
                                </div>
                            `,
                    showCloseButton: true,
                    showConfirmButton: true,
                    confirmButtonText: '<i class="fas fa-check-circle me-2"></i> Entendido, corregiré el curso',
                    confirmButtonColor: '#dc3545',
                    buttonsStyling: true,
                    customClass: {
                        popup: 'rounded-5 shadow-2xl border-0',
                        confirmButton: 'btn btn-danger px-5 py-3 rounded-pill fw-bold shadow-lg transform-hover',
                        closeButton: 'focus-ring focus-ring-danger'
                    },
                    width: '520px',
                    padding: '2.5rem',
                    background: '#ffffff',
                    backdrop: `rgba(220, 53, 69, 0.1)`,
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown animate__faster'
                    }
                });
            }

            function mostrarContactoProfesor(nombre, email, telefono) {
                Swal.fire({
                    title: '<span class="fw-bold">Datos de Contacto</span>',
                    html: `
                                <div class="text-center mb-4">
                                    <div class="avatar-lg mb-3 mx-auto">
                                        <img src="{{ asset('assets/img/avatar.png') }}" alt="Profesor" class="rounded-circle img-thumbnail" style="width: 100px; height: 100px;">
                                    </div>
                                    <h4 class="text-primary mb-1">${nombre}</h4>
                                    <p class="text-muted">Instructor del Curso</p>
                                </div>
                                <div class="card border-0 bg-light shadow-sm">
                                    <div class="card-body text-start p-4">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="bg-white p-2 rounded-circle shadow-sm me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-envelope text-primary"></i>
                                            </div>
                                            <div>
                                                <small class="text-muted d-block">Correo Electrónico</small>
                                                <span class="fw-bold text-dark">${email}</span>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-white p-2 rounded-circle shadow-sm me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-phone text-success"></i>
                                            </div>
                                            <div>
                                                <small class="text-muted d-block">Teléfono / WhatsApp</small>
                                                <span class="fw-bold text-dark">${telefono}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-muted small mt-3">
                                        <i class="fas fa-info-circle me-1"></i> Por favor, contacta al profesor solo en horarios administrativos.
                                    </p>
                                </div>
                            `,
                    showCloseButton: true,
                    confirmButtonText: 'Cerrar',
                    confirmButtonColor: '#5e72e4',
                    customClass: {
                        popup: 'rounded-4 shadow-lg',
                        confirmButton: 'btn btn-primary px-5 rounded-pill'
                    }
                });
            }
            function RechazarContenido(idCurso, btnElement) {
                const btn = btnElement || event?.target;
                const originalText = btn.innerHTML;

                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

                Swal.fire({
                    title: '<div class="text-center mb-2"><i class="fas fa-file-signature text-danger fa-2x mb-3 animate__animated animate__shakeX"></i><h3 class="fw-bold">Declinar Propuesta</h3></div>',
                    html: '<p class="text-muted">Por favor, detalla los motivos para rechazar esta propuesta. Esta observación será visible para el facilitador.</p>',
                    input: 'textarea',
                    inputPlaceholder: 'Escriba las observaciones detalladamente aquí...',
                    inputAttributes: {
                        'aria-label': 'Motivo del rechazo',
                        'style': 'height: 280px; width: 100% !important; border-radius: 20px; border: 2px solid #495057; padding: 20px; background-color: #fffefe; font-size: 1.1rem; margin: 0 auto; display: block; box-shadow: inset 0 4px 10px rgba(0,0,0,0.03), 0 10px 25px rgba(220, 53, 69, 0.08);'
                    },
                    showCancelButton: true,
                    confirmButtonText: '<i class="fas fa-times-circle me-2"></i> Confirmar Rechazo',
                    cancelButtonText: 'Regresar',
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#f8f9fa',
                    showLoaderOnConfirm: true,
                    customClass: {
                        popup: 'rounded-5 shadow-2xl border-0',
                        confirmButton: 'btn btn-danger px-5 py-3 rounded-pill fw-bold transform-hover',
                        cancelButton: 'btn btn-light px-5 py-3 rounded-pill fw-bold text-muted border ms-2',
                        input: 'form-control shadow-none border-0 mx-0 w-100'
                    },
                    width: '850px',
                    padding: '2rem 3rem',
                    backdrop: `rgba(220, 53, 69, 0.1)`,

                    inputValidator: (value) => {
                        if (!value || value.trim().length < 10) {
                            return 'Por favor, ingrese un motivo más descriptivo (mínimo 10 caracteres)';
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
                            Swal.showValidationMessage(`Error en el servidor: ${error.message}`);
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
                            title: '<h4 class="fw-bold">Acción Registrada</h4>',
                            text: 'La propuesta ha sido declinada y el facilitador ha sido notificado.',
                            timer: 3000,
                            showConfirmButton: false,
                            customClass: {
                                popup: 'rounded-4 shadow-lg'
                            }
                        }).then(() => {
                            window.location.reload();
                        });
                    }

                }).catch(() => {
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                });
            }
            function FinalizarCurso(idCurso) {

                fetch('{{ route("taller.cursos.updateStatus", ["curso" => $curso->id_curso]) }}', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        id_estado: 8 // El ID del estado al que quieres cambiar
                    })

                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Finalizado el curso!',
                                text: 'El curso ha sido finalizado exitosamente.',
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
            function CerrarCurso(idCurso) {

                fetch('{{ route("taller.cursos.updateStatus", ["curso" => $curso->id_curso]) }}', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        id_estado: 9 // El ID del estado al que quieres cambiar
                    })

                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Curso cerrado!',
                                text: 'El curso ha sido cerrado exitosamente.',
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

            function FinalizarInscripciones(idCurso) {

                fetch('{{ route("taller.cursos.updateStatus", ["curso" => $curso->id_curso]) }}', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        id_estado: 7 // El ID del estado al que quieres cambiar
                    })

                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Finalizada las inscripciones!',
                                text: 'Las inscripciones han sido finalizadas exitosamente.',
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
                        if (error.status === 403) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'El curso ya ha iniciado, no se puede gestionar el cupo.',
                                confirmButtonText: 'Entendido'
                            });
                        } else {
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Ocurrió un error al procesar tu solicitud',
                                confirmButtonText: 'Entendido'
                            });
                        }
                        btn.disabled = false;
                        btn.innerHTML = originalText;
                    });
            }
        </script>
    @endpush

    <!-- Modal para ver detalles del contenido -->
@endsection
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
                                        <h6 class="mb-1">
                                            <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#contenidoModal" 
                                               data-titulo="{{ $contenido->titulo }}"
                                               data-descripcion="{{ $contenido->descripcion }}"
                                               data-recurso="{{ $contenido->url_contenido ?? '' }}"
                                               data-tipo="{{ $contenido->tipo_contenido ?? 'enlace' }}">
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
                        <img src="{{ asset('assets/img/avatar.png') }}" 
                             alt="Instructor" 
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
                            <span class="fw-bold">{{ $curso->fecha_inicio ? \Carbon\Carbon::parse($curso->fecha_inicio)->format('d/m/Y') : 'Por definir' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span><i class="fas fa-calendar-check text-muted me-2"></i> Fecha de fin</span>
                            <span class="fw-bold">{{ $curso->fecha_fin ? \Carbon\Carbon::parse($curso->fecha_fin)->format('d/m/Y') : 'Por definir' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span><i class="fas fa-users text-muted me-2"></i> Cupos</span>
                            <span class="badge bg-primary rounded-pill">{{ $curso->cantidad_cupos ?? 'Por definir' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span><i class="fas fa-tag text-muted me-2"></i> Categoría</span>
                            <span class="fw-bold">{{ $curso->categoria ?? 'General' }}</span>
                        </li>
                    </ul>
                </div>
                <div class="card-footer bg-white border-top-0">
                    @auth
                        @php
                            // Obtener el ID de la persona desde los datos personales
                            $user = auth()->user();
                            $personalData = \Modules\Comun\Entities\PersonalData::where('document', $user->document)->first();
                            $idPersona = $personalData ? $personalData->id : null;
                            
                            // El faciliatador debe acetar el curso
                            $PorAceptar = $curso->estado_actual->id_estado == 1;

                            // Verificar si el usuario es el coordinador
                            $esCoordinador = $user->profile_id == 4;
                          
                            // Verificar si el usuario es el instructor del curso
                            $esFacilitador = $curso->id_persona == $idPersona;
                            
                            // Verificar si el curso está en edicion
                            $EnEdicion = $curso->estado_actual->id_estado == 4;
                            
                            // Verificar si el curso está en evaluación por coordinacion     
                            $EnAprobacion = $curso->estado_actual->id_estado == 5;
                            
                            // Verificar si el usuario ya está inscrito
                            $inscripcion = $idPersona ? 
                                \Modules\Taller\Entities\Inscripcion::where('id_curso', $curso->id_curso)
                                    ->where('id_persona', $idPersona)
                                    ->first() : null;
                        @endphp
                        @if($PorAceptar && $esFacilitador)
                            <a class="btn btn-info w-100 mb-2" disabled>
                                <i class="fas fa-user-tie me-2"></i> Eres el instructor de este curso
                            </a>
                            <button class="btn btn-success w-100 mb-2" onclick="updateStatus({{ $curso->id_curso }})">
                                <i class="fas fa-user-tie me-2"></i> Aceptar Curso
                            </button>
                        @elseif ($EnEdicion)
                        <a class="btn btn-warning w-100 mb-2" disabled>
                            Curso siendo evaluado por el Facilitador    
                        </a>
                        @elseif($esFacilitador && $EnEdicion)
                            <a class="btn btn-info w-100 mb-2" disabled>
                                <i class="fas fa-user-tie me-2"></i> Eres el instructor de este curso
                            </a>
                            <a class="btn btn-warning w-100 mb-2" href="{{ route('taller.cursos.edit', $curso->id_curso) }}">
                                <i class="fas fa-user-tie me-2"></i> Editar curso 
                            </a>
                            <button onclick="finalizarEdicion({{ $curso->id_curso }})" class="btn btn-primary w-100 mb-2">
                                Finalizar Edición
                            </button>
                        @elseif($esFacilitador && $EnAprobacion)
                            <a class="btn btn-info w-100 mb-2" disabled>
                                <i class="fas fa-user-tie me-2"></i> Eres el instructor de este curso
                            </a>
                            <button class="btn btn-warning w-100 mb-2" disabled>
                                <i class="fas fa-user-tie me-2"></i> Curso en evaluacion   
                            </button>
                        @elseif($EnAprobacion && $esCoordinador)
                            <button class="btn btn-success w-100 mb-2" onclick="updateStatus({{ $curso->id_curso }})">
                                <i class="fas fa-user-tie me-2"></i> Aprobar Contenido
                            </button>
                        @elseif($EnAprobacion)
                            <a class="btn btn-info w-100 mb-2" disabled>
                                <i class="fas fa-user-tie me-2"></i> Curso en evaluacion   
                            </a>
                        
                        @elseif($esFacilitador)
                            <a class="btn btn-info w-100 mb-2" disabled>
                                <i class="fas fa-user-tie me-2"></i> Eres el instructor de este curso
                            </a>
                        @elseif($inscripcion)
                            <a class="btn btn-success w-100 mb-2" disabled>
                                <i class="fas fa-check-circle me-2"></i> Ya estás inscrito
                            </a>
                            <button class="btn btn-outline-danger w-100 mb-2 cancelar-inscripcion-btn" 
                                data-inscripcion-id="{{ $inscripcion->id_inscripcion }}">
                                <i class="fas fa-times-circle me-2"></i> Cancelar inscripción
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


    // Función para actualizar el estado del curso
function updateStatus(idCurso) {
   

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
    document.addEventListener('DOMContentLoaded', function() {
        // Manejador de eventos para el botón de inscribir
        document.querySelectorAll('.inscribir-btn').forEach(button => {
            button.addEventListener('click', function() {
                const idCurso = this.getAttribute('data-curso-id');
                inscribirAlCurso(idCurso, this);
            });
        });

        // Manejador de eventos para el botón de cancelar inscripción
        document.querySelectorAll('.cancelar-inscripcion-btn').forEach(button => {
            button.addEventListener('click', function() {
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
<div class="modal fade" id="contenidoModal" tabindex="-1" aria-labelledby="contenidoModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="contenidoModalLabel">Detalles del Contenido</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <h4 id="modalTitulo" class="mb-4 text-center"></h4>
                <div id="modalDescripcion" class="lead mb-4"></div>
                <div id="modalRecurso" class="text-center mt-4">
                    <!-- Aquí se mostrará el botón de descarga si hay recurso disponible -->
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<style>
    /* Estilos adicionales para el modal */
    #contenidoModal .modal-content {
        border: none;
        border-radius: 10px;
        box-shadow: 0 5px 30px rgba(0, 0, 0, 0.2);
    }
    
    #contenidoModal .modal-header {
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    #contenidoModal .btn-descargar {
        background: linear-gradient(87deg, #2dce89 0, #2dcecc 100%);
        border: none;
        padding: 10px 25px;
        border-radius: 50px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    #contenidoModal .btn-primary {
        background: linear-gradient(87deg, #5e72e4 0, #825ee4 100%);
        border: none;
        padding: 10px 25px;
        border-radius: 50px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    #contenidoModal .btn-descargar:hover,
    #contenidoModal .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(94, 114, 228, 0.4);
    }
    
    #contenidoModal .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
    }
</style>

<script>
    // Script para manejar el modal de contenido
    document.addEventListener('DOMContentLoaded', function() {
        const contenidoModal = document.getElementById('contenidoModal');
        
        if (contenidoModal) {
            contenidoModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const titulo = button.getAttribute('data-titulo');
                const descripcion = button.getAttribute('data-descripcion');
                const recurso = button.getAttribute('data-recurso');
                
                const modalTitle = contenidoModal.querySelector('.modal-title');
                const modalTitulo = contenidoModal.querySelector('#modalTitulo');
                const modalDescripcion = contenidoModal.querySelector('#modalDescripcion');
                const modalRecurso = contenidoModal.querySelector('#modalRecurso');
                
                // Actualizar el contenido del modal
                modalTitle.textContent = titulo;
                modalTitulo.textContent = titulo;
                modalDescripcion.innerHTML = descripcion.replace(/\n/g, '<br>'); // Mantener saltos de línea
                
                // Manejar el recurso según su tipo
                if (recurso && recurso !== '') {
                    const tipo = button.getAttribute('data-tipo') || 'enlace';
                    const esArchivo = tipo === 'archivo' || 
                                    recurso.match(/\.(pdf|docx?|xlsx?|pptx?|zip|rar|7z|jpg|jpeg|png|gif|mp4|mp3)$/i);
                    
                    if (esArchivo) {
                        // Si es un archivo, mostrar botón de descarga
                        modalRecurso.innerHTML = `
                            <a href="${recurso}" class="btn btn-descargar text-white" download>
                                <i class="fas fa-download me-2"></i>Descargar Archivo
                            </a>
                            <p class="text-muted mt-2 small">Haz clic para descargar el material de apoyo</p>
                        `;
                    } else {
                        // Si es un enlace, mostrar botón para ir al contenido
                        const esVideo = recurso.match(/youtube\.com|vimeo\.com|dailymotion\.com|youtu\.be/i);
                        const esEnlaceExterno = !recurso.startsWith('#');
                        const target = esEnlaceExterno ? 'target="_blank"' : '';
                        const icono = esVideo ? 'fa-play-circle' : 'fa-external-link-alt';
                        const texto = esVideo ? 'Ver Video' : 'Ir al Contenido';
                        
                        modalRecurso.innerHTML = `
                            <a href="${recurso}" class="btn btn-primary text-white" ${target}>
                                <i class="fas ${icono} me-2"></i>${texto}
                            </a>
                            <p class="text-muted mt-2 small">
                                ${esEnlaceExterno ? 'Se abrirá en una nueva pestaña' : 'Verás el contenido aquí mismo'}
                            </p>
                        `;
                    }
                } else {
                    modalRecurso.innerHTML = ''; // No mostrar nada si no hay recurso
                }
            });
            
            // Limpiar el modal al cerrar
            contenidoModal.addEventListener('hidden.bs.modal', function() {
                const modalRecurso = contenidoModal.querySelector('#modalRecurso');
                modalRecurso.innerHTML = '';
            });
        }
    });
</script>
@endpush

@endsection
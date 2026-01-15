@extends('layouts.kaiadmin-menu')

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <div class="card shadow mb-4">
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('taller.cursos.update', $curso->id_curso) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nombre">Nombre del Curso *</label>
                                    <input type="text" class="form-control bg-light" id="nombre" name="nombre"
                                        value="{{ old('nombre', $curso->nombre) }}" readonly>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="modalidad_nombre">Modalidad *</label>
                                    @php
                                        $modalidadActual = $modalidades->firstWhere('id_modalidad', $curso->id_modalidad);
                                        $nombreModalidad = $modalidadActual ? $modalidadActual->nombre_modalidad : 'Modalidad no especificada';
                                    @endphp
                                    <input type="hidden" name="id_modalidad" value="{{ $curso->id_modalidad }}">
                                    <input type="text" class="form-control bg-light" id="modalidad_nombre"
                                        value="{{ $nombreModalidad }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion"
                                rows="3">{{ old('descripcion', $curso->descripcion) }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="duracion">Duración (semanas) *</label>
                                    <input type="text" class="form-control bg-light" id="duracion" name="duracion"
                                        value="{{ old('duracion', $curso->duracion) }}" readonly>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="horas">Horas totales</label>
                                    <input type="text" class="form-control bg-light" id="horas" name="horas"
                                        value="{{ old('horas', $curso->horas) }}" readonly>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="cantidad_cupos">Cupos disponibles</label>
                                    <input type="number" class="form-control" id="cantidad_cupos" name="cantidad_cupos"
                                        value="{{ old('cantidad_cupos', $curso->cantidad_cupos) }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fecha_inicio">Fecha de inicio</label>
                                    <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio"
                                        value="{{ old('fecha_inicio', $curso->fecha_inicio ? $curso->fecha_inicio->format('Y-m-d') : '') }}"
                                        readonly>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fecha_fin">Fecha de finalización</label>
                                    <input type="date" class="form-control" id="fecha_fin" name="fecha_fin"
                                        value="{{ old('fecha_fin', $curso->fecha_fin ? $curso->fecha_fin->format('Y-m-d') : '') }}"
                                        readonly>
                                </div>
                            </div>
                        </div>

                        <div class="card mt-4">
                            <div class="card-header">
                                <h5 class="card-title">Contenidos del Curso</h5>
                            </div>
                            <div class="card-body">
                                <div id="contenidos-container">
                                    @php $tiposEvaluacionJson = json_encode($tiposEvaluacion); @endphp
                                    @foreach($contenidos as $index => $contenido)
                                        <div class="contenido-item mb-3 border p-3 bg-light rounded" data-index="{{ $index }}">
                                            <input type="hidden" name="contenidos[{{ $index }}][id]"
                                                value="{{ $contenido->id_contenido_curso }}">

                                            <!-- Fila 1: Datos Generales -->
                                            <div class="row g-3">
                                                <div class="col-md-2 d-flex align-items-center pt-3">
                                                    <div class="form-check form-switch ps-0">
                                                        <input type="hidden" name="contenidos[{{ $index }}][es_evaluacion]" value="0">
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" class="custom-control-input" 
                                                                   id="evalSwitch_{{ $index }}" 
                                                                   name="contenidos[{{ $index }}][es_evaluacion]" 
                                                                   value="1" 
                                                                   onchange="toggleEvaluacion(this)"
                                                                   {{ $contenido->es_evaluacion ? 'checked' : '' }}>
                                                            <label class="custom-control-label small fw-bold text-muted" for="evalSwitch_{{ $index }}">
                                                                <i></i> Evaluable
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mb-0">
                                                        <label class="small text-muted fw-bold">Título</label>
                                                        <input type="text" name="contenidos[{{ $index }}][titulo]"
                                                            class="form-control" value="{{ $contenido->titulo }}" required
                                                            placeholder="Ej: Introducción al tema">
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="form-group mb-0">
                                                        <label class="small text-muted fw-bold">URL / Enlace</label>
                                                        <input type="url" name="contenidos[{{ $index }}][url_contenido]"
                                                            class="form-control" value="{{ $contenido->url_contenido }}"
                                                            required placeholder="https://...">
                                                    </div>
                                                </div>
                                                <div class="col-md-1 d-flex align-items-end justify-content-center">
                                                    <button type="button"
                                                        class="btn btn-outline-danger btn-sm remove-contenido border-0"
                                                        title="Eliminar">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Fila 2: Campos de Evaluación (Ocultos por defecto) -->
                                            <div class="row g-3 mt-2 evaluacion-fields border-top pt-3 bg-white mx-0 rounded pb-2 mb-2"
                                                style="{{ !$contenido->es_evaluacion ? 'display:none' : '' }}">
                                                <div class="col-12"><span class="badge bg-warning text-dark mb-2"><i
                                                            class="fas fa-star me-1"></i> Configuración de Evaluación</span>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mb-0">
                                                        <label class="small text-muted fw-bold">Tipo de Evaluación</label>
                                                        <select name="contenidos[{{ $index }}][id_tipo_evaluacion]"
                                                            class="form-select">
                                                            <option value="">Seleccione...</option>
                                                            @foreach($tiposEvaluacion as $tipo)
                                                                <option value="{{ $tipo->id_tipo_evaluacion }}" {{ $contenido->id_tipo_evaluacion == $tipo->id_tipo_evaluacion ? 'selected' : '' }}>
                                                                    {{ $tipo->nombre }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group mb-0">
                                                        <label class="small text-muted fw-bold">Ponderación (%)</label>
                                                        <div class="input-group">
                                                            <input type="number" name="contenidos[{{ $index }}][ponderacion]"
                                                                class="form-control" value="{{ $contenido->ponderacion }}"
                                                                min="0" max="100" placeholder="0">
                                                            <span class="input-group-text">%</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-5 d-flex align-items-center">
                                                    <small class="text-muted"><i class="fas fa-info-circle me-1"></i> Esta nota
                                                        se sumará al promedio final.</small>
                                                </div>
                                            </div>

                                            <!-- Fila 3: Detalles -->
                                            <div class="row mt-2">
                                                <div class="col-md-1">
                                                    <div class="form-group mb-0">
                                                        <label class="small text-muted fw-bold">Orden</label>
                                                        <input type="number" name="contenidos[{{ $index }}][orden]"
                                                            class="form-control form-control-sm"
                                                            value="{{ $contenido->orden ?? $loop->index + 1 }}" min="1">
                                                    </div>
                                                </div>
                                                <div class="col-md-11">
                                                    <div class="form-group mb-0">
                                                        <label class="small text-muted fw-bold">Descripción (Opcional)</label>
                                                        <input type="text" name="contenidos[{{ $index }}][descripcion_breve]"
                                                            class="form-control form-control-sm"
                                                            value="{{ $contenido->descripcion_breve }}"
                                                            placeholder="Breve descripción del contenido...">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="text-end mt-3">
                                    <button type="button" id="agregar-contenido"
                                        class="btn btn-outline-primary shadow-sm hover-scale">
                                        <i class="fas fa-plus-circle me-2"></i> Agregar Nuevo Contenido
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="card-action mt-4 d-flex justify-content-between align-items-center">
                            <a href="{{ route('taller.cursos.show', $curso->id_curso) }}"
                                class="btn btn-link text-muted text-decoration-none">
                                <i class="fas fa-arrow-left me-2"></i> Cancelar y Volver
                            </a>
                            <button type="submit" class="btn btn-success px-4 fw-bold shadow-sm">
                                <i class="fas fa-save me-2"></i> Guardar Todos los Cambios
                            </button>
                        </div>
                    </form>

                    @push('scripts')
                        <script>
                            const tiposEvaluacion = {!! $tiposEvaluacionJson !!};

                            function getTipoOptions() {
                                return tiposEvaluacion.map(t => `<option value="${t.id_tipo_evaluacion}">${t.nombre}</option>`).join('');
                            }
                            function toggleEvaluacion(inputElement) {
                                const container = inputElement.closest('.contenido-item');
                                const evalFields = container.querySelector('.evaluacion-fields');
                                
                                // Verificar si es checkbox (checked) o select (value)
                                const isActive = inputElement.type === 'checkbox' ? inputElement.checked : inputElement.value == '1';

                                if(isActive) {
                                    evalFields.style.display = 'flex';
                                    evalFields.style.opacity = 0;
                                    setTimeout(() => { evalFields.style.opacity = 1; }, 50);
                                } else {
                                    evalFields.style.display = 'none';
                                    evalFields.querySelectorAll('input, select').forEach(el => el.value = '');
                                }
                            }

                            document.addEventListener('DOMContentLoaded', function () {
                                let contadorContenidos = {{ count($contenidos) }};

                                // Agregar nuevo contenido
                                document.getElementById('agregar-contenido').addEventListener('click', function () {
                                    const contenedor = document.getElementById('contenidos-container');
                                    const nuevoIndice = contadorContenidos++;

                                    const nuevoContenido = `
                                    <div class="contenido-item mb-3 border p-3 bg-light rounded animate__animated animate__fadeIn" data-index="${nuevoIndice}">
                                        <input type="hidden" name="contenidos[${nuevoIndice}][id]" value="">
                                        
                                        <div class="row g-3">
                                            <div class="col-md-2 d-flex align-items-center pt-3">
                                                <div class="form-check form-switch ps-0">
                                                    <input type="hidden" name="contenidos[${nuevoIndice}][es_evaluacion]" value="0">
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" class="custom-control-input" 
                                                               id="evalSwitch_${nuevoIndice}" 
                                                               name="contenidos[${nuevoIndice}][es_evaluacion]" 
                                                               value="1" 
                                                               onchange="toggleEvaluacion(this)">
                                                        <label class="custom-control-label small fw-bold text-muted" for="evalSwitch_${nuevoIndice}">
                                                            <i class="fas fa-star me-1 text-warning"></i> Evaluable
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-0">
                                                    <label class="small text-muted fw-bold">Título</label>
                                                    <input type="text" name="contenidos[${nuevoIndice}][titulo]" class="form-control" required placeholder="Título del contenido">
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="form-group mb-0">
                                                    <label class="small text-muted fw-bold">URL / Enlace</label>
                                                    <input type="url" name="contenidos[${nuevoIndice}][url_contenido]" class="form-control" required placeholder="https://...">
                                                </div>
                                            </div>
                                            <div class="col-md-1 d-flex align-items-end justify-content-center">
                                                <button type="button" class="btn btn-outline-danger btn-sm remove-contenido border-0" title="Eliminar">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Campos de Evaluación -->
                                        <div class="row g-3 mt-2 evaluacion-fields border-top pt-3 bg-white mx-0 rounded pb-2 mb-2" style="display:none; transition: all 0.3s ease;">
                                            <div class="col-12"><span class="badge bg-warning text-dark mb-2"><i class="fas fa-star me-1"></i> Configuración de Evaluación</span></div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-0">
                                                    <label class="small text-muted fw-bold">Tipo de Evaluación</label>
                                                    <select name="contenidos[${nuevoIndice}][id_tipo_evaluacion]" class="form-select">
                                                        <option value="">Seleccione...</option>
                                                        ${getTipoOptions()}
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group mb-0">
                                                    <label class="small text-muted fw-bold">Ponderación (%)</label>
                                                    <div class="input-group">
                                                        <input type="number" name="contenidos[${nuevoIndice}][ponderacion]" class="form-control" min="0" max="100" placeholder="0">
                                                        <span class="input-group-text">%</span>
                                                    </div>
                                                </div>
                                            </div>
                                             <div class="col-md-5 d-flex align-items-center">
                                                 <small class="text-muted">La nota de esta actividad se promediará.</small>
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <div class="col-md-1">
                                                    <div class="form-group mb-0">
                                                            <label class="small text-muted fw-bold">Orden</label>
                                                            <input type="number" name="contenidos[${nuevoIndice}][orden]" class="form-control form-control-sm" value="${nuevoIndice + 1}" min="1">
                                                    </div>
                                            </div>
                                            <div class="col-md-11">
                                                <div class="form-group mb-0">
                                                    <label class="small text-muted fw-bold">Descripción (Opcional)</label>
                                                    <input type="text" name="contenidos[${nuevoIndice}][descripcion_breve]" class="form-control form-control-sm" placeholder="Descripción breve...">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                `;

                                    contenedor.insertAdjacentHTML('beforeend', nuevoContenido);
                                });

                                // Eliminar contenido
                                document.addEventListener('click', function (e) {
                                    if (e.target.closest('.remove-contenido')) {
                                        if (confirm('¿Estás seguro de eliminar este contenido?')) {
                                            const item = e.target.closest('.contenido-item');
                                            item.style.opacity = '0';
                                            setTimeout(() => item.remove(), 300);
                                        }
                                    }
                                });
                            });
                        </script>
                    @endpush

@endsection
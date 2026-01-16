@extends('layouts.kaiadmin-menu')

@push('styles')
    <style>
        .cursor-pointer { cursor: pointer; }
        .hover-bg-light:hover { background-color: #f8f9fa !important; }
        .bg-primary-subtle { background-color: rgba(13, 110, 253, 0.1); }
    </style>
@endpush

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
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Crear Nuevo Curso</h6>
                </div>
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

                    <form action="{{ route('taller.cursos.store_new') }}" method="POST">
                        @csrf

                        <!-- Fila 1: Nombre y Modalidad -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nombre">Nombre del Curso *</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre"
                                        value="{{ old('nombre') }}" required placeholder="Ingrese el nombre del curso">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="id_modalidad">Modalidad *</label>
                                    <select name="id_modalidad" id="id_modalidad" class="form-control" required>
                                        <option value="">Seleccione una modalidad...</option>
                                        @foreach($modalidades as $modalidad)
                                            <option value="{{ $modalidad->id_modalidad }}" {{ old('id_modalidad') == $modalidad->id_modalidad ? 'selected' : '' }}>
                                                {{ $modalidad->nombre_modalidad }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Fila: Facilitador con Búsqueda Integrada -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group position-relative" id="custom-select-container">
                                    <label for="buscador_facilitador">Facilitador Asignado *</label>
                                    
                                    <!-- Input Oculto para el valor real (Form Post) -->
                                    <input type="hidden" name="id_persona" id="id_persona" value="{{ old('id_persona') }}" required>

                                    <!-- Input Principal: Actúa como Buscador y Display -->
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                                        <input type="text" class="form-control border-start-0 ps-0" 
                                               id="buscador_facilitador" 
                                               placeholder="Escriba para buscar por nombre o cédula..." 
                                               autocomplete="off"
                                               onfocus="showFacilitatorDropdown()"
                                               style="box-shadow: none;">
                                        <span class="input-group-text bg-white" onclick="toggleFacilitatorDropdown()" style="cursor: pointer;">
                                            <i class="fas fa-chevron-down text-muted"></i>
                                        </span>
                                    </div>

                                    <!-- Dropdown de Resultados -->
                                    <div id="facilitator-dropdown" class="shadow-lg rounded-bottom border border-top-0 d-none" 
                                         style="position: absolute; width: 100%; z-index: 1050; background: white; margin-top: -1px;">
                                        
                                        <!-- Lista de Opciones -->
                                        <div id="facilitator-list" style="max-height: 250px; overflow-y: auto;">
                                            @foreach($facilitadores as $facilitador)
                                                <div class="facilitator-option p-3 border-bottom border-light cursor-pointer hover-bg-light"
                                                     onclick="selectFacilitator('{{ $facilitador->personalData->id }}', '{{ $facilitador->personalData->primer_nombre }} {{ $facilitador->personalData->primer_apellido }}', '{{ $facilitador->personalData->document }}')"
                                                     data-name="{{ strtolower($facilitador->personalData->primer_nombre . ' ' . $facilitador->personalData->primer_apellido) }}"
                                                     data-doc="{{ $facilitador->personalData->document }}">
                                                    
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                            <i class="fas fa-user"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0 text-dark font-weight-bold">
                                                                {{ $facilitador->personalData->primer_nombre }} {{ $facilitador->personalData->primer_apellido }}
                                                            </h6>
                                                            <small class="text-muted">
                                                                <i class="fas fa-id-card me-1"></i> {{ $facilitador->personalData->document }} 
                                                                <span class="mx-1">•</span> 
                                                                {{ $facilitador->email }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                            
                                            <!-- Empty State -->
                                            <div id="no-results" class="p-4 text-center text-muted d-none">
                                                <i class="fas fa-search mb-2"></i><br>
                                                No se encontraron resultados para su búsqueda
                                            </div>
                                        </div>
                                    </div>
                                    @error('id_persona')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="descripcion">Descripción del Curso</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3"
                                placeholder="Descripción detallada del curso...">{{ old('descripcion') }}</textarea>
                        </div>

                        <!-- Fila 2: Detalles Numéricos -->
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="duracion">Duración (días) *</label>
                                    <input type="number" class="form-control" id="duracion" name="duracion"
                                        value="{{ old('duracion') }}" min="1" required placeholder="Ej: 30">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="horas">Horas totales</label>
                                    <input type="number" class="form-control" id="horas" name="horas"
                                        value="{{ old('horas') }}" min="1" placeholder="Ej: 40">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="cantidad_cupos">Cupos disponibles</label>
                                    <input type="number" class="form-control" id="cantidad_cupos" name="cantidad_cupos"
                                        value="{{ old('cantidad_cupos') }}" min="0" placeholder="Ej: 20">
                                </div>
                            </div>
                        </div>

                        <!-- Fila 3: Fechas -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fecha_inicio">Fecha de inicio</label>
                                    <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio"
                                        value="{{ old('fecha_inicio') }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fecha_fin">Fecha de finalización</label>
                                    <input type="date" class="form-control" id="fecha_fin" name="fecha_fin"
                                        value="{{ old('fecha_fin') }}">
                                </div>
                            </div>
                        </div>

                        <div class="card mt-4">
                            <div class="card-header">
                                <h5 class="card-title">Contenidos del Curso (Opcional)</h5>
                            </div>
                            <div class="card-body">
                                <div id="contenidos-container">
                                    {{-- Contenidos dinámicos --}}
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
                            <a href="{{ route('taller.cursos.index') }}"
                                class="btn btn-link text-muted text-decoration-none">
                                <i class="fas fa-arrow-left me-2"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm">
                                <i class="fas fa-save me-2"></i> Crear Curso
                            </button>
                        </div>
                    </form>

                    @push('scripts')
                        <script>
                            // --- Custom Facilitator Dropdown Logic ---
                            function toggleFacilitatorDropdown() {
                                const dropdown = document.getElementById('facilitator-dropdown');
                                dropdown.classList.toggle('d-none');
                            }

                            function showFacilitatorDropdown() {
                                document.getElementById('facilitator-dropdown').classList.remove('d-none');
                            }

                            function selectFacilitator(id, name, documentNumber) {
                                document.getElementById('id_persona').value = id;
                                // Mostrar nombre seleccionado en el input principal
                                document.getElementById('buscador_facilitador').value = `${name} - ${documentNumber}`;
                                document.getElementById('facilitator-dropdown').classList.add('d-none');
                            }

                            const searchInput = document.getElementById('buscador_facilitador');
                            
                            // Setup Search Filtering
                            searchInput.addEventListener('keyup', function() {
                                // Si el usuario escribe, borramos la selección anterior para obligar a reseleccionar
                                document.getElementById('id_persona').value = '';
                                
                                const searchText = this.value.toLowerCase();
                                const items = document.querySelectorAll('.facilitator-option');
                                let hasVisible = false;

                                showFacilitatorDropdown(); // Asegurar que se ve al escribir

                                items.forEach(item => {
                                    const name = item.getAttribute('data-name');
                                    const doc = item.getAttribute('data-doc');

                                    if (name.includes(searchText) || doc.includes(searchText)) {
                                        item.style.display = 'block';
                                        hasVisible = true;
                                    } else {
                                        item.style.display = 'none';
                                    }
                                });

                                const noResults = document.getElementById('no-results');
                                if (hasVisible) {
                                    noResults.classList.add('d-none');
                                } else {
                                    noResults.classList.remove('d-none');
                                }
                            });

                            // Close on Click Outside
                            document.addEventListener('click', function(e) {
                                const container = document.getElementById('custom-select-container');
                                if (container && !container.contains(e.target)) {
                                    document.getElementById('facilitator-dropdown').classList.add('d-none');
                                }
                            });
                            // --- End Custom Dropdown Logic ---


                            // Usamos json_encode para pasar los tipos de evaluación a JS
                            const tiposEvaluacion = {!! json_encode($tiposEvaluacion) !!};

                            function getTipoOptions() {
                                return tiposEvaluacion.map(t => `<option value="${t.id_tipo_evaluacion}">${t.nombre}</option>`).join('');
                            }

                            function toggleEvaluacion(inputElement) {
                                const container = inputElement.closest('.contenido-item');
                                const evalFields = container.querySelector('.evaluacion-fields');

                                const isActive = inputElement.type === 'checkbox' ? inputElement.checked : inputElement.value == '1';

                                if (isActive) {
                                    evalFields.style.display = 'flex';
                                    evalFields.style.opacity = 0;
                                    setTimeout(() => { evalFields.style.opacity = 1; }, 50);
                                    evalFields.querySelectorAll('input, select').forEach(el => el.disabled = false);
                                } else {
                                    evalFields.style.display = 'none';
                                    evalFields.querySelectorAll('input, select').forEach(el => {
                                        el.disabled = true;
                                        el.value = '';
                                    });
                                }
                            }

                            document.addEventListener('DOMContentLoaded', function () {
                                let contadorContenidos = 0;

                                // Agregar nuevo contenido
                                document.getElementById('agregar-contenido').addEventListener('click', function () {
                                    const contenedor = document.getElementById('contenidos-container');
                                    const nuevoIndice = contadorContenidos++;

                                    const nuevoContenido = `
                                            <div class="contenido-item mb-3 border p-3 bg-light rounded animate__animated animate__fadeIn" data-index="${nuevoIndice}">

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
                                                            <label class="small text-muted fw-bold">Título *</label>
                                                            <input type="text" name="contenidos[${nuevoIndice}][titulo]" class="form-control" required placeholder="Título del contenido">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="form-group mb-0">
                                                            <label class="small text-muted fw-bold">URL / Enlace *</label>
                                                            <input type="url" name="contenidos[${nuevoIndice}][url_contenido]" class="form-control" required placeholder="https://...">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1 d-flex align-items-end justify-content-center">
                                                        <button type="button" class="btn btn-outline-danger btn-sm remove-contenido border-0" title="Eliminar">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                                <!-- Descripción Breve y Detallada -->
                                                <div class="row mt-2">
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-0">
                                                            <label class="small text-muted fw-bold">Descripción Breve (Opcional)</label>
                                                            <input type="text" name="contenidos[${nuevoIndice}][descripcion_breve]" class="form-control form-control-sm" placeholder="Resumen corto..." maxlength="190">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                         <div class="form-group mb-0">
                                                            <label class="small text-muted fw-bold">Descripción Detallada (Opcional)</label>
                                                            <textarea name="contenidos[${nuevoIndice}][descripcion]" class="form-control form-control-sm" rows="1" placeholder="Detalles adicionales..."></textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Campos de Evaluación -->
                                                <div class="row g-3 mt-2 evaluacion-fields border-top pt-3 bg-white mx-0 rounded pb-2 mb-2" style="display:none; transition: all 0.3s ease;">
                                                    <div class="col-12"><span class="badge bg-warning text-dark mb-2"><i class="fas fa-star me-1"></i> Configuración de Evaluación</span></div>
                                                    <div class="col-md-4">
                                                        <div class="form-group mb-0">
                                                            <label class="small text-muted fw-bold">Tipo de Evaluación</label>
                                                            <select name="contenidos[${nuevoIndice}][id_tipo_evaluacion]" class="form-select" disabled>
                                                                <option value="">Seleccione...</option>
                                                                ${getTipoOptions()}
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group mb-0">
                                                            <label class="small text-muted fw-bold">Ponderación (%)</label>
                                                            <div class="input-group">
                                                                <input type="number" name="contenidos[${nuevoIndice}][ponderacion]" class="form-control" min="0" max="100" placeholder="0" disabled>
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
                </div>
            </div>
        </div>
    </div>
@endsection
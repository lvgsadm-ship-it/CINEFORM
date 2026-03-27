<div class="row justify-content-center">
        <div class="col-lg-11">
            <!-- Alertas de Sesión -->
            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm rounded-4 d-flex align-items-center mb-4 fade show" role="alert">
                    <div class="icon-shape icon-sm bg-success-light text-success rounded-circle me-3">
                        <i class="fas fa-check"></i>
                    </div>
                    <div><h6 class="mb-0 fw-bold">¡Guardado con éxito!</h6><small>{{ session('success') }}</small></div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Encabezado de la Página -->
            <div class="d-flex justify-content-between align-items-end mb-4 px-2">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-1 p-0 bg-transparent" style="font-size: 0.8rem;">
                            <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">Gestión de Taller</a></li>
                            <li class="breadcrumb-item active text-primary fw-bold" aria-current="page">Editar Curso</li>
                        </ol>
                    </nav>
                    <h2 class="fw-bold text-dark mb-0">Configuración del Curso</h2>
                    <p class="text-muted small mb-0"><i class="fas fa-info-circle me-1 text-primary shadow-xs"></i> Gestiona la descripción y el currículo de tu programa educativo.</p>
                </div>
                <div class="text-end">
                    <a href="{{ route('taller.cursos.show', $curso->id_curso) }}"
                        class="btn btn-white shadow-sm border rounded-pill px-4 btn-sm fw-bold transition-hover">
                        <i class="fas fa-arrow-left me-2 text-primary"></i> Volver al Panel
                    </a>
                </div>
            </div>

            <form action="{{ route('taller.cursos.update', $curso->id_curso) }}" method="POST">
                @csrf
                @method('PUT')

                @if ($errors->any())
                    <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4">
                        <div class="fw-bold mb-2 small uppercase"><i class="fas fa-exclamation-circle me-2"></i> Errores detectados:</div>
                        <ul class="mb-0 small ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Datos Generales Card -->
                <div class="card border-0 shadow-card rounded-4 mb-5 overflow-hidden">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center">
                        <div class="icon-box bg-primary-soft text-primary rounded-3 me-3 p-2">
                            <i class="fas fa-university fs-5"></i>
                        </div>
                        <h5 class="fw-bold mb-0">Información General</h5>
                    </div>
                    <div class="card-body p-4 bg-light bg-opacity-10">
                        <div class="row g-4">
                            <!-- Fila 1: Identidad -->
                            <div class="col-md-8">
                                <label class="text-muted small fw-bold mb-2 d-flex align-items-center">
                                    <i class="fas fa-university me-2 opacity-50"></i> NOMBRE DEL CURSO
                                </label>
                                <div class="bg-white border-2 rounded-3 p-3 fw-bold text-dark shadow-xs border-light-2 fs-5">
                                    {{ $curso->nombre ?? 'N/A' }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="text-muted small fw-bold mb-2 d-flex align-items-center">
                                    <i class="fas fa-chalkboard-teacher me-2 opacity-50"></i> MODALIDAD
                                </label>
                                <div class="bg-white border-2 rounded-3 p-3 fw-bold text-dark shadow-xs border-light-2 fs-5 text-center">
                                    {{ $curso->modalidad->nombre_modalidad ?? 'N/A' }}
                                </div>
                            </div>

                            <!-- Fila 2: Métricas Rápidas -->
                            <div class="col-md-3">  
                                <label class="text-muted small fw-bold mb-2 d-flex align-items-center text-truncate">
                                    <i class="fas fa-calendar-week me-2 opacity-50"></i> DURACIÓN
                                </label>
                                <div class="bg-white border-2 rounded-3 p-2 px-3 text-dark shadow-xs border-light-2 text-center">
                                    <span class="fw-bold fs-5 text-primary">{{ $curso->duracion ?? '0' }}</span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="text-muted small fw-bold mb-2 d-flex align-items-center text-truncate">
                                    <i class="fas fa-clock me-2 opacity-50"></i> HORAS TOTALES
                                </label>
                                <div class="bg-white border-2 rounded-3 p-2 px-3 text-dark shadow-xs border-light-2 text-center">
                                    <span class="fw-bold fs-5 text-primary">{{ $curso->horas ?? '0' }}</span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="text-muted small fw-bold mb-2 d-flex align-items-center text-truncate">
                                    <i class="far fa-play-circle me-2 opacity-50 text-success"></i> INICIO
                                </label>
                                <div class="bg-white border-2 rounded-3 p-2 px-3 text-dark shadow-xs border-light-2 text-center">
                                    <span class="fw-bold">{{ $curso->fecha_inicio ? $curso->fecha_inicio->format('d/m/Y') : 'N/A' }}</span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="text-muted small fw-bold mb-2 d-flex align-items-center text-truncate">
                                    <i class="far fa-stop-circle me-2 opacity-50 text-danger"></i> CIERRE
                                </label>
                                <div class="bg-white border-2 rounded-3 p-2 px-3 text-dark shadow-xs border-light-2 text-center">
                                    <span class="fw-bold">{{ $curso->fecha_fin ? $curso->fecha_fin->format('d/m/Y') : 'N/A' }}</span>
                                </div>
                            </div>

                            <!-- Fila 3: Descripción Editable -->
                            <div class="col-12">
                                <label for="descripcion" class="text-muted small fw-bold mb-2 d-flex align-items-center">
                                    <i class="fas fa-align-left me-2 opacity-50"></i> SÍNTESIS DEL PROGRAMA
                                </label>
                                <textarea class="form-control border-2 shadow-none rounded-4 bg-white p-3 border-light-2" id="descripcion" name="descripcion"
                                    rows="4" placeholder="Describe brevemente de qué trata este curso..." style="font-size: 1rem;">{{ old('descripcion', $curso->descripcion) }}</textarea>
                            </div>
                        </div>
                    </div>
                          <!-- Sección: Currículo del Curso -->
                <div class="card border-0 shadow-card rounded-4 mb-5 overflow-hidden border-top border-4 border-primary">
                    <div class="card-header bg-white py-4 border-bottom">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div class="d-flex align-items-center">
                                <div class="icon-box bg-primary text-white rounded-3 me-3 p-2 shadow-sm">
                                    <i class="fas fa-layer-group fs-5"></i>
                                </div>
                                <div>
                                    <h4 class="fw-bold mb-0 text-dark">Contenidos Académicos</h4>
                                    <p class="text-muted small mb-0">Añade módulos, lecciones y actividades evaluables.</p>
                                </div>
                            </div>
                            
                            <!-- Contador de Ponderación Dinámico -->
                            <div id="total-ponderacion-container" class="card border-2 border-dashed shadow-xs p-2 px-3 transition-all rounded-pill bg-light" style="min-width: 220px;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="small fw-bold text-muted text-uppercase" style="letter-spacing: 0.5px;">Carga Evaluativa</span>
                                    <span class="badge bg-white text-dark fw-bold border"><span id="total-ponderacion-valor">0</span>%</span>
                                </div>
                                <div class="progress mt-2" style="height: 6px;">
                                    <div id="ponderacion-progress" class="progress-bar rounded-pill" role="progressbar" style="width: 0%; transition: width 0.5s ease;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4 bg-light bg-opacity-25">

                    <div id="contenidos-container" class="row row-cols-1 g-5">
                        @php $tiposEvaluacionJson = json_encode($tiposEvaluacion); @endphp
                        @foreach($contenidos as $index => $contenido)
                            <div class="col contenido-item animate__animated animate__fadeIn" data-index="{{ $index }}">
                                <div class="card border-0 shadow-card rounded-4 overflow-hidden content-card-edit active-card mb-2">
                                    <div class="card-body p-4">
                                        <input type="hidden" name="contenidos[{{ $index }}][id]" value="{{ $contenido->id_contenido_curso }}">
                                        
                                        <!-- Cabecera del Item -->
                                        <div class="row align-items-center g-3 border-bottom pb-3 mb-3">
                                            <div class="col-md-auto">
                                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 38px; height: 38px; font-size: 0.9rem;">
                                                    {{ $index + 1 }}
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-group mb-0">
                                                    <input type="text" name="contenidos[{{ $index }}][titulo]"
                                                        class="form-control border-0 bg-transparent fs-5 fw-bold p-0 text-dark focus-none" 
                                                        value="{{ $contenido->titulo }}" required
                                                        placeholder="Escribe el nombre del tema o actividad...">
                                                </div>
                                            </div>
                                            <div class="col-md-auto">
                                                <div class="form-check form-switch p-0 d-flex align-items-center bg-light rounded-pill px-3 py-1 border shadow-xs">
                                                    <input type="hidden" name="contenidos[{{ $index }}][es_evaluacion]" value="0">
                                                    <input type="checkbox" class="form-check-input ms-0 me-2 custom-control-input" 
                                                           id="evalSwitch_{{ $index }}" 
                                                           name="contenidos[{{ $index }}][es_evaluacion]" 
                                                           value="1" 
                                                           onchange="toggleEvaluacion(this)"
                                                           {{ $contenido->es_evaluacion ? 'checked' : '' }}>
                                                    <label class="form-check-label small fw-bold text-muted mt-1" for="evalSwitch_{{ $index }}">
                                                        ¿Es evaluable?
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-auto">
                                                <button type="button" class="btn btn-outline-danger btn-sm rounded-circle p-2 border-0 opacity-50 hover-opacity-100 remove-contenido" title="Eliminar este contenido">
                                                    <i class="fas fa-trash-alt fs-6"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Campos de Evaluación (Condicionales) -->
                                        <div class="row g-3 evaluacion-fields rounded-3 p-3 mb-3 shadow-sm"
                                            style="{{ !$contenido->es_evaluacion ? 'display:none' : 'display:flex' }}; background-color: #0d6efd !important;">
                                            <div class="col-md-6 text-white fw-bold small uppercase d-flex align-items-center mb-2">
                                                <i class="fas fa-star me-2"></i> Ajustes de Calificación
                                            </div>
                                            <div class="col-12 h-0"></div>
                                            <div class="col-md-7">
                                                <div class="form-group mb-0">
                                                    <label class="small text-white fw-bold mb-1 opacity-75">TIPO DE EVALUACIÓN</label>
                                                    <select name="contenidos[{{ $index }}][id_tipo_evaluacion]" class="form-select border-0 shadow-sm">
                                                        <option value="">Seleccione el método...</option>
                                                        @foreach($tiposEvaluacion as $tipo)
                                                            <option value="{{ $tipo->id_tipo_evaluacion }}" {{ $contenido->id_tipo_evaluacion == $tipo->id_tipo_evaluacion ? 'selected' : '' }}>
                                                                {{ $tipo->nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="form-group mb-0">
                                                    <label class="small text-white fw-bold mb-1 opacity-75">PESO SOBRE LA NOTA (%)</label>
                                                    <div class="input-group shadow-sm">
                                                        <input type="number" name="contenidos[{{ $index }}][ponderacion]"
                                                            class="form-control border-0 text-center fw-bold text-primary" value="{{ $contenido->ponderacion }}"
                                                            min="0" max="100" placeholder="0">
                                                        <span class="input-group-text bg-white border-0 fw-bold text-primary">%</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Detalles y Meta -->
                                        <div class="row g-3">
                                            <div class="col-md-10">
                                                <div class="form-group mb-0">
                                                    <label class="small text-muted fw-bold mb-1">ENLACE O RECURSO EXTERNO (URL)</label>
                                                    <div class="input-group bg-light rounded-3 px-2 border shadow-none">
                                                        <span class="input-group-text bg-transparent border-0 opacity-40"><i class="fas fa-link small"></i></span>
                                                        <input type="url" name="contenidos[{{ $index }}][url_contenido]"
                                                            class="form-control bg-transparent border-0 py-2 small" value="{{ $contenido->url_contenido }}"
                                                            placeholder="https://ejemplo.com/material">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group mb-0">
                                                    <label class="small text-muted fw-bold mb-1">ORDEN</label>
                                                    <input type="number" name="contenidos[{{ $index }}][orden]"
                                                        class="form-control border-light-2 shadow-xs text-center"
                                                        value="{{ $contenido->orden ?? $loop->index + 1 }}" min="1">
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group mb-0 mt-3">
                                                    <label class="small text-muted fw-bold mb-1">RESUMEN DEL CONTENIDO</label>
                                                    <textarea name="contenidos[{{ $index }}][descripcion_breve]"
                                                        class="form-control border-light-2 shadow-xs" rows="2"
                                                        placeholder="Proporciona una breve descripción o instrucciones para este contenido..." style="font-size: 0.85rem;">{{ $contenido->descripcion_breve }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="text-center mt-5">
                        <button type="button" id="agregar-contenido"
                            class="btn btn-outline-primary border-2 px-5 py-3 rounded-pill fw-bold hvr-push shadow-sm" style="transition: all 0.3s ease;">
                            <i class="fas fa-plus-circle me-2"></i> Añadir Nuevo Contenido / Evaluación
                        </button>
                    </div>
                    </div>
                </div>

                <!-- Footer de Acciones Fijo/Sustentado -->
                <div class="row justify-content-center mt-5 pb-5">
                    <div class="col-lg-6">
                        <div class="card border shadow rounded-pill overflow-hidden bg-white">
                            <div class="card-body p-2 d-flex align-items-center justify-content-between">
                                <a href="{{ route('taller.cursos.show', $curso->id_curso) }}"
                                    class="btn btn-link text-muted fw-bold text-decoration-none px-4 ms-2">
                                    <i class="fas fa-arrow-left me-2"></i> Cancelar
                                </a>
                                <div class="d-flex align-items-center me-2">
                                    <div class="ponderacion-alerts-container me-3 text-end">
                                        <div id="alert-100" class="text-success small fw-bold" style="display: none;">
                                            <i class="fas fa-check-double me-1"></i> Lista para guardar
                                        </div>
                                        <div id="alert-insufficient" class="text-warning small fw-bold" style="display: none;">
                                            <i class="fas fa-clock me-1"></i> Falta carga ({{ $totalPonderacion ?? '0' }}%)
                                        </div>
                                        <div id="alert-over" class="text-danger small fw-bold" style="display: none;">
                                            <i class="fas fa-times-circle me-1"></i> Exceso de carga
                                        </div>
                                    </div>
                                    <button type="submit" id="btn-save-curso" class="btn btn-primary px-5 py-3 rounded-pill fw-bold shadow-lg" style="background: #1e3a8a;">
                                        <i class="fas fa-save me-2 text-white"></i> Guardar Cambios
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
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

                                // Función para recalcular ponderación total
                                function actualizarPonderacionTotal() {
                                    let total = 0;
                                    const items = document.querySelectorAll('.contenido-item');
                                    
                                    items.forEach(item => {
                                        const isEval = item.querySelector('.custom-control-input').checked;
                                        if (isEval) {
                                            const ponderacion = parseFloat(item.querySelector('input[name*="[ponderacion]"]').value) || 0;
                                            total += ponderacion;
                                        }
                                    });

                                    const valorDisplay = document.getElementById('total-ponderacion-valor');
                                    const progressBar = document.getElementById('ponderacion-progress');
                                    const btnSave = document.getElementById('btn-save-curso');
                                    
                                    const alert100 = document.getElementById('alert-100');
                                    const alertInsuff = document.getElementById('alert-insufficient');
                                    const alertOver = document.getElementById('alert-over');

                                    valorDisplay.innerText = total;
                                    progressBar.style.width = Math.min(total, 100) + '%';
                                    
                                    // Reset alerts
                                    alert100.style.display = 'none';
                                    alertInsuff.style.display = 'none';
                                    alertOver.style.display = 'none';

                                    if (total > 100) {
                                        progressBar.className = 'progress-bar rounded-pill bg-danger';
                                        alertOver.style.display = 'block';
                                        btnSave.disabled = true;
                                    } else if (total < 100) {
                                        progressBar.className = 'progress-bar rounded-pill bg-warning';
                                        alertInsuff.style.display = 'block';
                                        btnSave.disabled = true;
                                    } else {
                                        progressBar.className = 'progress-bar rounded-pill bg-success';
                                        alert100.style.display = 'block';
                                        btnSave.disabled = false;
                                    }
                                }

                                // Escuchar cambios en ponderaciones y switches
                                document.addEventListener('input', function(e) {
                                    if (e.target.matches('input[name*="[ponderacion]"]') || e.target.matches('.custom-control-input')) {
                                        actualizarPonderacionTotal();
                                    }
                                });

                                // Delegación para toggleEvaluacion (ya existente pero integrada con el cálculo)
                                window.toggleEvaluacion = function(inputElement) {
                                    const container = inputElement.closest('.contenido-item');
                                    const evalFields = container.querySelector('.evaluacion-fields');
                                    const isActive = inputElement.checked;

                                    if(isActive) {
                                        evalFields.style.display = 'flex';
                                        evalFields.style.opacity = 0;
                                        setTimeout(() => { evalFields.style.opacity = 1; }, 50);
                                    } else {
                                        evalFields.style.display = 'none';
                                        evalFields.querySelectorAll('input, select').forEach(el => el.value = '');
                                    }
                                    actualizarPonderacionTotal();
                                }

                                // Inicializar cálculo
                                actualizarPonderacionTotal();

                                // Agregar nuevo contenido
                                document.getElementById('agregar-contenido').addEventListener('click', function () {
                                    const contenedor = document.getElementById('contenidos-container');
                                    const nuevoIndice = contadorContenidos++;
                                    const nuevoContenido = `
                                    <div class="col contenido-item animate__animated animate__zoomIn" data-index="${nuevoIndice}">
                                        <div class="card border-0 shadow-card rounded-4 overflow-hidden content-card-edit mb-2">
                                            <div class="card-body p-4">
                                                <input type="hidden" name="contenidos[${nuevoIndice}][id]" value="">
                                                
                                                <div class="row align-items-center g-3 border-bottom pb-3 mb-3">
                                                    <div class="col-md-auto">
                                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 38px; height: 38px; font-size: 0.9rem;">
                                                            ${nuevoIndice + 1}
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-group mb-0">
                                                            <input type="text" name="contenidos[${nuevoIndice}][titulo]"
                                                                class="form-control border-0 bg-transparent fs-5 fw-bold p-0 text-dark focus-none" 
                                                                required placeholder="Título del nuevo contenido...">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-auto">
                                                        <div class="form-check form-switch p-0 d-flex align-items-center bg-light rounded-pill px-3 py-1 border shadow-xs">
                                                            <input type="hidden" name="contenidos[${nuevoIndice}][es_evaluacion]" value="0">
                                                            <input type="checkbox" class="form-check-input ms-0 me-2 custom-control-input" 
                                                                   id="evalSwitch_${nuevoIndice}" 
                                                                   name="contenidos[${nuevoIndice}][es_evaluacion]" 
                                                                   value="1" 
                                                                   onchange="toggleEvaluacion(this)">
                                                            <label class="form-check-label small fw-bold text-muted mt-1" for="evalSwitch_${nuevoIndice}">
                                                                ¿Es evaluable?
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-auto">
                                                        <button type="button" class="btn btn-outline-danger btn-sm rounded-circle p-2 border-0 remove-contenido" title="Eliminar">
                                                            <i class="fas fa-trash-alt fs-6"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="row g-3 evaluacion-fields rounded-3 p-3 mb-3 shadow-sm" style="display:none; background-color: #0d6efd !important;">
                                                    <div class="col-12 text-white fw-bold small uppercase d-flex align-items-center mb-2">
                                                        <i class="fas fa-star me-2"></i> Ajustes de Calificación
                                                    </div>
                                                    <div class="col-md-7">
                                                        <div class="form-group mb-0">
                                                            <label class="small text-white fw-bold mb-1 opacity-75">TIPO DE EVALUACIÓN</label>
                                                            <select name="contenidos[${nuevoIndice}][id_tipo_evaluacion]" class="form-select border-0 shadow-sm">
                                                                <option value="">Seleccione...</option>
                                                                ${getTipoOptions()}
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="form-group mb-0">
                                                            <label class="small text-white fw-bold mb-1 opacity-75">PESO (%)</label>
                                                            <div class="input-group shadow-sm">
                                                                <input type="number" name="contenidos[${nuevoIndice}][ponderacion]" class="form-control border-0 text-center fw-bold text-primary" min="0" max="100" placeholder="0">
                                                                <span class="input-group-text bg-white border-0 fw-bold text-primary">%</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row g-3">
                                                    <div class="col-md-10">
                                                        <div class="form-group mb-0">
                                                            <label class="small text-muted fw-bold mb-1">URL / LINK</label>
                                                            <div class="input-group bg-light rounded-3 px-2 border shadow-none">
                                                                <span class="input-group-text bg-transparent border-0 opacity-40"><i class="fas fa-link small"></i></span>
                                                                <input type="url" name="contenidos[${nuevoIndice}][url_contenido]" class="form-control bg-transparent border-0 py-2 small" required placeholder="https://...">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group mb-0">
                                                            <label class="small text-muted fw-bold mb-1">ORDEN</label>
                                                            <input type="number" name="contenidos[${nuevoIndice}][orden]" class="form-control border-light-2 shadow-xs text-center" value="${nuevoIndice + 1}" min="1">
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group mb-0 mt-3">
                                                            <label class="small text-muted fw-bold mb-1">RESUMEN</label>
                                                            <textarea name="contenidos[${nuevoIndice}][descripcion_breve]" class="form-control border-light-2 shadow-xs" rows="2" placeholder="Breve descripción..."></textarea>
                                                        </div>
                                                    </div>
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
                                            setTimeout(() => {
                                                item.remove();
                                                actualizarPonderacionTotal();
                                            }, 300);
                                        }
                                    }
                                });
                            });
                        </script>
                    @endpush

    @push('styles')
        <style>
            .bg-primary-soft { background-color: rgba(30, 58, 138, 0.1); }
            .bg-warning-soft { background-color: rgba(245, 158, 11, 0.1); }
            .bg-success-light { background-color: rgba(16, 185, 129, 0.1); }
            .bg-gray-100 { background-color: #f8fafc; }
            .border-light-2 { border-color: #f1f4f8 !important; }
            .shadow-xs { box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
            .shadow-card { box-shadow: 0 10px 30px rgba(0,0,0,0.08); }
            
            .content-card-edit {
                border: 1px solid #e2e8f0;
                border-left: 5px solid #1e3a8a;
                transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
                box-shadow: 0 4px 10px rgba(0,0,0,0.03);
            }
            .content-card-edit:hover {
                transform: translateY(-4px);
                box-shadow: 0 15px 35px rgba(30, 58, 138, 0.12) !important;
                border-color: rgba(30, 58, 138, 0.2);
            }
            
            .focus-none:focus { outline: none; border: none; box-shadow: none; }
            .hover-opacity-100:hover { opacity: 1 !important; }
            
            .hvr-push { transition: transform 0.2s; }
            .hvr-push:active { transform: scale(0.98); }
            
            .transition-hover { transition: all 0.2s ease; }
            .transition-hover:hover { background-color: #f1f5f9; transform: translateY(-1px); }

            .uppercase { text-transform: uppercase; letter-spacing: 0.5px; }

            .progress-bar { transition: width 0.6s cubic-bezier(0.65, 0, 0.35, 1); }
        </style>
    @endpush
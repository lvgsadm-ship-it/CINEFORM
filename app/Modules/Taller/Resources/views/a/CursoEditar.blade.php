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
                                    @foreach($contenidos as $index => $contenido)
                                        <div class="contenido-item mb-3 border p-3" data-index="{{ $index }}">
                                            <input type="hidden" name="contenidos[{{ $index }}][id]"
                                                value="{{ $contenido->id_contenido_curso }}">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Título</label>
                                                        <input type="text" name="contenidos[{{ $index }}][titulo]"
                                                            class="form-control" value="{{ $contenido->titulo }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>URL o Enlace</label>
                                                        <input type="url" name="contenidos[{{ $index }}][url_contenido]"
                                                            class="form-control" value="{{ $contenido->url_contenido }}"
                                                            required>
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <div class="form-group">
                                                        <label>Orden</label>
                                                        <input type="number" name="contenidos[{{ $index }}][orden]"
                                                            class="form-control"
                                                            value="{{ $contenido->orden ?? $loop->index + 1 }}" min="1">
                                                    </div>
                                                </div>
                                                <div class="col-md-1 d-flex align-items-end">
                                                    <button type="button" class="btn btn-danger btn-sm remove-contenido">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="form-group mt-2">
                                                <label>Descripción</label>
                                                <textarea name="contenidos[{{ $index }}][descripcion]" class="form-control"
                                                    rows="2">{{ $contenido->descripcion }}</textarea>
                                            </div>
                                            <div class="form-group mt-2">
                                                <label>Descripción Breve</label>
                                                <textarea name="contenidos[{{ $index }}][descripcion_breve]"
                                                    class="form-control" rows="2">{{ $contenido->descripcion_breve }}</textarea>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="text-right mt-3">
                                    <button type="button" id="agregar-contenido" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Agregar Contenido
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="card-action mt-4">
                            <a href="{{ route('taller.cursos.show', $curso->id_curso) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Guardar Cambios
                            </button>
                        </div>
                    </form>

                    @push('scripts')
                            <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    let contadorContenidos = {{ count($contenidos) }};

                                    // Agregar nuevo contenido
                                    document.getElementById('agregar-contenido').addEventListener('click', function () {
                                        const contenedor = document.getElementById('contenidos-container');
                                        const nuevoIndice = contadorContenidos++;

                                        const nuevoContenido = `
                            <div class="contenido-item mb-3 border p-3" data-index="${nuevoIndice}">
                                <input type="hidden" name="contenidos[${nuevoIndice}][id]" value="">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Título</label>
                                            <input type="text" name="contenidos[${nuevoIndice}][titulo]" 
                                                   class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>URL o Enlace</label>
                                            <input type="url" name="contenidos[${nuevoIndice}][url_contenido]" 
                                                   class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label>Orden</label>
                                            <input type="number" name="contenidos[${nuevoIndice}][orden]" 
                                                   class="form-control" value="${nuevoIndice + 1}" min="1">
                                        </div>
                                    </div>
                                    <div class="col-md-1 d-flex align-items-end">
                                        <button type="button" class="btn btn-danger btn-sm remove-contenido">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="form-group mt-2">
                                    <label>Descripción</label>
                                    <textarea name="contenidos[${nuevoIndice}][descripcion]" 
                                             class="form-control" rows="2"></textarea>
                                </div>
                                <div class="form-group mt-2">
                                    <label>Descripción Breve</label>
                                    <textarea name="contenidos[${nuevoIndice}][descripcion_breve]" 
                                             class="form-control" rows="2"></textarea>
                                </div>
                            </div>
                        `;

                                        contenedor.insertAdjacentHTML('beforeend', nuevoContenido);
                                    });

                                    // Eliminar contenido
                                    document.addEventListener('click', function (e) {
                                        if (e.target.closest('.remove-contenido')) {
                                            if (confirm('¿Estás seguro de que deseas eliminar este contenido?')) {
                                                const item = e.target.closest('.contenido-item');
                                                item.remove();
                                            }
                                        }
                                    });
                                });
                            </script>
                    @endpush

@endsection
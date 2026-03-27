@extends('layouts.kaiadmin-login')
@section('content')
<style>
    input[type=text]:focus, input[type=password]:focus, input[type=email]:focus, select:focus {
        background-color: #f7faff !important;
        border-color: #3f67f0 !important;
        box-shadow: 0 0 0 0.25rem rgba(63, 103, 240, 0.15) !important;
        outline: none;
    }
    .form-floating-custom .form-control:focus+label,
    .form-floating-custom .form-control:not(:placeholder-shown)+label,
    .form-floating-custom .form-select:focus+label,
    .form-floating-custom .form-select:not(:placeholder-shown)+label {
        font-weight: 700;
        color: #3f67f0;
    }
    .container-login {
        max-width: 1100px !important;
        width: 100% !important;
    }
    .wrapper-login {
        min-height: 100vh;
    }
</style>

<div class="wrapper-login d-flex justify-content-center align-items-center py-5">
    <div class="container-login bg-white shadow-lg rounded-4 p-5 animated fadeIn">
        <h2 class="text-center mb-5 fw-extrabold text-primary">
            <i class="fas fa-user-plus me-2"></i> {{__('Registro de Participante')}}
        </h2>
        
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                Varios campos contienen errores, por favor verifique.
            </div>
        @endif

        <form method="post" action="{{ route('registro.usuario.store') }}" autocomplete="off" id="frm_register">
            @csrf
            
            <div class="login-form">
                <div class="form-sub mb-5">
                    <h5 class="section-title text-primary border-bottom pb-2 mb-4 fw-bold">
                        <i class="fas fa-user-lock me-2"></i> Información de Cuenta
                    </h5>
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="form-floating form-floating-custom mb-2">
                                <input id="email" name="email" maxlength="100" type="email" class="form-control" placeholder="{{__('Email')}}" required value="{{ old('email') }}" />
                                <label for="email">{{__('Correo Electrónico')}} (Email) <span class="text-danger">*</span></label>
                                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating form-floating-custom mb-2">
                                <input id="password" name="password" type="password" maxlength="16" class="form-control" placeholder="{{__('Password')}}" required />
                                <label for="password">{{__('Contraseña')}} <span class="text-danger">*</span></label>
                                @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating form-floating-custom mb-2">
                                <input id="password_confirmation" name="password_confirmation" type="password" maxlength="16" class="form-control" placeholder="{{__('Confirm Password')}}" required />
                                <label for="password_confirmation">{{__('Repita Contraseña')}} <span class="text-danger">*</span></label>
                            </div>
                        </div>
                    </div>

                    <h5 class="section-title text-primary border-bottom pb-2 mb-4 mt-5 fw-bold">
                        <i class="fas fa-id-card me-2"></i> Información Personal
                    </h5>
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="form-floating form-floating-custom">
                                <select id="tipo_dni" name="tipo_dni" class="form-select" required>
                                    <option value="" disabled {{ old('tipo_dni') ? '' : 'selected' }}>Seleccione</option>
                                    @foreach($documentTypes as $tdoc)
                                        <option value="{{ $tdoc->id }}" {{ old('tipo_dni') == $tdoc->id ? 'selected' : '' }}>{{ $tdoc->code }} - {{ $tdoc->name }}</option>
                                    @endforeach
                                </select>
                                <label for="tipo_dni">{{__('Tipo DNI')}} <span class="text-danger">*</span></label>
                                @error('tipo_dni') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating form-floating-custom">
                                <input id="dni" name="dni" type="text" class="form-control" placeholder="DNI" required value="{{ old('dni') }}" />
                                <label for="dni">{{__('DNI')}} <span class="text-danger">*</span></label>
                                @error('dni') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating form-floating-custom">
                                <input id="pasaporte" name="pasaporte" type="text" class="form-control" placeholder="Pasaporte" value="{{ old('pasaporte') }}" />
                                <label for="pasaporte">{{__('Pasaporte')}}</label>
                                @error('pasaporte') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-floating form-floating-custom">
                                <input id="rif" name="rif" type="text" class="form-control" placeholder="RIF" value="{{ old('rif') }}" />
                                <label for="rif">{{__('RIF')}}</label>
                                @error('rif') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating form-floating-custom">
                                <input id="reg_nac_cine" name="reg_nac_cine" type="text" class="form-control" placeholder="Registro" value="{{ old('reg_nac_cine') }}" />
                                <label for="reg_nac_cine">Reg. Nacional Cine</label>
                                @error('reg_nac_cine') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating form-floating-custom">
                                <select id="genero" name="genero" class="form-select" required>
                                    <option value="" disabled {{ old('genero') ? '' : 'selected' }}>Seleccione</option>
                                    @foreach($genders as $gen)
                                        <option value="{{ $gen->id }}" {{ old('genero') == $gen->id ? 'selected' : '' }}>{{ $gen->name }}</option>
                                    @endforeach
                                </select>
                                <label for="genero">{{__('Género')}} <span class="text-danger">*</span></label>
                                @error('genero') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row g-4 mt-1">
                        <div class="col-md-4">
                            <div class="form-floating form-floating-custom">
                                <input id="telefono" name="telefono" type="text" class="form-control" placeholder="Teléfono" required value="{{ old('telefono') }}" />
                                <label for="telefono">{{__('Teléfono Principal')}} <span class="text-danger">*</span></label>
                                @error('telefono') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating form-floating-custom">
                                <input id="telefono_opcional" name="telefono_opcional" type="text" class="form-control" placeholder="Teléfono Opc." value="{{ old('telefono_opcional') }}" />
                                <label for="telefono_opcional">Teléfono Opcional</label>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4 mt-1">
                        <div class="col-md-6">
                            <div class="form-floating form-floating-custom mb-2">
                                <input id="primer_nombre" name="primer_nombre" type="text" class="form-control" placeholder="Primer Nombre" required value="{{ old('primer_nombre') }}" />
                                <label for="primer_nombre">{{__('Primer Nombre')}} <span class="text-danger">*</span></label>
                                @error('primer_nombre') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-floating-custom mb-2">
                                <input id="segundo_nombre" name="segundo_nombre" type="text" class="form-control" placeholder="Segundo Nombre" value="{{ old('segundo_nombre') }}" />
                                <label for="segundo_nombre">{{__('Segundo Nombre')}}</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-floating-custom mb-2">
                                <input id="primer_apellido" name="primer_apellido" type="text" class="form-control" placeholder="Primer Apellido" required value="{{ old('primer_apellido') }}" />
                                <label for="primer_apellido">{{__('Primer Apellido')}} <span class="text-danger">*</span></label>
                                @error('primer_apellido') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-floating-custom mb-2">
                                <input id="segundo_apellido" name="segundo_apellido" type="text" class="form-control" placeholder="Segundo Apellido" value="{{ old('segundo_apellido') }}" />
                                <label for="segundo_apellido">{{__('Segundo Apellido')}}</label>
                            </div>
                        </div>
                    </div>

                    <h5 class="section-title text-primary border-bottom pb-2 mb-4 mt-5 fw-bold">
                        <i class="fas fa-map-marker-alt me-2"></i> Ubicación
                    </h5>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="form-floating form-floating-custom">
                                <select id="id_pais" name="id_pais" class="form-select" required>
                                    <option value="" disabled {{ old('id_pais') ? '' : 'selected' }}>Seleccione País</option>
                                    @foreach($countries as $pais)
                                        <option value="{{ $pais->id }}" {{ old('id_pais') == $pais->id ? 'selected' : '' }}>{{ $pais->name }}</option>
                                    @endforeach
                                </select>
                                <label for="id_pais">{{__('País')}} <span class="text-danger">*</span></label>
                                @error('id_pais') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-floating-custom">
                                <select id="id_estado" name="id_estado" class="form-select" required disabled>
                                    <option value="">Seleccione Estado</option>
                                </select>
                                <label for="id_estado">{{__('Estado')}} <span class="text-danger">*</span></label>
                                @error('id_estado') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-floating-custom">
                                <select id="id_municipio" name="id_municipio" class="form-select" required disabled>
                                    <option value="">Seleccione Municipio</option>
                                </select>
                                <label for="id_municipio">{{__('Municipio')}} <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-floating-custom">
                                <select id="id_parroquia" name="id_parroquia" class="form-select" required disabled>
                                    <option value="">Seleccione Parroquia</option>
                                </select>
                                <label for="id_parroquia">{{__('Parroquia')}} <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-12 mt-2">
                            <div class="form-floating form-floating-custom mb-2">
                                <input id="direccion" name="direccion" type="text" class="form-control" placeholder="Dirección" required value="{{ old('direccion') }}" />
                                <label for="direccion">{{__('Dirección Exacta')}} <span class="text-danger">*</span></label>
                                @error('direccion') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-action mb-3 mt-4 text-center">
                    <button type="submit" class="btn btn-primary btn-lg w-50 shadow"><i class="fas fa-check-circle me-2"></i> Registrarse Ahora</button>
                    <div class="mt-3">
                        <a href="{{ route('login') }}" class="btn btn-link text-muted" style="text-decoration: none;">
                            <i class="fas fa-arrow-left me-1"></i> {{__('¿Ya tienes cuenta? Volver al Login')}}
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        
        // Cuando el país cambia, buscar estados
        $('#id_pais').on('change', function() {
            var pais_id = $(this).val();
            var baseUrl = '{{ url("registro/ajax/estados") }}';
            
            $('#id_estado').empty().append('<option value="">Cargando...</option>').prop('disabled', true);
            $('#id_municipio').empty().append('<option value="">Seleccione Municipio</option>').prop('disabled', true);
            $('#id_parroquia').empty().append('<option value="">Seleccione Parroquia</option>').prop('disabled', true);
            
            if(pais_id) {
                $.get(baseUrl + '/' + pais_id, function(data) {
                    $('#id_estado').empty().append('<option value="">Seleccione Estado</option>');
                    $.each(data, function(index, estado) {
                        $('#id_estado').append('<option value="'+ estado.id +'">'+ estado.nombre +'</option>');
                    });
                    $('#id_estado').prop('disabled', false);
                }).fail(function() {
                    $('#id_estado').empty().append('<option value="">Error al cargar</option>');
                });
            }
        });

        // Cuando el estado cambia, buscar municipios
        $('#id_estado').on('change', function() {
            var estado_id = $(this).val();
            var baseUrl = '{{ url("registro/ajax/municipios") }}';
            
            $('#id_municipio').empty().append('<option value="">Cargando...</option>').prop('disabled', true);
            $('#id_parroquia').empty().append('<option value="">Seleccione Parroquia</option>').prop('disabled', true);
            
            if(estado_id) {
                $.get(baseUrl + '/' + estado_id, function(data) {
                    $('#id_municipio').empty().append('<option value="">Seleccione Municipio</option>');
                    $.each(data, function(index, municipio) {
                        $('#id_municipio').append('<option value="'+ municipio.id +'">'+ municipio.nombre +'</option>');
                    });
                    $('#id_municipio').prop('disabled', false);
                }).fail(function() {
                    $('#id_municipio').empty().append('<option value="">Error al cargar</option>');
                });
            }
        });

        // Cuando el municipio cambia, buscar parroquias
        $('#id_municipio').on('change', function() {
            var municipio_id = $(this).val();
            var baseUrl = '{{ url("registro/ajax/parroquias") }}';
            
            $('#id_parroquia').empty().append('<option value="">Cargando...</option>').prop('disabled', true);
            
            if(municipio_id) {
                $.get(baseUrl + '/' + municipio_id, function(data) {
                    $('#id_parroquia').empty().append('<option value="">Seleccione Parroquia</option>');
                    $.each(data, function(index, parroquia) {
                        $('#id_parroquia').append('<option value="'+ parroquia.id +'">'+ parroquia.nombre +'</option>');
                    });
                    $('#id_parroquia').prop('disabled', false);
                }).fail(function() {
                    $('#id_parroquia').empty().append('<option value="">Error al cargar</option>');
                });
            }
        });
        
    });
</script>
@endsection

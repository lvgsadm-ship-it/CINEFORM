@extends('kaiadmin::layouts.master')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">👤 Perfil Participante</h3>
                <a href="{{ route('participantes') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> Volver
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr><th>Nombre Completo</th><td>{{ $participante->full_name ?? 'N/D' }}</td></tr>
                            <tr><th>DNI</th><td>{{ $participante->dni ?? 'N/D' }}</td></tr>
                            <tr><th>Email</th><td>{{ $participante->email ?? 'N/D' }}</td></tr>
                            <tr><th>Usuario</th><td>{{ $participante->username ?? 'N/D' }}</td></tr>
                            <tr><th>Estado</th><td>
                                @if($participante->active)
                                    <span class="badge badge-success">Activo</span>
                                @else
                                    <span class="badge badge-danger">Inactivo</span>
                                @endif
                            </td></tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

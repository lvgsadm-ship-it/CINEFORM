@extends('kaiadmin::layouts.master')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h3 class="card-title">👥 Lista de Participantes</h3>
                <a href="{{ route('security.users.create') }}?profile=Participante" class="btn btn-primary">
                    <i class="fa fa-plus"></i> Crear Participante
                </a>
            </div>
            <div class="card-body">
                <table id="participantesTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre Completo</th>
                            <th>DNI</th>
                            <th>Email</th>
                            <th>Usuario</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('javascript')
<script>
$(document).ready(function() {
    let table = $('#participantesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('participantes.list') }}",
        pageLength: 25,
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', width: "5%" },
            { data: 'full_name', name: 'full_name', width: "25%" },
            { data: 'dni', name: 'dni', width: "15%" },
            { data: 'email', name: 'email', width: "25%" },
            { data: 'username', name: 'username', width: "15%" },
            { 
                data: 'active', 
                name: 'active',
                width: "10%",
                render: function(data) {
                    return data ? 
                        '<span class="badge badge-success">Activo</span>' : 
                        '<span class="badge badge-danger">Inactivo</span>';
                }
            },
            { data: 'action', name: 'action', orderable: false, searchable: false, width: "10%" }
        ]
    });
});
</script>
@endpush

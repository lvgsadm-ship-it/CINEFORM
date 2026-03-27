{{-- Estado 5: En Aprobación - Coordinador --}}
{{-- El coordinador puede aprobar o rechazar el curso --}}

<button class="btn btn-success w-100 mb-2" onclick="aprobarCurso({{ $curso->id_curso }})">
    Aprobar Curso
</button>

<button class="btn btn-danger w-100 mb-2" onclick="rechazarContenido({{ $curso->id_curso }})">
    Rechazar Curso
</button>
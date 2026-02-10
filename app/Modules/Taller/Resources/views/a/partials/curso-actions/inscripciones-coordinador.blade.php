{{-- Estado 6: Inscripciones - Coordinador --}}
{{-- El coordinador puede finalizar el período de inscripciones --}}

<a class="btn btn-info w-100 mb-2" disabled>
    <i class="fas fa-user-tie me-2"></i> Inscripciones en curso
</a>
<button class="btn btn-success w-100 mb-2" onclick="FinalizarInscripciones({{ $curso->id_curso }})">
    Finalizar Inscripciones
</button>
@extends('layouts.kaiadmin-menu')

@section('content')
<style>
    input[type=text]:focus,  input[type=password]:focus{
        background-color: #FFFF99 !important;
    }
    .form-floating-custom .form-control:focus+label,.form-floating-custom .form-control:not(:placeholder-shown)+label,.form-floating-custom .form-select:focus+label,.form-floating-custom .form-select:not(:placeholder-shown)+label {
        font-weight: bold;
    }
</style>


<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function (event) {

        $.notify({
            icon: 'icon-bell',
            title: APP_NAME,
            message: '{{__("Welcome")}}',
        }, {
            type: 'secondary',
            placement: {
                from: "bottom",
                align: "right"
            },
            time: 1000,
        });
    });

</script>
@endsection

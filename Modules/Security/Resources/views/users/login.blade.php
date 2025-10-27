@extends('layouts.kaiadmin-login')
@section('content')
<style>
    input[type=text]:focus,  input[type=password]:focus{
        background-color: #FFFF99 !important;
    }
    .form-floating-custom .form-control:focus+label,.form-floating-custom .form-control:not(:placeholder-shown)+label,.form-floating-custom .form-select:focus+label,.form-floating-custom .form-select:not(:placeholder-shown)+label {
        font-weight: bold;
    }
</style>

<div class="wrapper wrapper-login">
    
    <div class="container container-login animated fadeIn">
         <div class="row mb-2 text-center">
             
             <div class="col-6 col-md-6 col-lg-6 text-center ">
                 <a href="{{route('language', 'es')}}">
                 <i style="display:inline-block" class="iti__flag iti__ve"></i>
                 </a>
             </div>
             <div class="col-6 col-md-6 col-lg-6  text-center ">
                 <a href="{{route('language', 'en')}}">
                 <i style="display:inline-block" class="iti__flag iti__gb"></i>
                 </a>
             </div>
            
            
        </div>
        <h3 class="text-center d-block d-sm-none">
            <img class="img-fluid" src="{{ asset('template/kaiadmin/assets/img/kaiadmin/logo_inac.jpeg')}}" alt="Card image cap">
       
        </h3>
        <h3 class="text-center">
            
            {{__('Sign In')}}
        </h3>
        <form method="post" action="{{route('login')}}" autocomplete="off" id="frm1">
             
            @csrf
            <div class="login-form">
                
                <div class="form-sub">
                    
                    <div class="form-floating form-floating-custom mb-2">
                        <input
                            id="username"
                            name="username"
                            maxlength="50"
                            type="text"
                            autofocus=""
                            class="form-control"
                            placeholder="{{__('username')}}"
                            required
                            />
                        <label for="username">{{__('Username')}}</label>
                    </div>
                    
                    @error('username')
                    <label id="username-error" class="error" for="username">{{__('This field is required')}}</label>
                    @enderror
                    <div class="form-floating form-floating-custom mb-2">
                        <input
                            id="password"
                            name="password"
                            type="password"
                            maxlength="16"
                            class="form-control"
                            placeholder="{{__('password')}}"
                            required
                            />
                        <label for="password">{{__('Password')}}</label>

                        <div class="show-password">
                            <i class="icon-eye"></i>
                        </div>

                    </div>
                    @error('password')
                    <label id="password-error" class="error" for="password">{{__('This field is required')}}</label>
                    @enderror
                    <div class="form-floating form-floating-custom mb-2">
                        <input
                            id="captcha"
                            name="captcha"
                            maxlength="5"
                            minlength="5"
                            type="text"
                            style="text-transform: uppercase"
                            class="form-control"
                            placeholder="{{__('Captcha')}}"
                            required
                            />
                        <label for="captcha">{{__('Captcha')}}</label>
                    </div>
                    
                    @error('captcha')
                    <label id="captcha-error" class="error" for="captcha">{{__('This field is required')}}</label>
                    @enderror
                    <div class="row mt-3">
                        <div class="col-8 ">
                        
                            <img id="img-c" class="img-fluid" src="{{ route('captcha', rand(1,999)) }}" alt="captcha">
                        
                        </div>
                        <div class="col-4 mt-1">
                            <div class="row ">
                                <button id="reload-img"   type="button" class="btn btn-large btn-info">
                                    <i class="fa fa-undo"></i>
                                </button>

                            </div>
                        </div>
                        
                    </div>
                    
                </div>

                <div class="form-action mb-3">
                    <button id="checkUser" type="button" class="btn btn-primary w-100 btn-login"><i class="icon-login"></i> {{__('Sign In')}}</button>

                </div>
                {{--
                <div class="login-account">
                    <span class="msg">{{__("Don't have an account yet ?")}}</span>
                    <a href="{{route('register')}}" id="show-signup" class="link">{{__("Sign Up")}}</a>
                </div>
                --}}
                <div class="login-account">
                    
                    <a href="{{route('recovery')}}" id="show-signup" class="link">{{__("Recover Password")}}</a>
                </div>
                
                
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function (event) {
        $("#checkUser").on('click', function () {
            if ($('#frm1').valid() == true) {
                $("#password").attr("type","password");
                showLoading({"icon":"info", "title":"{{__('Processing...')}}", "html":'<i class="fas fa-spinner fa-spin"></i>' });
                $('#frm1').submit();
            } else {

                $.notify({
                    icon: 'icon-bell',
                    title: APP_NAME,
                    message: '{{__("Missing Required Fields")}}',
                }, {
                    type: 'secondary',
                    placement: {
                        from: "bottom",
                        align: "right"
                    },
                    time: 1000,
                });


            }
        });
        
        $("#reload-img").on("click", function (){
            showLoading({"icon":"info", "title":"{{__('Processing...')}}", "html":'<i class="fas fa-spinner fa-spin"></i>' });
            $('#img-c').attr('src', '{{url("security/captcha")}}/'+Math.floor(Math.random() * 100000));
        });
        
        $("#img-c").on("load", function() {
            Swal.close();
        });
        
        
         @if(session()->has('success'))
             $.notify({
                icon: 'icon-bell',
                title: APP_NAME,
                message: "{{session()->get('success')}}",
            }, {
                type: 'success',
                placement: {
                    from: "bottom",
                    align: "right"
                },
                time: 1000,
            });
            
        @endif
        
        setInterval(function (){  $("#reload-img").click(); }, 120000);
        
    });

</script>
@endsection

@extends('layouts.kaiadmin-menu')

@section('content')

<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <div class="card-head-row">
                <h4 class="card-title">{{__('Users')}}</h4>
                <div class="card-tools">
                    <a href="{{route('users')}}" class="btn btn-icon btn-link btn-primary btn-xs">
                        <span class="fa fa-undo"></span>
                    </a>
                    <a href="javascript:void(0)" onClick="closeCard(this)" class="btn btn-icon btn-link btn-primary btn-xs">
                        <span class="fa fa-times"></span>
                    </a>
                    
                </div>
            </div>
        </div>
        <div class="card-body">
            {{
                html()->form("post", route('users.update', $id))
                        ->autocomplete("off")

                        ->id("frm1")
                        ->open()
            }}
            <input type="hidden" value="" name="country_iso2" id="country_iso2" />
            <div class="row">
                <div class="col-12 offset-md-2 col-md-4 offset-lg-4  col-lg-4 border border-info"">
                    <h1>{{__('Personal Data')}}</h1>

                    <div class="form-floating form-floating-custom mb-2">
                        {{html()->select("document_type_id", $typeDoc, $user->getDocumentType->crypt_id )
                                                        ->class('form-select')
                                                        ->placeholder(__("Select"))
                                                        ->required(true)
                        }}
                        <label for="type_document">{{__('Document Type')}}</label>
                    </div>
                    @error('type_document')
                    <label id="type_document-error" class="error" for="type_document">{{__('This field is required')}}</label>
                    @enderror
                    <div class="form-floating form-floating-custom mb-2">
                        {{
                            html()->input("text", 'document', $user->document )
                                    ->class('form-control')
                                    ->placeholder(__('Document'))
                                    ->maxlength(20)
                                    ->required(true)
                        }}
                        <label for="document">{{__('Document')}}</label>
                    </div>
                    @error('document')
                    <label id="document-error" class="error" for="document">{{__('This field is required')}}</label>
                    @enderror


                    <div class="form-floating form-floating-custom mb-2">
                        {{
                            html()->input("text", 'full_name', $user->full_name )
                                    ->class('form-control')
                                    ->placeholder(__('Full Name'))
                                    ->maxlength(250)
                                    ->required(true)
                        }}

                        <label for="full_name">{{__('Full Name')}}</label>
                    </div>
                    @error('full_name')
                    <label id="full_name-error" class="error" for="full_name">{{__('This field is required')}}</label>
                    @enderror
                    <div class="form-floating form-floating-custom mb-2">
                        {{
                            html()->input("text", 'phone', $user->phone )
                                    ->class('form-control phone')
                                    ->maxlength(20)
                                    ->required(true)
                        }}

                        <label class="phone" for="phone">{{__('Cell Phone')}}</label>
                    </div>
                    @error('phone')
                    <label id="phone-error" class="error" for="phone">{{__('This field is required')}}</label>
                    @enderror

                    <div class="form-floating form-floating-custom mb-2">
                        {{html()->select("profile_id", $profiles, $user->getProfile->crypt_id )
                                                        ->class('form-select')
                                                        ->placeholder(__("Select"))
                                                        ->required(true)
                        }}
                        <label for="profile_id">{{__('Profile')}}</label>
                    </div>
                    @error('profile_id')
                    <label id="profile_id-error" class="error" for="profile_id">{{__('This field is required')}}</label>
                    @enderror
                    <div class="form-floating form-floating-custom mb-2">
                        {{
                            html()->input("text", 'email', $user->email )
                                    ->class('form-control email')
                                    ->placeholder('')
                                    ->maxlength(100)
                                    ->required(true)
                        }}

                        <label for="email">{{__('Email')}}</label>
                    </div>
                    @error('email')
                    <label id="email-error" class="error" for="email">{{__('This field is required')}}</label>
                    @enderror

                    <div class="form-floating form-floating-custom mb-2">
                        {{html()->select("active", ["1"=>__('Yes'), "0"=>__('Not')], $user->active )
                                                        ->class('form-select')
                                                        ->placeholder(__("Select"))
                                                        ->required(true)
                        }}
                        <label for="active">{{__('Active')}}</label>
                    </div>
                    @error('active')
                    <label id="active-error" class="error" for="active">{{__('This field is required')}}</label>
                    @enderror
                </div>






                <div class="col-12 offset-md-4 col-md-4 offset-lg-4  col-lg-4 text-center mt-4">

                    <button id="save" type="button" class="btn btn-large btn-info">
                        <i class="fa fa-save"></i>
                        {{__('Save')}}
                    </button>

                </div>
            </div>
            {{ html()->form()->close() }}
        </div>
    </div>
</div>


<script type="text/javascript">
    var iti = null;
    document.addEventListener("DOMContentLoaded", function (event) {

        $("#frm1").validate({
            errorPlacement: function (error, element) {
                element.parents('.form-floating').after(error);
            }
        });

        iti = window.intlTelInput(document.getElementById('phone'), {
            initialCountry: '{{Auth::user()->getCountry->iso2}}',
            strictMode: true,
            separateDialCode: true,
            i18n: INTL_TEL_ES,
            utilsScript: APP_ROUTE + "template/kaiadmin/assets/js/plugin/intl-tel/intl-tel-input-utils.js",
        });

        $("#country_iso2").val(iti.getSelectedCountryData().iso2);

        $("#phone").on('countrychange', function (e) {
            this.value = "";
            $("#country_iso2").val(iti.getSelectedCountryData().iso2);
        });

        $('#save').on('click', function () {
            if ($("#frm1").valid() == true) {
                if (iti.isValidNumber() == true) {
                    showLoading({"icon": "info", "title": "{{__('Processing...')}}", "html": '<i class="fas fa-spinner fa-spin"></i>'});
                    $("#frm1").submit();
                } else {
                      showNotify('danger', "{{__('Wrong cell phone number')}}");
                }
            } else {
                showNotify('danger', "{{__('Missing Required Fields')}}");
            }
        });




    });

</script>
@endsection

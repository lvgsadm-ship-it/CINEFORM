@extends('layouts.kaiadmin-menu')

@section('content')

<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <div class="card-head-row">
                <h4 class="card-title">{{__('Profiles')}}</h4>
                <div class="card-tools">
                    <a href="{{route('profiles')}}" class="btn btn-icon btn-link btn-primary btn-xs">
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
                html()->form("post", route('profiles.permissions', $id))
                        ->autocomplete("off")
                        ->id("frm1")
                        ->open()
            }}
            <div class="row">
                <div class="col-12 col-md-4 ">
                    <div class="form-floating form-floating-custom mb-2">
                        {{
                                html()->input("text", 'name', $profile->name)
                                        ->class('form-control ')
                                        ->placeholder('')
                                        
                                        ->disabled(true)
                        }}
                        <label for="name">{{__('Name')}}</label>
                    </div>

                </div>
                <div class="col-12 col-md-4 ">
                    <div class="form-floating form-floating-custom mb-2">
                        {{
                                html()->input("text", 'description', $profile->description)
                                        ->class('form-control ')
                                        
                                        ->placeholder('')
                                        ->disabled(true)
                        }}
                        <label for="description">{{__('Description')}}</label>
                    </div>
                </div>
                <div class="col-12 col-md-4 ">

                </div>

                <div class="col-5 col-md-1">

                    <div class="nav flex-column nav-pills nav-secondary nav-pills-no-bd nav-pills-icons" role="tablist" aria-orientation="vertical">
                        @foreach($MODULES as $key=>$value)
                        <a class="nav-link {{$key==0 ? 'active':''}}"  data-bs-toggle="pill" href="#v-{{$value->crypt_id}}" role="tab" aria-controls="v-pills-profile-icons" aria-selected="false" tabindex="-1">
                            <i class="fa fa-{{$value->icon}}"></i>
                            {{$value->name}}
                        </a>
                        @endforeach
                    </div>


                </div>

                <div class="col-7 col-md-11">
                    <div class="tab-content" >
                        @foreach($MODULES as $key=>$value)
                        <div class="tab-pane  {{$key==0 ? 'active':'fade'}}" id="v-{{$value->crypt_id}}" role="tabpanel" aria-labelledby="v-pills-home-tab-icons">
                            <div class="row mt-3">
                                <div class="col-12 col-md-12 text-center">
                                    <h1>{{$value->name}}</h1>

                                </div>
                                <div class="col-12 col-md-12 ">


                                    <ul class="nav nav-pills nav-secondary nav-pills-no-bd" id="pills-tab" role="tablist">
                                        @foreach($value->getMenus as $key2=>$value2)
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link {{$key2==0? 'active':''}}" id="pills-home-tab" data-bs-toggle="pill" href="#pills-{{$value->crypt_id}}" role="tab" aria-controls="pills-home" aria-selected="true">
                                                {{$value2->name}}
                                            </a>
                                        </li>
                                        @endforeach
                                    </ul>


                                    <div class="tab-content mt-2 mb-3" id="pills-tabContent">
                                        @foreach($value->getMenus as $key2=>$value2)
                                        <div class="tab-pane {{$key2==0? 'active':'fade'}}" id="pills-{{$value->crypt_id}}" role="tabpanel" aria-labelledby="pills-home-tab">
                                            <table id="table1" class="table table-bordered table-head-bg-info table-bordered-bd-info ">
                                                <thead>
                                                    <tr>
                                                        <th style="width:20px">{{__('N')}}&deg;</th>
                                                        <th>{{__('Process')}}</th>
                                                        <th>{{__('Actions')}}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    
                                                    @foreach($value2->getProcess as $key3=>$value3)
                                                    @php
                                                    $check ='';
                                                    $actions = [];
                                                    foreach($profile->getPermissions as $key4=>$value4){
                                                        if ($value4->id == $value3->id){
                                                            $check ='checked="checked"';
                                                            
                                                            $actions =explode('|', $value4->pivot->actions);
                                                            
                                                            break;
                                                        }
                                                    }
                                                    @endphp
                                                    <tr>
                                                        <td style="text-align: center">{{$key3+1}}</th>
                                                        <td>
                                                            <input {{$check}} style="margin-right:5px;" type="checkbox" name="permissions[{{$value3->crypt_id}}]"  />
                                                            {{$value3->name}}
                                                        </td>
                                                        <td style="padding-left: 15px !important">{!!showActions($value3->actions, $value3->crypt_id, $actions)!!}</d>
                                                        
                                                        
                                                    </tr>

                                                @endforeach
                                                </tbody>
                                            </table>


                                        </div>

                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="col-12 col-md-12 text-center">

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

    document.addEventListener("DOMContentLoaded", function (event) {

        $("#frm1").validate({
            errorPlacement: function (error, element) {
                element.parents('.form-floating').after(error);
            }
        });

        $('#save').on('click', function () {
            if ($("#frm1").valid() == true) {
                showLoading({"icon": "info", "title": "{{__('Processing...')}}", "html": '<i class="fas fa-spinner fa-spin"></i>'});
                $("#frm1").submit();
            }
        });


    });

</script>
@endsection

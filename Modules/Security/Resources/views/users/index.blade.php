@extends('layouts.kaiadmin-menu')

@section('content')

<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <div class="card-head-row">
                <h4 class="card-title">{{__('Users')}}</h4>
                <div class="card-tools">
                    <button onClick="closeCard(this)" class="btn btn-icon btn-link btn-primary btn-xs">
                        <span class="fa fa-times"></span>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 offset-md-1 col-md-10 offset-lg-1  col-lg-10">
                    <table id="table1" class="table table-bordered table-head-bg-info table-bordered-bd-info ">
                        <thead>
                            <tr>
                                <th>{{__('ID')}}</th>
                                <th>{{__('Document')}}</th>
                                <th>{{__('Full Name')}}</th>
                                <th>{{__('Profile')}}</th>
                                <th>{{__('Phone')}}</th>
                                <th>{{__('Actions')}}</th>

                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">

    document.addEventListener("DOMContentLoaded", function (event) {

        table1 = $('#table1').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "order": [[0, 'desc']],

            ajax: {
                url: "{{route('users.list')}}",
                data: function (d) {
                    d.search = $('#search').val()
                }
            },
            initComplete: function () {
                $("#table1_filter").removeClass('dataTables_filter').html('<div class="col-12  col-md-12  col-lg-12"><div class="form-floating form-floating-custom mb-2">{{html()->input("text", "search", "")->class("form-control")->placeholder("")->maxlength(20)}}<label for="search">{{__("Search")}}</label><div onClick="table1.draw();" class="icon-right"><i class="fa fa-search"></i></div></div></div>');
                $("#table1_filter").parent().siblings().eq(0).html('<a href="{{route("users.create")}}"  class="btn btn-large btn-info"> <i class="fa fa-plus"></i> {{__("New User")}}</a>');
            },
            serverSide: true,
            processing: true,

            rowCallback: function (row, data) {
                //$(row).addClass(data.class);
            },
            columns: [
                {data: 'DT_RowIndex'},
                {data: 'full_document'},
                {data: 'full_name'},
                {data: 'get_profile.name'},
                {data: 'cell_phone'},
                {data: 'action'},
            ],
            language: {
                url: "{{url('/')}}/template/kaiadmin/assets/js/plugin/datatables/{{app()->getLocale()}}.json"
            }
        });

    });

</script>
@endsection

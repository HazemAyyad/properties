@extends('dashboard.layouts.app')
@section('style')
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/css/form-validation.css')}}" />
    <style>
        fieldset {
            border: 1px solid #bebfbe;
            padding-left: 26px;
            border-radius: 25px;
        }

        legend {
            padding: 0.2em 0.5em;
            border: 1px solid #0d10d0;
            color: #fd0000;
            font-size: 90%;
            width: 43%;
            text-align: left;
            border-radius: 25px;
        }

        .box_permission ul{
            list-style: none;
            padding-left: 5%;
        }

        .child{
            display: flex;
            align-items: center;
            margin-right: 10%;
        }
        .checkAll{
            margin-left: 20%;
            display: flex;
        }
        .checkAll label{
            margin-bottom: unset;
        }
        fieldset {
            border: 1px solid #bebfbe;
            padding-left: 26px;
            border-radius: 25px;
        }

        legend {
            padding: 0.2em 0.5em;
            border: 1px solid black;
            color: #2f312f;
            font-size: 90%;
            width: 43%;
            text-align: left;
            border-radius: 25px;
        }

        .box_permission ul{
            list-style: none;
            padding-left: 5%;
        }
        .parent{
            font-size: 16px;
            color: black;
            font-weight: bold;
            margin-bottom: 5%;

        }
    </style>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{route('admin.dashboard')}}">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{route('admin.roles.index')}}">{{__('Roles')}}</a>
                </li>
                <li class="breadcrumb-item active">{{__('Edit Role')}}</li>
                <!-- Basic table -->


                <!--/ Basic table -->
            </ol>
        </nav>
        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{__('Edit Property')}}</h5>
                    </div>
                                <div class="card-body">
                                    <form id="mainAdd" method="post" action="javascript:void(0)">
                                        @csrf
                                        <input type="text" name="role_id" value="{{$role->id}}" hidden>
                                        <div class="form-group">
                                            <label class="form-label" for="basic-default-name">{{__('Name')}}</label>
                                            <input type="text" class="form-control" id="name" name="name" value="{{$role->name}}" placeholder="{{__('Name')}}" />
                                        </div>

                                        <div class="form-group">
                                            <fieldset>
                                                <legend style="width: 30%;" class="d-flex">
                                                    <div >
                                                        <h3>{{__('Permission')}}</h3>
                                                    </div>
                                                    <div class="custom-control custom-checkbox " style="margin-left: 5%;margin-right: 5%">
                                                        <input type="checkbox" class="custom-control-input "  id="checkAll"  />
                                                        <label class="custom-control-label" for="checkAll"> {{__('Check All')}}</label>
                                                    </div>


                                                </legend>
                                                <div class="row">
                                                    @foreach ($permissions as $permission)
                                                        <div class="box_permission col-md-3 mr-2 mb-2 mt-2 ml-2">
                                                            <ul>
                                                                <li>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <input type="checkbox" class="custom-control-input option_{{$permission->id}}" name="permission[]" id="option_{{$permission->id}}" value="{{$permission->id}}"
                                                                            {{ in_array($permission->id, $rolePermissions)? "checked" : "" }}/>
                                                                        <label class="custom-control-label" for="option_{{$permission->id}}">
                                                                            @if(App::isLocale('en'))
                                                                                {{$permission->name}}
                                                                            @else
                                                                                {{$permission->ar_name}}
                                                                            @endif
                                                                        </label>
                                                                    </div>
                                                                    <ul>
                                                                        <li>
                                                                            <ul>
                                                                                @foreach($permission->children as $child)
                                                                                    <li>
                                                                                        <div class="custom-control custom-checkbox">
                                                                                            <input type="checkbox" class="custom-control-input subOption_{{$permission->id}}" name="permission[]"
                                                                                                   id="customCheck_{{$child->id}}" value="{{$child->id}}" {{ in_array($child->id, $rolePermissions)? "checked" : "" }}/>
                                                                                            <label class="custom-control-label" for="customCheck_{{$child->id}}">
                                                                                                @if(App::isLocale('en'))
                                                                                                    {{$child->name}}
                                                                                                @else
                                                                                                    {{$child->ar_name}}
                                                                                                @endif
                                                                                            </label>
                                                                                        </div>

                                                                                    </li>
                                                                                @endforeach
                                                                            </ul>
                                                                        </li>
                                                                    </ul>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </fieldset>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 text-center">
                                                <button type="submit" class="btn btn-primary " id="add_form" name="submit" value="Submit" >
                                                    {{__('Save')}}
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Content-->
@endsection
@section('scripts')
    <!-- BEGIN: Page Vendor JS-->
    <script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
    <script src="{{asset('assets/js/jquery.validate.min.js')}}"></script>
    <!-- BEGIN: Page JS-->
{{--    <script src="{{asset('app-assets/custom/role-validation.js')}}"></script>--}}
    <!-- END: Page JS-->

    <script type="text/javascript">
        function deleteRole(id) {
            var idRow =document.getElementById("role_row_"+id)
            swal.fire({
                title: "حذف؟",
                text: "الرجاء التأكيد على الموافقة",
                type: "warning",
                showCancelButton: !0,
                confirmButtonText: "نعم, اتم الحذف!",
                cancelButtonText: "لا,تراجع!",
                confirmButtonColor: "#DD6B55",
                reverseButtons: !0
            }).then(function (e) {
                if (e.value === true) {
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        type: 'delete',
                        url: "{{ url("admin/roles/delete/")}}/" + id,
                        data: {_token: CSRF_TOKEN},
                        dataType: 'JSON',
                        success: function (response) {

                            if (response.status === true) {
                                swal.fire("Done!", response.msg, "success");
                                idRow.remove();

                            } else {
                                swal.fire("Error!", response.msg, "error");
                            }
                        }
                    });

                } else {
                    e.dismiss;
                }

            }, function (dismiss) {
                return false;
            })
        }
    </script>
    <script>
        $("#checkAll").click(function () {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
    </script>
    <script>
        $(document).ready(function () {
            var checkboxes = document.querySelectorAll('input[class*="subOption_"]'),
                checkall = document.querySelectorAll('input[id*="option_"]')
            for(let i=0; i<checkboxes.length; i++) {
                checkboxes[i].onclick = function() {

                    let current_class =this.classList[1].split("_")[1]
                    // var checkedCount = document.querySelectorAll('input.subOption:checked').length;
                    let  checkAll = document.getElementById('option_'+current_class);

                    let checkedCount = document.querySelectorAll('input[class*="subOption_'+current_class+'"]:checked').length;
                    console.log(checkedCount)
                    console.log(checkAll)

                    checkAll.checked = checkedCount > 0;
                    var checkBoxes = document.querySelectorAll('input[class*="subOption_'+current_class+'"]')
                    // $(document).ready(function () {
                    checkAll.indeterminate = checkedCount > 0 && checkedCount < checkBoxes.length;
                    // });
                }
            }
            for(let i=0; i<checkall.length; i++) {
                checkall[i].onclick = function() {
                    let current_class =this.id.split("_")[1]
                    // var checkboxes = document.querySelectorAll('input[class*="subOption_"]'),
                    let checkBoxes=$(`.subOption_${current_class}`)
                    // console.log(current_class)
                    // console.log(checkBoxes)

                    for(let i=0; i<checkBoxes.length; i++) {
                        checkBoxes[i].checked = this.checked;
                    }
                }
            }

        });
    </script>
    <script>
        $(document).ready(function() {
            function myHandel(obj, id) {
                if ('responseJSON' in obj)
                    obj.messages = obj.responseJSON;
                $('input,select,textarea').each(function () {
                    var parent = "";
                    if ($(this).parents('.form-group').length > 0)
                        parent = $(this).parents('.form-group');
                    if ($(this).parents('.input-group').length > 0)
                        parent = $(this).parents('.input-group');
                    var name = $(this).attr("name");
                    if (obj.messages && obj.messages[name] && ($(this).attr('type') !== 'hidden')) {
                        var error_message = '<div class="col-md-8 offset-md-3 custom-error"><p style="color: red">' + obj.messages[name][0] + '</p></div>'
                        parent.append(error_message);
                    }
                });
            }

            $(document).on("click", "#add_form", function() {

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }

                });
                var postData = new FormData(this.form);
                $('#add_form').html('');
                $('#add_form').append('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'+
                    '<span class="ml-25 align-middle">{{__('Editing')}}...</span>');
                $.ajax({
                    url: "{{ route('admin.roles.update')}}",
                    type: "POST",
                    data: postData,
                    processData: false,
                    contentType: false,
                    success: function( response ) {
                        $('#add_form').empty();
                        $('#add_form').html('{{__('Save')}}');
                        // swal.fire({
                        //     type: 'success',
                        //     title: response.success
                        // });
                        setTimeout(function () {
                            toastr['success'](
                                response.success,
                                {
                                    closeButton: true,
                                    tapToDismiss: false
                                }
                            );
                        }, 200);

                        $('.custom-error').remove();

                    },
                    error: function (data) {
                        $('.custom-error').remove();
                        $('#add_form').empty();
                        $('#add_form').html('{{__('Save')}}');
                        var response = data.responseJSON;
                        if (data.status == 422) {
                            if (typeof(response.responseJSON) != "undefined") {
                                myHandel(response);
                                setTimeout(function () {
                                    toastr['error'](
                                        response.message,
                                        {
                                            closeButton: true,
                                            tapToDismiss: false
                                        }
                                    );
                                }, 2000);
                            }
                        } else {
                            swal.fire({
                                icon: 'error',
                                title: response.message
                            });
                        }
                    }
                });
            });

        });

    </script>
@endsection

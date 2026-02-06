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

        .box_permission ul {
            list-style: none;
            padding-left: 5%;
        }

        .child {
            display: flex;
            align-items: center;
            margin-right: 10%;
        }

        .checkAll {
            margin-left: 20%;
            display: flex;
        }

        .checkAll label {
            margin-bottom: unset;
        }

        .parent {
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
                <li class="breadcrumb-item active">{{__('Create Role')}}</li>
            </ol>
        </nav>
        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{__('Create Property')}}</h5>
                    </div>
                    <div class="card-body">
                        <form id="mainAdd" method="post" action="javascript:void(0)">
                            @csrf
                            <div class="form-group">
                                <label class="form-label" for="basic-default-name">{{__('Name')}}</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="{{__('Name')}}" required/>
                            </div>
                            <div class="form-group mt-2">
                                <fieldset>
                                    <legend style="width: 30%;" class="d-flex">
                                        <div>
                                            <h3>{{__('Permission')}}</h3>
                                        </div>
                                        <div class="custom-control custom-checkbox " style="margin-left: 5%;margin-right: 5%">
                                            <input type="checkbox" class="custom-control-input" id="checkAll" />
                                            <label class="custom-control-label" for="checkAll">{{__('Check All')}}</label>
                                        </div>
                                    </legend>
                                    <div class="row">
                                        @foreach ($permissions as $permission)
                                            <div class="box_permission col-md-3 mr-2 mb-2 mt-2 ml-2">
                                                <ul>
                                                    <li>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input option_{{$permission->id}}" name="permission[]" id="option_{{$permission->id}}" value="{{$permission->id}}" />
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
                                                                                <input type="checkbox" class="custom-control-input subOption_{{$permission->id}}" name="permission[]" id="customCheck_{{$child->id}}" value="{{$child->id}}" />
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
                                    <button type="submit" class="btn btn-primary" id="add_form">{{__('Save')}}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
    <script src="{{asset('assets/js/jquery.validate.min.js')}}"></script>

    <script>
        $("#checkAll").click(function () {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });

        $(document).ready(function () {
            var checkboxes = document.querySelectorAll('input[class*="subOption_"]'),
                checkall = document.querySelectorAll('input[id*="option_"]');

            function toggleGroupCheckboxes(parentId) {
                var parentCheckbox = $("#option_" + parentId);
                var childCheckboxes = $(".subOption_" + parentId);

                parentCheckbox.on('click', function () {
                    childCheckboxes.prop('checked', this.checked);
                });

                childCheckboxes.on('click', function () {
                    var totalChecked = childCheckboxes.filter(':checked').length;
                    parentCheckbox.prop('checked', totalChecked === childCheckboxes.length);
                    parentCheckbox.prop('indeterminate', totalChecked > 0 && totalChecked < childCheckboxes.length);
                });
            }

            $('input[id^="option_"]').each(function () {
                var parentId = $(this).attr('id').split('_')[1];
                toggleGroupCheckboxes(parentId);
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            function myHandel(obj) {
                if ('responseJSON' in obj)
                    obj.messages = obj.responseJSON;
                $('input,select,textarea').each(function () {
                    var parent = $(this).parents('.form-group');
                    var name = $(this).attr("name");
                    if (obj.messages && obj.messages[name]) {
                        var error_message = '<div class="col-md-8 offset-md-3 custom-error"><p style="color: red">' + obj.messages[name][0] + '</p></div>';
                        parent.append(error_message);
                    }
                });
            }

            $(document).on("click", "#add_form", function() {
                var form = $(this.form);
                if(!form.valid()) return false;

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                var postData = new FormData(this.form);
                $('#add_form').html('<span class="spinner-border spinner-border-sm align-middle ms-2"></span><span class="ml-25 align-middle">{{__('Saving')}}...</span>');

                $.ajax({
                    url: '{{ route('admin.roles.store')}}',
                    type: "POST",
                    data: postData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        $('#add_form').html('{{__('Save')}}');
                        setTimeout(function () {
                            toastr['success'](response.success, { closeButton: true, tapToDismiss: false });
                        }, 200);
                        document.getElementById("mainAdd").reset();
                        $('.custom-error').remove();
                    },
                    error: function (data) {
                        $('.custom-error').remove();
                        $('#add_form').html('{{__('Save')}}');
                        if (data.status === 422) {
                            myHandel(data.responseJSON);
                            setTimeout(function () {
                                toastr['error'](data.responseJSON.message, { closeButton: true, tapToDismiss: false });
                            }, 200);
                        } else {
                            swal.fire({ icon: 'error', title: data.responseJSON.message });
                        }
                    }
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            jQuery.extend(jQuery.validator.messages, {
                required: "{{__('This field is required.')}}",
                remote: "{{__('Please fix this field.')}}",
                email: "{{__('Please enter a valid email address.')}}",
                number: "{{__('Please enter a valid number.')}}",
                maxlength: jQuery.validator.format("{{__('Please enter no more than {0} characters.')}}"),
                minlength: jQuery.validator.format("{{__('Please enter at least {0} characters.')}}")
            });
        });
    </script>
@endsection

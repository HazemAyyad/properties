@extends('dashboard.layouts.app')
@section('style')

    <link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/css/form-validation.css')}}" />
    <link rel="stylesheet" href="{{asset('site/quill/quill.snow.css')}}" />
@endsection
@section('content')

    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{route('admin.dashboard')}}">{{__('Home')}}</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{route('admin.blogs.index')}}">{{__('Blogs')}}</a>
                </li>
                <li class="breadcrumb-item active">{{__('Edit Blog')}}</li>
                <!-- Basic table -->

                <!--/ Basic table -->
            </ol>
        </nav>
        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{__('Edit Blog')}}</h5>
                    </div>
                    <div class="card-body">
                        <form id="mainAdd" method="post" action="javascript:void(0)" >

                            @foreach($lang as $item)
                                <div class="form-group mb-3">
                                    <label class="form-label" for="title_{{$item}}">{{__('Title')}} {{$item}}</label>
                                    <input type="text" class="form-control" name="title_{{$item}}" id="title_{{$item}}" value="{{$blog['title_'.$item]}}" placeholder="{{__('Title')}} {{$item}}" required>
                                </div>
                            @endforeach
                            @foreach($lang as $item)
                                <div class="form-group mb-3">
                                    <label class="form-label" for="short_description_{{$item}}">{{__('Short Description')}} {{$item}}</label>
                                    <textarea  class="form-control" rows="5"  name="short_description_{{$item}}" id="short_description_{{$item}}" required>{{$blog['short_description_'.$item]}}</textarea>
                                </div>
                            @endforeach
                            @foreach($lang as $item)
                                <div class="form-group mb-3">
                                    <label class="form-label" for="description_{{$item}}">{{__('Description')}} {{$item}}</label>
                                    <div id="description_{{$item}}">
                                        {!! $blog['description_'.$item] !!}
                                    </div>

                                </div>
                            @endforeach
                            <div class="form-group mb-3">
                                <label class="form-label" for="category_id">{{__('Category')}} </label>
                                <select name="category_id" id="category_id" class="select2 form-select">
                                    <option value=""></option>
                                    @foreach($categories as $category)
                                        <option value="{{$category->id}}" {{$blog->category_id==$category->id?'selected':''}}>{{$category->name}}</option>
                                    @endforeach

                                </select>

                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label" for="tags">{{__('Tags')}} </label>
                                <select name="tags[]" multiple id="tags" class="select2 form-select">
                                    <option value=""></option>
                                    @foreach($tags as $tag)
                                        <option value="{{$tag->id}}" {{in_array($tag->id,json_decode($blog->tags,true))?'selected':''}}>{{$tag->name}}</option>
                                    @endforeach

                                </select>

                            </div>
                            <div class="form-group mb-3">
                                <label for="photo" class="form-label">photo</label>
                                <input class="form-control" name="photo" type="file" id="photo"  >
                            </div>
                            <button type="submit" class="btn btn-primary waves-effect waves-light" id="add_form">
                                {{__('Save')}}
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- / Content -->

@endsection
@section('scripts')
    <!-- BEGIN: Page Vendor JS-->

    <script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
    <script src="{{asset('assets/js/jquery.validate.min.js')}}"></script>
    <!-- END: Page JS-->
    <script src="{{asset('site/quill/quill.js')}}"></script>

    <script>
        /**
         * Selects & Tags
         */
        'use strict';

        $(function () {
            const select2 = $('.select2');

            if (select2.length) {
                select2.each(function () {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Select value',
                        dropdownParent: $this.parent()
                    });
                });
            }
        });

    </script>


    <!-- A friendly reminder to run on a server, remove this during the integration. -->
    <script>
        window.onload = function() {
            if ( window.location.protocol === "file:" ) {
                alert( "This sample requires an HTTP server. Please serve this file with a web server." );
            }
        };
    </script>

    <script>
        $(document).ready(function() {
            const fullToolbar = [
                [{ font: [] }, { size: [] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ color: [] }, { background: [] }],
                [{ script: 'super' }, { script: 'sub' }],
                [{ header: '1' }, { header: '2' }, 'blockquote', 'code-block'],
                [{ list: 'ordered' }, { list: 'bullet' }, { indent: '-1' }, { indent: '+1' }],
                ['direction', { align: [] }],
                ['link', 'image', 'video', 'formula'],
                ['clean']
            ];
            @foreach($lang as $item)
            const description_{{$item}} = new Quill('#description_{{$item}}', {
                bounds: '#description_{{$item}}',
                placeholder: 'Type Something...',
                modules: {
                    formula: true,
                    toolbar: fullToolbar
                },
                theme: 'snow'
            });
            @endforeach

            $('#mainAdd').submit(function(e) {
                e.preventDefault();

                if (!$(this).valid()) {
                    return false;
                }

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                var postData = new FormData(this);
                @foreach($lang as $item)
                var quillContent = description_{{$item}}.root.innerHTML;
                postData.append('description_{{$item}}', quillContent);
                @endforeach

                $('#add_form').html('');
                $('#add_form').append('<span class="spinner-border spinner-border-sm align-middle ms-2"></span>' +
                    '<span class="ml-25 align-middle">{{ __('Saving') }}...</span>');

                $.ajax({
                    url: '{{ route('admin.blogs.update',$blog->id) }}',
                    type: "POST",
                    data: postData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#add_form').html('{{ __('Save') }}');
                        setTimeout(function() {
                            toastr.success(response.success, {
                                closeButton: true,
                                tapToDismiss: false
                            });
                        }, 200);
                        $('#mainAdd')[0].reset();
                        $('.custom-error').remove();

                        @foreach($lang as $item)

                            description_{{$item}}.root.innerHTML = '';
                        @endforeach
                    },
                    error: function(data) {
                        $('.custom-error').remove();
                        $('#add_form').html('{{ __('Save') }}');
                        var response = data.responseJSON;
                        if (data.status == 422) {
                            if (response && response.errors) {
                                $.each(response.errors, function(key, value) {
                                    var error_message = '<div class="custom-error"><p style="color: red">' + value[0] + '</p></div>';
                                    $('[name="' + key + '"]').closest('.form-group').append(error_message);
                                });
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

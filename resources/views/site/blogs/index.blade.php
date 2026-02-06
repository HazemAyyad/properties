@extends('site.layouts.app')

@section('style')
    <!-- Add your styles here if needed -->
@endsection

@section('content')
    <!-- Page Title -->
    <section class="flat-title-page">
        <div class="container">
            <h2 class="text-center">{{__('Latest News')}}</h2>
            <ul class="breadcrumb">
                <li><a href="{{route('site.index')}}">{{__('Home')}}</a></li>
                <li>/ {{__('Blog')}}</li>
            </ul>


        </div>
    </section>
    <!-- End Page Title -->
    <!-- Section Blog Grid -->
    <section class="flat-section">
        <div class="container">
            <div class="row">
                @foreach($blogs as $blog)
                <div class="col-lg-4 col-md-6">
                    <a href="{{route('site.blog.show',$blog->slug)}}" class="flat-blog-item hover-img">
                        <div class="img-style">
                            @php
                                $imagePath = asset($blog->photo);
                                $correctedImagePath = str_replace('/public/public/', '/public/', $imagePath);
                            @endphp
                            <img src="{{$correctedImagePath}}" alt="img-blog">
                            <span class="date-post">{{ \Carbon\Carbon::parse($blog->created_at)->format('F j, Y') }}</span>
                        </div>
                        <div class="content-box">
                            <div class="post-author">
                                <span class="fw-7">{{$blog->user->name}}</span>
                                <span>{{$blog->category->name}}</span>
                            </div>
                            <h6 class="title">{{$blog->title_en}}</h6>
                            <p class="description">{{ \Illuminate\Support\Str::limit($blog->short_description_en, 90, '...') }}</p>
                        </div>

                    </a>
                </div>
                @endforeach
                <div class="col-12 text-center">
                    <!-- Pagination Links -->
                    <div class="pagination-wrapper">
                        {{ $blogs->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Section Blog Grid -->
@endsection
@section('scripts')

@endsection

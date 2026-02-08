@extends('site.layouts.app')

@section('style')
    <style>
        #social-links ul {
            gap: 12px;
            display: flex;
            flex-wrap: wrap;
        }

    </style>
@endsection

@section('content')
    <section class="flat-banner-blog">
        @php
            $imagePath = asset($blog->photo);
            $correctedImagePath = str_replace('/public/public/', '/public/', $imagePath);
        @endphp
        <img src="{{$correctedImagePath}}" alt="banner-blog">
    </section>

    <!-- Section Blog Detail -->
    <section class="flat-section-v2">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <div class="flat-blog-detail">
                        <a href="#" class="blog-tag primary">{{$blog->category->name}}</a>
                        <h3 class="text-capitalize">{{$blog->title_en}}</h3>
                        <div class="mt-12 d-flex align-items-center gap-16">
                            <div class="avatar avt-40 round">
                                @php
                                    $imagePath = asset($blog->user->photo);
                                    $correctedImagePath = str_replace('/public/public/', '/public/', $imagePath);
                                @endphp
                                <img src="{{$correctedImagePath}}" alt="avatar">
                            </div>
                            <div class="post-author style-1">
                                <span>{{$blog->user->name}}</span>
                                <span>{{ \Carbon\Carbon::parse($blog->created_at)->format('F j, Y') }}</span>
                            </div>
                        </div>
                        <div class="my-40">
                            <p class="body-2 text-black">
                                {{$blog->short_description_en}}
                            </p>

                        </div>
                        {!! $blog->description_en !!}
                        <div class="my-40 d-flex justify-content-between flex-wrap gap-16">
                            <div class="d-flex flex-wrap align-items-center gap-12">
                                <span class="text-black">Tag:</span>
                                <ul class="d-flex flex-wrap gap-12">
                                    @foreach($blog->full_tags as $tag)
                                        <li><a href="#" class="blog-tag">{{$tag->name}}</a></li>
                                    @endforeach

                                </ul>
                            </div>
                            <div class="d-flex flex-wrap align-items-center gap-16">
                                <span class="text-black">Share:</span>
                                {!! $shareButtons !!}


{{--                                {!! Share::page(route('site.blog.show',$blog->slug))->facebook()->twitter()->whatsapp() !!}--}}

                            </div>
                        </div>
                        <div class="post-navigation">
                            @if($previous)
                                <div class="previous-post">
                                    <a   href="{{route('site.blog.show',$previous->slug)}}">
                                        <div class="subtitle">Previous</div>
                                        <div class="h7 fw-7 text-black text-capitalize">{{$previous->title_en}}</div>
                                    </a>
                                </div>
                            @else
                                <div class="previous-post">
                                    <a   href="#">
                                        <div class="subtitle">Previous</div>

                                    </a>
                                </div>

                            @endif
                            @if($next)
                                <div class="next-post">
                                    <a   href="{{route('site.blog.show',$next->slug)}}">
                                        <div class="subtitle">Next</div>
                                        <div class="h7 fw-7 text-black text-capitalize"> {{$next->title_en}}</div>
                                    </a>
                                </div>

                                @else
                                <div class="next-post">
                                    <a   href="#">
                                        <div class="subtitle">Next</div>

                                    </a>
                                </div>


                            @endif

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--End Section Blog Detail -->

    <!-- Section Latest Post -->
    <section class="flat-section flat-latest-post">
        <div class="container">
            <div class="box-title-relatest text-center">
                <div class="text-subheading text-primary">Relatest Post</div>
                <h5 class="mt-4">News insight</h5>
            </div>
            <div class="row">
                @foreach($blogs as $blog)
                    <div class="box col-lg-4 col-md-6">
                        <a href="{{route('site.blog.show',$blog->slug)}}" class="flat-blog-item hover-img wow fadeIn" data-wow-delay=".2s" data-wow-duration="2000ms">
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
                                    <span class="fw-6">{{$blog->user->name}}</span>
                                    <span>{{$blog->category->name}}</span>
                                </div>
                                <h6 class="title">{{$blog->title_en}}</h6>
                                <p class="description">{{ \Illuminate\Support\Str::limit($blog->short_description_en, 90, '...') }}</p>
                            </div>

                        </a>
                    </div>
                @endforeach


            </div>
        </div>
    </section>
    <!-- End Latest Post -->

@endsection
@section('scripts')
    <script src="{{ asset('js/share.js') }}"></script>
    <script src="{{asset('assets/js/jquery.validate.min.js')}}"></script>

@endsection

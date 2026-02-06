@extends('site.layouts.app')

@section('style')
    <!-- Add your styles here if needed -->
@endsection

@section('content')
    <!-- Page Title -->
    <section class="flat-title-page style-2">
        <div class="container">
            <ul class="breadcrumb">
                <li><a href="{{ url('/') }}">{{__('Home') }}</a></li>
                <li>/ {{__('Pages') }}</li>
                <li>/ {{__('Frequently Asked Questions') }}</li>
            </ul>
            <h2 class="text-center">{{__('FAQs') }}</h2>
        </div>
    </section>
    <!-- End Page Title -->

    <section class="flat-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">




                    @foreach ($categories as $category)
                        @php
                            // Filter FAQs for the current category using Collection's filter method
                            $filteredFaqs = $faqs->filter(function ($faq) use ($category) {
                                return $faq->category_id == $category->id;
                            });
                        @endphp

                        @if ($filteredFaqs->isNotEmpty())
                            <div class="tf-faq">
                                <h5>{{ $category->title }}</h5> <!-- Assuming the category name is stored in the name property -->
                                <ul class="box-faq" id="wrapper-faq-{{ $category->id }}">
                                    @foreach ($filteredFaqs as $faq)
                                        <li class="faq-item">
                                            <a href="#accordion-faq-{{ $faq->id }}" class="faq-header collapsed" data-bs-toggle="collapse" aria-expanded="false" aria-controls="accordion-faq-{{ $faq->id }}">
                                                {{ App::isLocale('en') ? $faq->title_en : $faq->title_ar }}
                                            </a>
                                            <div id="accordion-faq-{{ $faq->id }}" class="collapse" data-bs-parent="#wrapper-faq-{{ $category->id }}">
                                                <p class="faq-body">
                                                    {{ App::isLocale('en') ? $faq->answer_en : $faq->answer_ar }}
                                                </p>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    @endforeach


                </div>
            </div>
        </div>
    </section>

@endsection

@section('scripts')
    <!-- Add your scripts here if needed -->
@endsection

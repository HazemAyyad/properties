<div class="widget-box single-property-contact bg-surface">
    <div class="h7 title fw-7">{{__('Contact Sellers')}}</div>
    <div class="box-avatar">
        <div class="avatar avt-100 round">
            @if($property->user_id!=null && !empty($property->user->photo))
                @php
                    $photoPath = ltrim(str_replace('/public', '', $property->user->photo), '/');
                @endphp
                <img src="{{ asset($photoPath) }}" alt="{{ $property->user->name }}" loading="lazy">
            @else
                @php
                    $avatarPlaceholder = 'data:image/svg+xml,' . rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 128 128" width="128" height="128"><rect width="128" height="128" fill="#e0e0e0"/><circle cx="64" cy="48" r="24" fill="#999"/><ellipse cx="64" cy="110" rx="40" ry="30" fill="#999"/></svg>');
                @endphp
                <img src="{{ $avatarPlaceholder }}" alt="{{ $property->user_id ? ($property->user->name ?? '') : config('app.name') }}" loading="lazy">
            @endif
        </div>
        <div class="info">
            <div class="text-1 name">
                @if($property->user_id!=null)
                    {{$property->user->name}}
                @else
                    {{config('app.name')}}

                @endif
            </div>
            @if($property->user_id!=null)
                <span>{{$property->user->mobile}} {{$property->user->email}} </span>
            @else
                <span>{{$data_settings['phone']}} {{$data_settings['email']}}</span>

            @endif


        </div>
    </div>
    <form  class="contact-form" id="mainAdd" method="post" action="javascript:void(0)">
        <div class="ip-group form-group">
            <label for="name">{{__('Full Name')}}:</label>
            <input type="text" name="name" required placeholder="Jony Dane" class="form-control">
        </div>
        <div class="ip-group form-group">
            <label for="phone">{{__('Phone Number')}}:</label>
            <input type="text" name="phone" required placeholder="ex 0123456789" class="form-control">
        </div>
        <div class="ip-group form-group">
            <label for="email">{{__('Email Address')}}:</label>
            <input type="text" name="email" required placeholder="themesflat@gmail.com" class="form-control">
        </div>
        <div class="ip-group form-group">
            <label for="subject">{{__('Your Message')}}:</label>
            <textarea id="comment-message" required name="subject" rows="4" tabindex="4"
                      placeholder="{{__('Message')}}" aria-required="true"></textarea>
        </div>
        <button type="submit" class="tf-btn primary w-100" id="add_form">{{__('Send Message')}}</button>
    </form>
</div>

<div class="widget-box single-property-contact bg-surface">
    <div class="h7 title fw-7">Contact Sellers</div>
    <div class="box-avatar">
        <div class="avatar avt-100 round">
            @if($property->user_id!=null)
                <img src="{{$property->user->photo}}" alt="{{$property->user->name}}">

            @else
                <img src="https://images.ctfassets.net/lh3zuq09vnm2/yBDals8aU8RWtb0xLnPkI/19b391bda8f43e16e64d40b55561e5cd/How_tracking_user_behavior_on_your_website_can_improve_customer_experience.png" alt="avt">
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
            <label for="name">Full Name:</label>
            <input type="text" name="name" required placeholder="Jony Dane" class="form-control">
        </div>
        <div class="ip-group form-group">
            <label for="phone">Phone Number:</label>
            <input type="text" name="phone" required placeholder="ex 0123456789" class="form-control">
        </div>
        <div class="ip-group form-group">
            <label for="email">Email Address:</label>
            <input type="text" name="email" required placeholder="themesflat@gmail.com" class="form-control">
        </div>
        <div class="ip-group form-group">
            <label for="subject">Your Message:</label>
            <textarea id="comment-message" required name="subject" rows="4" tabindex="4"
                      placeholder="Message" aria-required="true"></textarea>
        </div>
        <button type="submit" class="tf-btn primary w-100" id="add_form">Send Message</button>
    </form>
</div>

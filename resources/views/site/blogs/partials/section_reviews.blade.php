<div class="single-property-element single-wrapper-review">
    <div class="box-title-review d-flex justify-content-between align-items-center flex-wrap gap-20">
        <div class="h7 fw-7">Guest Reviews</div>
{{--        <a href="#" class="tf-btn">View All Reviews</a>--}}
    </div>
    <div class="wrap-review">
        <ul class="box-review">
            @foreach($property->reviews as $review)


            <li class="list-review-item">
                <div class="avatar avt-60 round">
                    <img src="{{$review->user->photo}}" alt="avatar">
                </div>
                <div class="content">
                    <div class="name h7 fw-7 text-black">{{$review->user->name}}
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                             xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                  d="M0 8C0 12.4112 3.5888 16 8 16C12.4112 16 16 12.4112 16 8C16 3.5888 12.4112 0 8 0C3.5888 0 0 3.5888 0 8ZM12.1657 6.56569C12.4781 6.25327 12.4781 5.74673 12.1657 5.43431C11.8533 5.1219 11.3467 5.1219 11.0343 5.43431L7.2 9.26863L5.36569 7.43431C5.05327 7.12189 4.54673 7.12189 4.23431 7.43431C3.9219 7.74673 3.9219 8.25327 4.23431 8.56569L6.63432 10.9657C6.94673 11.2781 7.45327 11.2781 7.76569 10.9657L12.1657 6.56569Z"
                                  fill="#198754" />
                        </svg>
                    </div>
                    <span class="mt-4 d-inline-block  date body-3 text-variant-2">
                        {{ \Carbon\Carbon::parse($review->created_at)->format('F j, Y') }}
                    </span>
                    <ul class="mt-8 list-star">
                        @for ($i = 0; $i < $review->star; $i++)
                            <li class="icon-star"></li>
                        @endfor

                        @if($review->star < 5)
                            @for($i = $review->star; $i < 5; $i++)
                                <li class="icon-star" style="color: #f7f7f7"></li>
                            @endfor
                        @endif
                    </ul>
                    <p class="mt-12 body-2 text-black">
                        {{$review->comment}}
                    </p>

                </div>
            </li>
            @endforeach

        </ul>
    </div>
    @auth('web')
        @if(!$property->reviews->contains('user_id', auth()->id()))
    <div class="wrap-form-comment">
        <h6>Write A Review</h6>
        <div id="comments" class="comments">
            <div class="respond-comment">
                <form    id="reviewForm" class="comment-form form-submit" method="post" action="javascript:void(0)">




                    <fieldset class="form-wg">

                        <div class="star-rating">
                            <input type="radio" id="5-stars" name="rating" value="5" />
                            <label for="5-stars" class="star">&#9733;</label>
                            <input type="radio" id="4-stars" name="rating" value="4" />
                            <label for="4-stars" class="star">&#9733;</label>
                            <input type="radio" id="3-stars" name="rating" value="3" />
                            <label for="3-stars" class="star">&#9733;</label>
                            <input type="radio" id="2-stars" name="rating" value="2" />
                            <label for="2-stars" class="star">&#9733;</label>
                            <input type="radio" id="1-star" name="rating" value="1" />
                            <label for="1-star" class="star">&#9733;</label>
                        </div>

                    </fieldset>
                    <fieldset class="form-wg">
                        <label class="sub-ip">Review</label>
                        <textarea id="comment-message" name="comment" rows="4" tabindex="4"
                                  placeholder="Write comment " aria-required="true"></textarea>
                    </fieldset>
                    <button class="form-wg tf-btn primary" id="store_reviews" name="submit" type="submit">
                        <span>Post Comment</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
        @endif
    @endauth
</div>

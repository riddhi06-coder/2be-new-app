<!doctype html>
<html lang="en">

    <head>
        @include('components.frontend.head')
    </head>

    <body>

        @include('components.frontend.employee_header')

            <section class="pumping-log">
                <div class="container">
                    <div class="col-md-12">
                        <div class="pumping-log__content">
                            <h1 class="pumping-log__title">
                                <span class="pumping-log__brand">Customer Reviews</span>
                            </h1>
                            <p class="pumping-log__description">
                                See what our customers are saying about 2B Environmental on Google.
                            </p>
                        </div>
                    </div>
                </div>
                <svg class="shape-one" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none">
                    <path class="elementor-shape-fill" d="M421.9,6.5c22.6-2.5,51.5,0.4,75.5,5.3c23.6,4.9,70.9,23.5,100.5,35.7c75.8,32.2,133.7,44.5,192.6,49.7
                    c23.6,2.1,48.7,3.5,103.4-2.5c54.7-6,106.2-25.6,106.2-25.6V0H0v30.3c0,0,72,32.6,158.4,30.5c39.2-0.7,92.8-6.7,134-22.4
                    c21.2-8.1,52.2-18.2,79.7-24.2C399.3,7.9,411.6,7.5,421.9,6.5z"></path>
                </svg>
            </section>

            <section class="reviews-wrap">
                <div class="container">

                    {{-- Summary bar --}}
                    @if($count > 0)
                        <div class="reviews-summary">
                            <div class="reviews-summary__score">
                                <span class="reviews-summary__num">{{ number_format($average, 1) }}</span>
                                <span class="reviews-summary__stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fa fa-star {{ $i <= round($average) ? 'is-on' : '' }}"></i>
                                    @endfor
                                </span>
                                <span class="reviews-summary__count">Based on {{ $count }} Google review{{ $count === 1 ? '' : 's' }}</span>
                            </div>
                            <div class="reviews-summary__brand">
                                <span class="g-logo" aria-hidden="true">
                                    <span style="color:#4285F4;">G</span><span style="color:#EA4335;">o</span><span style="color:#FBBC05;">o</span><span style="color:#4285F4;">g</span><span style="color:#34A853;">l</span><span style="color:#EA4335;">e</span>
                                </span>
                                <span class="reviews-summary__brandlabel">Reviews</span>
                            </div>
                        </div>
                    @endif

                    {{-- Reviews grid --}}
                    <div class="row g-4">
                        @forelse($reviews as $review)
                            <div class="col-lg-4 col-md-6">
                                <article class="review-card">
                                    <div class="review-card__head">
                                        <div class="review-card__avatar">
                                            @if($review->reviewer_photo_url)
                                                <img src="{{ $review->reviewer_photo_url }}" alt="{{ $review->reviewer_name }}" loading="lazy">
                                            @else
                                                <span>{{ $review->initials }}</span>
                                            @endif
                                        </div>
                                        <div class="review-card__who">
                                            <div class="review-card__name">{{ $review->reviewer_name }}</div>
                                            <div class="review-card__date">
                                                {{ optional($review->reviewed_at)->format('M d, Y') }}
                                            </div>
                                        </div>
                                        <i class="fa fa-google review-card__g" aria-hidden="true"></i>
                                    </div>

                                    <div class="review-card__stars">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fa fa-star {{ $i <= $review->rating ? 'is-on' : '' }}"></i>
                                        @endfor
                                    </div>

                                    @if($review->comment)
                                        <p class="review-card__text">{{ $review->comment }}</p>
                                    @endif

                                    @if($review->reply_comment)
                                        <div class="review-card__reply">
                                            <div class="review-card__reply-label"><i class="fa fa-reply"></i> Response from the owner</div>
                                            <p>{{ $review->reply_comment }}</p>
                                        </div>
                                    @endif
                                </article>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="reviews-empty">
                                    <i class="fa fa-star-o"></i>
                                    <p>No reviews to show yet. Check back soon!</p>
                                </div>
                            </div>
                        @endforelse
                    </div>

                </div>
            </section>

        @include('components.frontend.footer')

        @include('components.frontend.main-js')

    </body>

</html>

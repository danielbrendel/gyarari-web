<div class="container">
	<div class="columns">
		<div class="column is-2"></div>

		<div class="column is-8">
			<div class="content-section">
                <div>
				    <div class="is-inline-block">
                        <h3>{{ $photo->get('title') }}</h3>
                    </div>

                    <div class="photo-options is-inline-block">
                        <div class="dropdown is-right" id="photo-options-{{ $photo->get('id') }}">
                            <div class="dropdown-trigger">
                                <i class="fas fa-ellipsis-v is-pointer" onclick="window.vue.togglePhotoOptions(document.getElementById('photo-options-{{ $photo->get('id') }}'));"></i>
                            </div>
                            <div class="dropdown-menu" role="menu">
                                <div class="dropdown-content">
                                    <a onclick="window.vue.togglePhotoOptions(document.getElementById('photo-options-{{ $photo->get('id') }}'));" href="whatsapp://send?text={{ url('/photo/' . $photo->get('id')) }} - {{ $photo->get('title') }}" class="dropdown-item">
                                        <i class="fab fa-whatsapp"></i>&nbsp;{{ __('app.share_whatsapp') }}
                                    </a>
                                    <a onclick="window.vue.togglePhotoOptions(document.getElementById('photo-options-{{ $photo->get('id') }}'));" href="https://twitter.com/share?url={{ urlencode(url('/photo/' . $photo->get('id'))) }}&text={{ $photo->get('title') }}" class="dropdown-item">
                                        <i class="fab fa-twitter"></i>&nbsp;{{ __('app.share_twitter') }}
                                    </a>
                                    <a onclick="window.vue.togglePhotoOptions(document.getElementById('photo-options-{{ $photo->get('id') }}'));" href="https://www.facebook.com/sharer/sharer.php?u={{ url('/photo/' . $photo->get('id')) }}" class="dropdown-item">
                                        <i class="fab fa-facebook"></i>&nbsp;{{ __('app.share_facebook') }}
                                    </a>
                                    <a onclick="window.vue.togglePhotoOptions(document.getElementById('photo-options-{{ $photo->get('id') }}'));" href="mailto:name@domain.com?body={{ url('/photo/' . $photo->get('id')) }} - {{ $photo->get('title') }}" class="dropdown-item">
                                        <i class="far fa-envelope"></i>&nbsp;{{ __('app.share_email') }}
                                    </a>
                                    <a onclick="window.vue.togglePhotoOptions(document.getElementById('photo-options-{{ $photo->get('id') }}'));" href="sms:000000000?body={{ url('/photo/' . $photo->get('id')) }} - {{ $photo->get('title') }}" class="dropdown-item">
                                        <i class="fas fa-sms"></i>&nbsp;{{ __('app.share_sms') }}
                                    </a>
                                    <a href="javascript:void(0)" onclick="window.vue.copyToClipboard('{{ url('/photo/' . $photo->get('slug')) }} - {{ $photo->get('title') }}'); window.vue.togglePhotoOptions(document.getElementById('photo-options-{{ $photo->get('id') }}'));" class="dropdown-item">
                                        <i class="far fa-copy"></i>&nbsp;{{ __('app.share_clipboard') }}
                                    </a>
                                    <hr class="dropdown-divider"/>
                                    <a href="javascript:void(0)" onclick="window.vue.reportPhoto('{{ $photo->get('id') }}'); window.vue.togglePhotoOptions(document.getElementById('photo-options-{{ $photo->get('id') }}'));" class="dropdown-item">
                                        <i class="far fa-flag"></i>&nbsp;{{ __('app.report_item') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>    
                </div>

                <p>{!! __('app.photo_uploaded_by', ['name' => $photo->get('name'), 'link' => url('/search?text=' . $photo->get('name')), 'date' => $diffForHumans, 'count' => $viewCount]) !!}</p>

                <div>
                    <a href="{{ asset('img/photos/' . $photo->get('photo_full')) }}" target="_blank">
                        <img src="{{ asset('img/photos/' . $photo->get('photo_thumb')) }}" alt="photo"/>
                    </a>
                </div>

                <div class="tags">
                    @foreach ($tags as $tag)
                        @if (strlen($tag) > 0)
                            <div class="tag" onclick="location.href = window.location.origin + '/search?text={{ $tag }}'">{{ $tag }}</div>
                        @endif
                    @endforeach
                </div>
			</div>
		</div>

		<div class="column is-2"></div>
	</div>
</div>
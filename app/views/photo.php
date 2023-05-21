<div class="container">
	<div class="columns">
		<div class="column is-2"></div>

		<div class="column is-8">
			<div class="content-section">
				<h3>{{ $photo->get('title') }}</h3>

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
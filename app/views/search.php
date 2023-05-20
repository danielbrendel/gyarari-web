<div class="container">
	<div class="columns">
		<div class="column is-1"></div>

		<div class="column is-10">
			<div class="content-section">
				<h3>{{ __('app.search_title') }}</h3>

				<div class="field">
					<label class="label">{{ __('app.search_hint') }}</label>
					<p class="control has-icons-left">
						<input class="input" type="text" id="search-input" placeholder="{{ __('app.search_placeholder') }}" value="{{ (isset($_GET['text'])) ? $_GET['text'] : '' }}">
						<span class="icon is-small is-left">
							<i class="fas fa-search"></i>
						</span>
					</p>
				</div>

				<div class="field">
					<div class="control">
						<a class="input button is-link" id="search-submit" onclick="window.recentPhotosPaginate = null; window.vue.queryRecentPhotos('search', document.getElementById('search-input').value);">{{ __('app.search_submit') }}</a>
					</div>
				</div>

				<div id="photos-search"></div>

				<div class="tags">
					@foreach ($tags as $tag)
						@if (strlen($tag->get('name')) > 0)
							<div class="tag" onclick="location.href = window.location.origin + '/search?text={{ $tag->get('name') }}'">{{ $tag->get('name') }}</div>
						@endif
					@endforeach
				</div>
			</div>
		</div>

		<div class="column is-1"></div>
	</div>
</div>
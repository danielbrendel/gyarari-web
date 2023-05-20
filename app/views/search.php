<div class="container">
	<div class="columns">
		<div class="column is-1"></div>

		<div class="column is-10">
			<div class="content-section">
				<h3>{{ __('app.search_title') }}</h3>

				<div class="field">
					<label class="label">{{ __('app.search_hint') }}</label>
					<p class="control has-icons-left">
						<input class="input" type="text" placeholder="{{ __('app.search_placeholder') }}">
						<span class="icon is-small is-left">
							<i class="fas fa-search"></i>
						</span>
					</p>
				</div>

				<div class="field">
					<div class="control">
						<a class="input button is-link">{{ __('app.search_submit') }}</a>
					</div>
				</div>

				<div id="photos-search"></div>

				<div class="tags">
					@foreach ($tags as $tag)
						<div class="tag">{{ $tag->get('name') }}</div>
					@endforeach
				</div>
			</div>
		</div>

		<div class="column is-1"></div>
	</div>
</div>
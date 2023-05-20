<div class="container">
	<div class="columns">
		<div class="column is-1"></div>

		<div class="column is-10">
			<div class="content-section">
				<h3>{{ __('app.recent_title') }}</h3>

				@if (FlashMessage::hasMsg('error'))
				<div class="info info-box-red">
					<div class="info-title info-header-red">{{ __('app.error') }}</div>

					<div class="info-content info-content-red">{!! FlashMessage::getMsg('error') !!}</div>
				</div>
				@endif

				<div id="photos-recent"></div>
			</div>
		</div>

		<div class="column is-1"></div>
	</div>
</div>
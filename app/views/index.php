<div class="container">
	<div class="columns">
		<div class="column is-1"></div>

		<div class="column is-10">
			<div class="content-section">
				<h3>{{ __('app.recent_title') }}</h3>

				@if (FlashMessage::hasMsg('success'))
				<div class="info info-box-green">
					<div class="info-title info-header-green">{{ __('app.success') }}</div>

					<div class="info-content info-content-green">{!! FlashMessage::getMsg('success') !!}</div>
				</div>
				@endif

				@if (FlashMessage::hasMsg('error'))
				<div class="info info-box-red">
					<div class="info-title info-header-red">{{ __('app.error') }}</div>

					<div class="info-content info-content-red">{!! FlashMessage::getMsg('error') !!}</div>
				</div>
				@endif

				@if (FlashMessage::hasMsg('info'))
				<div class="info info-box-blue">
					<div class="info-title info-header-blue">{{ __('app.info') }}</div>

					<div class="info-content info-content-blue">{!! FlashMessage::getMsg('info') !!}</div>
				</div>
				@endif

				<div id="photos-recent"></div>
			</div>
		</div>

		<div class="column is-1"></div>
	</div>
</div>
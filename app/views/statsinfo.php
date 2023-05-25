@if (env('APP_ENABLESTATSINFO'))
<div class="container">
	<div class="columns">
		<div class="column is-2"></div>

		<div class="column is-8">
            <div class="statsinfo">
                <p>{{ __('app.stats_info', ['photo_count' => PhotoModel::getPhotoCount(), 'user_count' => PhotoModel::getUserCount()]) }}</p>
            </div>
        </div>

        <div class="column is-2"></div>
    </div>
</div>
@endif
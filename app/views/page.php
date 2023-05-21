<div class="container">
	<div class="columns">
		<div class="column is-2"></div>

		<div class="column is-8">
			<div class="content-section">
				<h3>{{ $page->get('label') }}</h3>

				{!! $page->get('content') !!}
			</div>
		</div>

		<div class="column is-2"></div>
	</div>
</div>
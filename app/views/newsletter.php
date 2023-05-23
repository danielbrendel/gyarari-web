@if (env('APP_ENABLENEWSLETTER'))
<div class="container">
	<div class="columns">
		<div class="column is-2"></div>

		<div class="column is-8">
            <div class="newsletter">
                <h3>{{ __('app.newsletter_title') }}</h3>

                <p>{{ __('app.newsletter_info') }}</p>

                <form method="POST" action="{{ url('/newsletter/subscribe') }}" id="form-newsletter">
                    @csrf

                    <div class="field has-addons">
                        <p class="control is-full-width">
                            <input class="input" type="email" name="email" placeholder="{{ __('app.newsletter_email') }}" required>
                        </p>
                        <p class="control">
                            <a class="button is-link" href="javascript:void(0);" onclick="document.getElementById('form-newsletter').submit();">{{ __('app.newsletter_add') }}</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>

        <div class="column is-2"></div>
    </div>
</div>
@endif
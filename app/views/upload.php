<div class="container">
	<div class="columns">
		<div class="column is-2"></div>

		<div class="column is-8">
            <div class="content-section">
                <h3>{{ __('app.upload_title') }}</h3>

                <p>{{ __('app.upload_hint') }}</p>

                <form method="POST" action="{{ url('/upload') }}" enctype="multipart/form-data">
                    <div class="field">
                        <p class="control has-icons-left">
                            <input class="input" type="text" name="title" placeholder="{{ __('app.upload_placeholder_title') }}">
                            <span class="icon is-small is-left">
                                <i class="fas fa-bolt"></i>
                            </span>
                        </p>
                    </div>

                    <div class="field">
                        <p class="control has-icons-left">
                            <input class="input" type="text" name="name" placeholder="{{ __('app.upload_placeholder_name') }}">
                            <span class="icon is-small is-left">
                                <i class="fas fa-user"></i>
                            </span>
                        </p>
                    </div>

                    <div class="file has-name">
                        <label class="file-label is-full-width">
                            <input class="file-input" type="file" name="resume">
                            <span class="file-cta">
                                <span class="file-icon">
                                    <i class="fas fa-upload"></i>
                                </span>
                                <span class="file-label">
                                    {{ __('app.upload_choose_file') }}
                                </span>
                            </span>
                            <span class="file-name is-full-width" id="upload-file-name">
                                {{ __('app.upload_no_file_chosen') }}
                            </span>
                        </label>
                    </div>

                    <div class="field">
                        <div class="control">
                            <input type="submit" class="input button is-link" value="{{ __('app.upload_submit') }}">
                        </div>
                    </div>
                </form>
            </div>
		</div>

		<div class="column is-2"></div>
	</div>
</div>


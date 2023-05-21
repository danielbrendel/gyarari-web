<div class="container">
	<div class="columns">
		<div class="column is-2"></div>

		<div class="column is-8">
            <div class="content-section">
                <h3>{{ __('app.upload_title') }}</h3>

                <p>{{ __('app.upload_hint') }}</p>

                <div class="flash-message">
                    @if (FlashMessage::hasMsg('error'))
                    <div class="info info-box-red">
                        <div class="info-title info-header-red">{{ __('app.error') }}</div>

                        <div class="info-content info-content-red">{!! FlashMessage::getMsg('error') !!}</div>
				    </div>
                    @endif
                </div>

                <form method="POST" action="{{ url('/upload') }}" enctype="multipart/form-data" id="form-upload">
                    @csrf

                    <div class="field">
                        <label class="label">{{ __('app.upload_specify_title') }}</label>
                        <p class="control has-icons-left">
                            <input class="input" type="text" name="title" placeholder="{{ __('app.upload_placeholder_title') }}" required>
                            <span class="icon is-small is-left">
                                <i class="fas fa-bolt"></i>
                            </span>
                        </p>
                    </div>

                    <div class="field">
                        <label class="label">{{ __('app.upload_specify_name') }}</label>
                        <p class="control has-icons-left">
                            <input class="input" type="text" name="name" id="upload-name" placeholder="{{ __('app.upload_placeholder_name') }}" required>
                            <span class="icon is-small is-left">
                                <i class="fas fa-user"></i>
                            </span>
                        </p>
                    </div>

                    <div class="field">
                        <label class="label">{{ __('app.upload_specify_email') }}</label>
                        <p class="control has-icons-left">
                            <input class="input" type="email" name="email" id="upload-email" placeholder="{{ __('app.upload_placeholder_email') }}" required>
                            <span class="icon is-small is-left">
                                <i class="fas fa-envelope"></i>
                            </span>
                        </p>
                    </div>

                    <div class="field">
                        <label class="label">{{ __('app.upload_specify_tags') }}</label>
                        <p class="control has-icons-left">
                            <input class="input" type="text" name="tags" placeholder="{{ __('app.upload_placeholder_tags') }}">
                            <span class="icon is-small is-left">
                                <i class="fas fa-hashtag"></i>
                            </span>
                        </p>
                    </div>

                    <div class="field">
                        <label class="label">{{ $captchadata[0] }} + {{ $captchadata[1] }} = ?</label>
                        <p class="control has-icons-left">
                            <input class="input" type="text" name="captcha" placeholder="{{ $captchadata[0] }} + {{ $captchadata[1] }} = ?" required>
                            <span class="icon is-small is-left">
                                <i class="fas fa-shield-alt"></i>
                            </span>
                        </p>
                    </div>

                    <div class="file has-name">
                        <label class="file-label is-full-width">
                            <input class="file-input" type="file" name="photo" onchange="document.getElementById('upload-file-name').innerHTML = this.files[0].name;" required>
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
                            <a class="input button is-link is-margin-top-40" href="javascript:void(0);" onclick="window.vue.performItemUpload('form-upload', 'upload-name', 'upload-email'); this.innerHTML = window.vue.getSpinnerCode();">{{ __('app.upload_submit') }}</a>
                        </div>
                    </div>
                </form>
            </div>
		</div>

		<div class="column is-2"></div>
	</div>
</div>


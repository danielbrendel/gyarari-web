<div class="cookies-outer" id="cookie-notice">
    <div class="cookies-inner">
        <div class="cookies-notice">{{ AppSettingsModel::getCookieNotice() }}</div>
        <div class="cookies-action"><a href="javascript:void(0);" onclick="window.vue.setCookie('cookie-notice', 1); document.getElementById('cookie-notice').style.display = 'none';">{{ __('app.cookie_notice_ok') }}</a></div>
    </div>
</div>
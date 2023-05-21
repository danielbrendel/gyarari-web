<!DOCTYPE html>
<html lang="{{ getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="{{ env('APP_AUTHOR') }}">
        <meta name="description" content="{{ env('APP_DESCRIPTION') }}">
        <meta name="keywords" content="{{ env('APP_KEYWORDS') }}">

        <meta property="og:title" content="{{ env('APP_TITLE') }}">
        <meta property="og:description" content="{{ env('APP_DESCRIPTION') }}">
        <meta property="og:url" content="{{ url($_SERVER['REQUEST_URI']) }}">
        <meta property="og:image" content="{{ PhotoModel::getInitialPhoto() }}">

        <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}"/>

        <link rel="stylesheet" type="text/css" href="{{ asset('css/bulma.css') }}"/>
        
        <title>{{ env('APP_TITLE') }}</title>

        @if (env('APP_DEBUG'))
        <script src="{{ asset('js/vue.js') }}"></script>
        @else
        <script src="{{ asset('js/vue.min.js') }}"></script>
        @endif
        <script src="{{ asset('js/fontawesome.js') }}"></script>
    </head>

    <body>
        <div id="app">
            @include('navbar.php')
            @include('header.php')

            {%content%}

            @include('footer.php')
            @include('scroller.php')
        </div>

        <script src="{{ asset('js/app.js', true) }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                window.vue.initNavbar();

                window.vue.lang.load_more = '{{ __('app.load_more') }}';
                window.vue.lang.no_more_items = '{{ __('app.no_more_items') }}';
                window.vue.lang.photo_by = '{{ __('app.photo_by') }}';
                window.vue.lang.copiedToClipboard = '{{ __('app.copiedToClipboard') }}';

                if (document.getElementById('photos-recent')) {
                    window.recentPhotosPaginate = null;
                    window.vue.queryRecentPhotos();
                } else if (document.getElementById('photos-random')) {
                    window.vue.queryRandomPhotos();
                } else if (document.getElementById('photos-search')) {
                    @if (isset($_GET['text']))
                        document.getElementById('search-submit').click();
                    @endif
                } else if (document.getElementById('form-upload')) {
                    document.getElementById('upload-name').value = window.vue.getCookie('upload-name');
                    document.getElementById('upload-email').value = window.vue.getCookie('upload-email');
                }

                @if (isset($photo))
                if (document.getElementsByClassName('photo-options').length > 0) {
                    window.optionsClickCount = 0;
                    document.getElementsByTagName('html')[0].addEventListener('click', function() {
                        if (document.getElementById('photo-options-{{ $photo->get('id') }}').classList.contains('is-active')) {
                            window.optionsClickCount++;

                            if (window.optionsClickCount >= 2) {
                                window.vue.togglePhotoOptions(document.getElementById('photo-options-{{ $photo->get('id') }}'));
                                window.optionsClickCount = 0;
                            }
                        }
                    });
                }
                @endif
            });
        </script>
    </body>
</html>

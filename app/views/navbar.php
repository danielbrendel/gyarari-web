<nav class="navbar is-black" role="navigation" aria-label="main navigation">
    <div class="navbar-brand">
        <a class="navbar-item navbar-item-brand is-visible-mobile" href="{{ url('/') }}">
            <img src="{{ asset('img/logo.png') }}" alt="Logo"/>&nbsp;{{ env('APP_NAME') }}
        </a>

        <a role="button" class="navbar-burger burger is-top-5" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
        </a>
    </div>

    <div id="navbarBasicExample" class="navbar-menu">
        <div class="navbar-start">
            <a class="navbar-item" href="{{ url('/recent') }}">
                {{ __('app.recent') }}
            </a>

            <a class="navbar-item" href="{{ url('/random') }}">
                {{ __('app.random') }}
            </a>

            <a class="navbar-item" href="{{ url('/search') }}">
                {{ __('app.search') }}
            </a>

            <a class="navbar-item" href="{{ url('/upload') }}">
                {{ __('app.upload') }}
            </a>
        </div>

        <div class="navbar-end">
            @if (env('LINK_WEBSITE'))
            <div class="navbar-item is-inline-block is-navbar-fixed-top">
                <a href="{{ env('LINK_WEBSITE') }}"><i class="fas fa-globe"></i></a>
            </div>
            @endif

            @if (env('LINK_INSTAGRAM'))
            <div class="navbar-item is-inline-block is-navbar-fixed-top">
                <a href="{{ env('LINK_INSTAGRAM') }}"><i class="fab fa-instagram"></i></a>
            </div>
            @endif

            @if (env('LINK_TWITTER'))
            <div class="navbar-item is-inline-block is-navbar-fixed-top">
                <a href="{{ env('LINK_TWITTER') }}"><i class="fab fa-twitter"></i></a>
            </div>
            @endif

            @if (env('LINK_DISCORD'))
            <div class="navbar-item is-inline-block is-navbar-fixed-top">
                <a href="{{ env('LINK_DISCORD') }}"><i class="fab fa-discord"></i></a>
            </div>
            @endif

            @if (env('LINK_TWITCHTV'))
            <div class="navbar-item is-inline-block is-navbar-fixed-top">
                <a href="{{ env('LINK_TWITCHTV') }}"><i class="fab fa-twitch"></i></a>
            </div>
            @endif

            @if (env('LINK_MASTODON'))
            <div class="navbar-item is-inline-block is-navbar-fixed-top">
                <a href="{{ env('LINK_MASTODON') }}"><i class="fab fa-mastodon"></i></a>
            </div>
            @endif
        </div>
    </div>
</nav>
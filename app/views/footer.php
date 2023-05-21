<div class="footer navbar is-fixed-bottom">
    <div class="columns is-full-width">
        <div class="column is-2"></div>
        <div class="column is-8">
            <div class="footer-frame">
                <div class="footer-content">
                    <div>&copy; {{ date('Y') }} by {{ env('APP_AUTHOR') }}</div>

                    <div>
                        @foreach (PageModel::getLinkablePages() as $page)
                            <a href="{{ $page->url }}">{{ $page->label }}</a>&nbsp;&nbsp;
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="column is-2"></div>
    </div>
</div>
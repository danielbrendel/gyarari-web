<h1>You photo has been posted!</h1>

<p>Dear {{ $name }},</p>

<p>
    Your photo has been successfully posted. Thank you for sharing your content
    with our community. Please keep the following information to your records.
</p>

<p>
    Link to photo: <a href="{{ $link }}">{{ $link }}</a>
</p>

<p>
    Removal link: <a href="{{ $removal }}">{{ $removal }}</a>
</p>

@if (env('APP_ENABLEPHOTOAPPROVAL'))
<p>
    Note: Your photo needs to be approved before it will be publicly viewable.
    We will review your photo as soon as possible. However this might take a while.
</p>
@endif

<p>
    Kind Regards,<br/>
    {{ env('APP_NAME') }}
</p>

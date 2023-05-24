<h1>Weekly photo newsletter</h1>

<p>Dear {{ $email }},</p>

<p>
    We are happy to showcase some of the last weeks uploaded photos!
    We hope you have much fun viewing these photos. Have a nice day.
</p>

<div>
    @foreach ($photos as $photo)
        <p>
            <a href="{{ url('/photo/' . $photo->get('id')) }}">
                <img src="{{ asset('img/photos/' . $photo->get('photo_thumb')) }}" alt="Photo"/>
            </a>
        </p>
    @endforeach
</div>

<p>
    Do you want to see more photos? Then click <a href="{{ url('/recent') }}">here</a> to view more content.
</p>

<p>
    If you no longer want to recieve our newsletter, you can unsubscribe
    <a href="{{ url('/newsletter/unsubscribe?token=' . $token) }}">here</a>.
</p>

<p>
    Kind Regards,<br/>
    {{ env('APP_NAME') }}
</p>

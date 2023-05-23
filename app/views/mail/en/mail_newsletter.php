<h1>Weekly photo newsletter</h1>

<p>Dear {{ $email }},</p>

<p>
    We are happy to showcase some of the last weeks uploaded photos!
    We hope you have much fun viewing these photos. Have a nice day.
</p>

<div>
    @foreach ($photos as $photo)
        <div>
            <img src="{{ asset('img/photos/' . $photo->get('photo_thumb')) }}" alt="Photo"/><br/><br/>
        </div>
    @endforeach
</div>

<p>
    If you no longer want to recieve our newsletter, you can unsubscribe
    <a href="{{ url('/newsletter/unsubscribe?token=' . $token) }}">here</a>.
</p>

<p>
    Kind Regards,<br/>
    {{ env('APP_NAME') }}
</p>

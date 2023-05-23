<h1>Wöchendlicher Photo-Newsletter</h1>

<p>Hallo {{ $email }},</p>

<p>
    Wir freuen uns, dir ein paar der Fotos zu zeigen, die im Laufe der
    vergangenen Woche hochgeladen wurden! Wir hoffen, dass Du viel Spaß 
    hast, dir diese Fotos anzusehen. Habe einen schönen Tag.
</p>

<div>
    @foreach ($photos as $photo)
        <div>
            <img src="{{ asset('img/photos/' . $photo->get('photo_thumb')) }}" alt="Foto"/><br/><br/>
        </div>
    @endforeach
</div>

<p>
    Falls Du unseren Newsletter nicht mehr erhalten möchtest, kannst du dich
    <a href="{{ url('/newsletter/unsubscribe?token=' . $token) }}">hier</a>
    abmelden.
</p>

<p>
    Viele Grüße,<br/>
    {{ env('APP_NAME') }}
</p>

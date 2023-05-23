<h1>Dein Foto wurde gepostet</h1>

<p>Hallo {{ $name }},</p>

<p>
    Dein Foto wurde erfolgreich gepostet. Danke für deinen Beitrag
    in unserer Community. Im Folgenden findest Du noch wichtige Infos.
</p>

<p>
    Link zum Foto: <a href="{{ $link }}">{{ $link }}</a>
</p>

<p>
    Link zum Löschen: <a href="{{ $removal }}">{{ $removal }}</a>
</p>

@if (env('APP_ENABLEPHOTOAPPROVAL'))
<p>
    Hinweis: Bevor dein Foto auf unserer Plattform veröffentlicht wird, muss es erst
    von uns bewilligt werden. Wir prüfen dein Foto so schnell wie möglich. Allerdings
    kann dies etwas Zeit in Anspruch nehmen.
</p>
@endif

<p>
    Viele Grüße,<br/>
    {{ env('APP_NAME') }}
</p>

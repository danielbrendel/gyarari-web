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

<p>
    Viele Grüße,<br/>
    {{ env('APP_NAME') }}
</p>

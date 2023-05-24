<!doctype html>
<html lang="{{ getLocale() }}">
    <head>
        <meta charset="utf-8"/>

        <style>
			@import url('https://fonts.googleapis.com/css2?family=Nunito&family=Shadows+Into+Light+Two&family=MedievalSharp&display=swap');

			html, body {
				width: 100%;
				height: 100%;
				margin: 0 auto;
				overflow-y: hidden;
				background-color: rgb(243, 220, 200);
			}
			
			body {
                background-image: url('{{ asset('img/body.jpg') }}');
				background-repeat: no-repeat;
				background-size: cover;
			}
			
			.content {
				width: 100%;
				height: 100%;
				padding: 10px;
				overflow-y: auto;
			}

			h1 {
				font-family: MedievalSharp, Nunito, Verdana, Geneva, Tahoma, sans-serif;
				color: rgb(109, 35, 3);
				text-shadow: -1px -1px 0 rgb(255, 155, 111), 1px -1px 0 rgb(255, 155, 111), -1px 1px 0 rgb(255, 155, 111), 1px 1px 0 rgb(255, 155, 111);
			}
			
			p {
				color: rgb(50, 50, 50);
				font-family: BlinkMacSystemFont, -apple-system, "Segoe UI", "Roboto", "Oxygen", "Ubuntu", "Cantarell", "Fira Sans", "Droid Sans", "Helvetica Neue", "Helvetica", "Arial", sans-serif;
			}
			
			img {
				width: 90%;
				height: 90%;
			}

            @media screen and (min-width: 1180px) {
				img {
					width: 50%;
					height: 50%;
				}
			}
			
			.signature {
				margin-bottom: 50px;
				font-size: 0.8em;
				color: rgb(100, 100, 100);
			}
		</style>
    </head>

    <body>
        <div class="content">
            {%mail%}
        </div>
    </body>
</html>

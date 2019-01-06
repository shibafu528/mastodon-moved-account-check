<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Mastodonおひっこしチェッカー</title>

    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bulma/0.7.2/css/bulma.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script defer src="//use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
    <script defer src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.slim.min.js"></script>
    <script defer src="{{ asset('js/app.js') }}"></script>
</head>
<body>
<nav class="navbar" role="navigation">
    <div class="container">
        <div class="navbar-brand">
            <a href="{{ url('/') }}" class="navbar-item">Mastodonおひっこしチェッカー</a>
            @if (!empty(session('account')))
                <a role="button" class="navbar-burger burger" aria-label="menu" aria-expanded="false" data-target="navbar">
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                </a>
            @endif
        </div>

        @if (!empty(session('account')))
            <div id="navbar" class="navbar-menu">
                <div class="navbar-start">
                    <span class="navbar-item">&commat;{{ session('account')['acct'] }}&commat;{{ session('instance') }}</span>
                </div>
                <div class="navbar-end">
                    <div class="navbar-item">
                        <form action="{{ url('/logout') }}" method="post">
                            {{ csrf_field() }}
                            <button type="submit" class="button">ログアウト</button>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
</nav>
@yield('content')
<footer class="footer">
    <div class="container">
        <p>&copy; 2019 shibafu528</p>
        <p>Developed by <a href="https://ertona.net/@shibafu528" target="_blank" rel="noopener noreferrer">&commat;shibafu528&commat;ertona.net</a></p>
        <p><a href="https://github.com/shibafu528/mastodon-moved-account-check" target="_blank" rel="noopener noreferrer">GitHub</a></p>
    </div>
</footer>
</body>
</html>
@extends('layouts.base')

@section('content')
    <div class="container">
        <section class="section">
            <h1 class="title">これは何？</h1>
            <p>
                Mastodonのフォロワーから、別のインスタンスにお引越しした人をリストアップします。
            </p>
        </section>
        <section class="section">
            <form action="{{ url('/login') }}" method="post">
                {{ csrf_field() }}
                <div class="field">
                    <label class="label">お持ちのアカウントが登録されている、インスタンスのドメインを入力してください。</label>
                    <div class="field has-addons">
                        <div class="control">
                            <a class="button is-static">https://</a>
                        </div>
                        <div class="control is-expanded">
                            <input name="instance" type="text" class="input" placeholder="mastodon.social" value="{{ old('instance') }}">
                        </div>
                    </div>
                </div>
                <div class="field">
                    <button type="submit" class="button is-info"><i class="fab fa-mastodon" style="margin-right: 0.5rem;"></i>Mastodonアカウントでログイン</button>
                </div>
            </form>
            @if (session('status'))
            <div class="notification is-danger" style="margin-top: 1rem;">
                <i class="fas fa-exclamation-triangle" style="margin-right: 0.5rem;"></i>
                {{ session('status') }}
            </div>
            @endif
        </section>
    </div>
@endsection
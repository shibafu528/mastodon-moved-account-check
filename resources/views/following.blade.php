@extends('layouts.base')

@section('content')
    <div class="container">
        <section class="section">
            <h1 class="title">お引越ししたフォロー中ユーザーの一覧</h1>
            @if (empty($followings))
                <p>
                    フォロー中の {{ $followingCount }} アカウントのうち、お引越しした人は誰もいないようです。
                </p>
            @else
                <p style="margin-bottom: 2rem;">
                    フォロー中の {{ $followingCount }} アカウントのうち、{{ count($followings) }} アカウントは別のアカウントにお引越ししたようです。
                </p>
                @foreach ($followings as $account)
                    <div class="account-list-item">
                        <div class="columns">
                            <div class="column account-old">
                                <div class="account-old-inner">
                                    <a class="account-link" href="{{ $account['url'] }}" target="_blank" rel="noopener noreferrer">
                                        <img class="account-avatar" src="{{ $account['avatar'] }}"
                                             alt="&commat;{{ Formatter::expandFullAcct($account) }}">
                                        <div class="account-name">
                                            <p class="account-display-name">{{ $account['display_name'] ?: $account['username'] }}</p>
                                            <p class="account-acct">&commat;{{ Formatter::expandFullAcct($account) }}</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="column account-new">
                                <div class="account-new-inner">
                                    <i class="fas fa-arrow-right" style="margin-right: 0.25rem;"></i>
                                    <a class="account-link" href="{{ $account['moved']['url'] }}" target="_blank" rel="noopener noreferrer">
                                        <img class="account-avatar" src="{{ $account['moved']['avatar'] }}"
                                             alt="&commat;{{ Formatter::expandFullAcct($account['moved']) }}">
                                        <div class="account-name">
                                            <p class="account-display-name">{{ $account['moved']['display_name'] ?: $account['moved']['username'] }}</p>
                                            <p class="account-acct">&commat;{{ Formatter::expandFullAcct($account['moved']) }}</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="column is-hidden-touch"></div>
                        </div>
                    </div>
                @endforeach
            @endif
        </section>
    </div>
@endsection
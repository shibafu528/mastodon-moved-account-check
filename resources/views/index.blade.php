@extends('layouts.base')

@section('content')
    <div class="container">
        <section class="section">
            <h1 class="title">しばらくお待ちください…</h1>
            <p>
                <i class="fas fa-sync fa-spin" style="margin-right: 0.5rem;"></i>フォロー状態を取得しています
            </p>
        </section>
    </div>
    <script>
        window.addEventListener('DOMContentLoaded', function () {
            location.href = '{{ url('/following') }}';
        });
    </script>
@endsection
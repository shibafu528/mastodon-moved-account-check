<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FollowingController extends Controller
{
    public function index(Request $request)
    {
        // ログインチェック
        // TODO: ミドルウェアにするべきかな？
        if (!$request->session()->has('access_token')) {
            return redirect('/')
                ->with('status', 'ログインしてください。')
                ->withInput();
        }

        // フォロイーを取得
        $followingCount = 0;
        $followings = []; // フォロー済判定用、キー = URL
        $movedAccounts = [];
        $client = $this->getClient(session('instance'), session('access_token'));
        $nextUrl = '/api/v1/accounts/' . session('account')['id'] . '/following';
        while (!empty($nextUrl)) {
            $followingResponse = $client->get($nextUrl);
            $followingBody = json_decode($followingResponse->getBody()->getContents(), true);
            foreach ($followingBody as $account) {
                $followingCount++;
                $followings[$account['url']] = true;

                if (!empty($account['moved'])) {
                    $movedAccounts[] = $account;
                }
            }

            // 次のページがあれば続けて取得
            $nextUrl = null;
            $links = \GuzzleHttp\Psr7\parse_header($followingResponse->getHeader('link'));
            foreach ($links as $link) {
                if ($link['rel'] === 'next') {
                    $nextUrl = trim($link[0], '<>');
                }
            }
        }

        return view('following')->with(compact('followingCount', 'followings', 'movedAccounts'));
    }

    private function getClient(string $instance, string $accessToken)
    {
        return new \GuzzleHttp\Client([
            'base_uri' => 'https://' . $instance,
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken
            ]
        ]);
    }
}

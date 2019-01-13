<?php

namespace App\Http\Controllers;

use App\MastodonApp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MastodonLoginController extends Controller
{
    public function index(Request $request)
    {
        if ($request->session()->has('access_token')) {
            return view('index');
        } else {
            return view('guest');
        }
    }

    public function login(Request $request)
    {
        $inputs = $request->all();
        $validator = Validator::make($inputs, [
            'instance' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect('/')
                ->with('status', 'インスタンスドメインを入力してください。')
                ->withInput();
        }

        // アプリ登録情報の取得
        $app = MastodonApp::where('host', $inputs['instance'])->first();
        if ($app === null) {
            // /api/v1/apps でアプリ登録
            $client = new \GuzzleHttp\Client([
                'base_uri' => 'https://' . $inputs['instance']
            ]);
            $redirectUri = url('/login/callback');
            $registerResponse = $client->post('/api/v1/apps', [
                'form_params' => [
                    'client_name' => config('app.name'),
                    'redirect_uris' => $redirectUri,
                    'scopes' => 'read follow',
                    'website' => url('/')
                ]
            ]);
            $registerBody = json_decode($registerResponse->getBody()->getContents(), true);

            // Client ID/SecretをDBに保存
            $app = MastodonApp::create([
                'host' => $inputs['instance'],
                'client_id' => $registerBody['client_id'],
                'client_secret' => $registerBody['client_secret'],
                'redirect_uri' => $redirectUri
            ]);
        }

        // Instance/CID/Secretはセッションに保存
        $request->session()->regenerate();
        session([
            'instance' => $app->host,
            'client_id' => $app->client_id,
            'client_secret' => $app->client_secret
        ]);

        // OAuthページにリダイレクト
        $query = http_build_query([
            'response_type' => 'code',
            'client_id' => $app->client_id,
            'scope' => 'read follow',
            'redirect_uri' => $app->redirect_uri
        ]);
        return redirect('https://' . $app->host . '/oauth/authorize?' . $query);
    }

    public function callback(Request $request)
    {
        // 認証コードが返ってきているかチェック
        $authorizationCode = $request->input('code');
        if (empty($authorizationCode)) {
            // キャンセルしたか、何か他のエラー
            return redirect('/')
                ->with('status', 'ログイン処理が上手くいきませんでした。もうしわけねえもうしわけねえ。ログイン画面で許可押したのにこのメッセージが出ていたら、管理者に上手くいかなかったドメインとかを伝えるとマシになるかもしれません。')
                ->withInput();
        }

        // セッションからInstance/CID/Secret取得
        $instance = session('instance');
        $clientId = session('client_id');
        $clientSecret = session('client_secret');
        if (empty($instance) || empty($clientId) || empty(($clientSecret))) {
            return redirect('/')
                ->with('status', 'ログイン処理が上手くいきませんでした。もう一度やり直してください。')
                ->withInput();
        }

        // /oauth/token でアクセストークン取得
        $client = new \GuzzleHttp\Client([
            'base_uri' => 'https://' . $instance
        ]);
        $tokenResponse = $client->post('/oauth/token', [
            'form_params' => [
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'grant_type' => 'authorization_code',
                'redirect_uri' => url('/login/callback'),
                'code' => $authorizationCode
            ]
        ]);
        $tokenBody = json_decode($tokenResponse->getBody()->getContents(), true);
        $accessToken = $tokenBody['access_token'];

        // アクセストークンはセッションに保存
        session(['access_token' => $accessToken]);

        // ユーザ情報を取得してセッションに保存
        $credentialsResponse = $client->get('/api/v1/accounts/verify_credentials', [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken
            ]
        ]);
        $credentialsBody = json_decode($credentialsResponse->getBody()->getContents(), true);
        session(['account' => $credentialsBody]);

        // ログイン済トップページにリダイレクト
        return redirect('/');
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect('/');
    }
}

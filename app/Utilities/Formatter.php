<?php

namespace App\Utilities;

class Formatter
{
    /**
     * AccountのフルAcctを取得します。
     * @param array $account Account情報
     * @return string "username@domain" 形式の文字列
     */
    public function expandFullAcct($account) : string
    {
        return $account['username'] . '@' . parse_url($account['url'], PHP_URL_HOST);
    }
}
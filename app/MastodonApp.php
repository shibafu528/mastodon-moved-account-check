<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MastodonApp extends Model
{
    protected $fillable = ['host', 'client_id', 'client_secret', 'redirect_uri'];
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMastodonAppsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mastodon_apps', function (Blueprint $table) {
            $table->increments('id');
            $table->text('host')->unique();
            $table->text('client_id');
            $table->text('client_secret');
            $table->text('redirect_uri');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mastodon_apps');
    }
}

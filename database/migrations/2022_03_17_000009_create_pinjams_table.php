<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePinjamsTable extends Migration
{
    public function up()
    {
        Schema::create('pinjams', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->datetime('time_start');
            $table->datetime('time_end');
            $table->datetime('time_return')->nullable();
            $table->string('no_hp');
            $table->longText('penggunaan');
            $table->string('unit_pengguna')->nullable();
            $table->string('status')->nullable();
            $table->string('status_text')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}

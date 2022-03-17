<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToPinjamsTable extends Migration
{
    public function up()
    {
        Schema::table('pinjams', function (Blueprint $table) {
            $table->unsignedBigInteger('ruang_id')->nullable();
            $table->foreign('ruang_id', 'ruang_fk_6052120')->references('id')->on('ruangs');
            $table->unsignedBigInteger('borrowed_by_id')->nullable();
            $table->foreign('borrowed_by_id', 'borrowed_by_fk_6052128')->references('id')->on('users');
            $table->unsignedBigInteger('processed_by_id')->nullable();
            $table->foreign('processed_by_id', 'processed_by_fk_6052129')->references('id')->on('users');
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->foreign('created_by_id', 'created_by_fk_6052134')->references('id')->on('users');
        });
    }
}

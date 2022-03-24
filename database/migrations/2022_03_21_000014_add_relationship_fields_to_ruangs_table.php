<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToRuangsTable extends Migration
{
    public function up()
    {
        Schema::table('ruangs', function (Blueprint $table) {
            $table->unsignedBigInteger('lantai_id')->nullable();
            $table->foreign('lantai_id', 'lantai_fk_6052099')->references('id')->on('lantais');
        });
    }
}

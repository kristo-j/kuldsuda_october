<?php namespace Kuldsuda\Kuldsuda\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateAcknowledgedusersTable extends Migration
{
    public function up()
    {
        Schema::create('kuldsuda_kuldsuda_acknowledgedusers', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('reason')->nullable();
            $table->string('email')->nullable();
            $table->string('picture_type')->nullable();
            $table->string('sent_type')->nullable();
            $table->string('name')->nullable();
            $table->string('acknowledged_name')->nullable();
            $table->string('picture_location')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kuldsuda_kuldsuda_acknowledgedusers');
    }
}

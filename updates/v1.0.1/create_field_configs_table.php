<?php

use Winter\Storm\Database\Schema\Blueprint;
use Winter\Storm\Database\Updates\Migration;
use Winter\Storm\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wpjscc_popupform_field_configs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mode', 20)->unique();
            $table->string('description')->nullable();
            $table->text('permissions')->nullable();
            $table->text('config_form')->nullable();
            $table->text('config_relation')->nullable();
            $table->text('config_list')->nullable();
            $table->text('fields')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('wpjscc_popupform_field_configs');
    }
};

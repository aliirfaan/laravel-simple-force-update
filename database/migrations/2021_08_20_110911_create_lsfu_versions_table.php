<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLsfuVersionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lsfu_versions', function (Blueprint $table) {
            $table->id();
            $table->string('app_name')->index('app_name_index');
            $table->string('platform')->default('default')->index('platform_index');
            $table->string('min_ver');
            $table->string('max_ver');
            $table->string('update_url')->nullable();
            $table->text('update_available_msg')->nullable();
            $table->text('update_required_msg')->nullable();
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
        Schema::dropIfExists('lsfu_versions');
    }
}

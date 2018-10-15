<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report', function (Blueprint $table) {
            $table->string('id', 36);
            $table->string('name', 100);
            $table->string('report_category_id', 36);
            $table->tinyInteger('status');
            $table->timestamps();
            $table->string('pdf', 200);
            $table->string('image_thumb', 200);
            $table->string('company_id', 36);
            $table->string('created_by', 100);
            $table->string('updated_by', 100);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('report');
    }
}

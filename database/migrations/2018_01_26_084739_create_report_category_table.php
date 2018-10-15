<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_category', function (Blueprint $table) {
            $table->string('id', 36);
            $table->string('name', 100);
            $table->timestamps();
            $table->softDeletes();
            $table->string('company_id', 36);
            $table->string('created_by', 100);
            $table->string('updated_by', 100);
            $table->string('deleted_by', 100);
            $table->string('slug', 100);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('report_category');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImportProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer("amount");
            $table->double("export_price");
            $table->double("import_price");
            $table->bigInteger("product_id")->unsigned();//
            $table->foreign("product_id")->references("id")->on("products")->onDelete("cascade");
            $table->bigInteger("import_id")->unsigned();//
            $table->foreign("import_id")->references("id")->on("imports")->onDelete("cascade");
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
        Schema::dropIfExists('import_products');
    }
}

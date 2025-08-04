<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Название деятельности');
            $table->unsignedBigInteger('parent_id')->nullable()->comment('ID родительской деятельности');
            $table->integer('level')->default(1)->comment('Уровень вложенности (1-3)');
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('activities')->onDelete('cascade');
            $table->index('parent_id');
            $table->index('level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};

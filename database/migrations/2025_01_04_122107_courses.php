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
        Schema::create('courses', function (Blueprint $table) {

        $table->id();
        $table->string('name');
        $table->string('contant');
        $table->text('description');
        $table->decimal('price', 10, 2);
        $table->time('time');
        $table->string('image')->nullable();
        $table->unsignedBigInteger('teach_id')->nullable();
        $table->unsignedBigInteger('get_id')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');

    }
};

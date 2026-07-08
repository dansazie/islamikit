<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable()->index();
            $table->string('label');
            $table->json('labels')->nullable();
            $table->string('route')->nullable();
            $table->string('icon')->default('Layers');
            $table->string('type')->default('link');
            $table->integer('order')->default(0);
            $table->json('roles')->nullable();
            $table->timestamps();
        });
    }
};
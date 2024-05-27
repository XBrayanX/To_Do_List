<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create( 'todolist', function ( Blueprint $table ) {
            $table->id();
            $table->string( 'name', 150 );
            $table->date( 'deadline' );
            $table->enum( 'complete', ['si', 'no'] )->default( 'no' );
            $table->timestamp( 'created_at' )->useCurrent();
            $table->timestamp( 'updated_at' )->nullable()->useCurrentOnUpdate();
        } );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists( 'todolist' );
    }
};

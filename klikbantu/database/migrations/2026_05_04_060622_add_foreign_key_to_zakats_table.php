<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Cek apakah foreign key sudah ada
        $foreignKeys = Schema::getConnection()
            ->getDoctrineSchemaManager()
            ->listTableForeignKeys('zakats');
        
        $foreignKeyExists = false;
        foreach ($foreignKeys as $foreignKey) {
            if ($foreignKey->getLocalColumns() == ['id_user']) {
                $foreignKeyExists = true;
                break;
            }
        }
        
        if (!$foreignKeyExists) {
            Schema::table('zakats', function (Blueprint $table) {
                $table->foreign('id_user')
                      ->references('id')
                      ->on('users')
                      ->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        Schema::table('zakats', function (Blueprint $table) {
            $table->dropForeign(['id_user']);
        });
    }
};
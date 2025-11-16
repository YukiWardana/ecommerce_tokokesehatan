<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // For MySQL, we need to modify the enum
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'customer', 'seller', 'visitor') DEFAULT 'customer'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'customer', 'visitor') DEFAULT 'customer'");
    }
};

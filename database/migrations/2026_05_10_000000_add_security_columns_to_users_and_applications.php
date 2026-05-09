<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_admin')->default(false)->after('email_verified_at');
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->string('document_upload_token')->nullable()->index()->after('reference_number');
        });

        $adminEmail = env('ADMIN_EMAIL', 'btechadmissionoffice@gmail.com');
        if ($adminEmail) {
            DB::table('users')->where('email', $adminEmail)->update(['is_admin' => true]);
        }
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn('document_upload_token');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_admin');
        });
    }
};

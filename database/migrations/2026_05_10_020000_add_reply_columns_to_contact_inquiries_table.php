<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('contact_inquiries')) {
            return;
        }

        Schema::table('contact_inquiries', function (Blueprint $table) {
            if (! Schema::hasColumn('contact_inquiries', 'reply_message')) {
                $table->text('reply_message')->nullable();
            }

            if (! Schema::hasColumn('contact_inquiries', 'replied_at')) {
                $table->timestamp('replied_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('contact_inquiries')) {
            return;
        }

        Schema::table('contact_inquiries', function (Blueprint $table) {
            if (Schema::hasColumn('contact_inquiries', 'reply_message')) {
                $table->dropColumn('reply_message');
            }

            if (Schema::hasColumn('contact_inquiries', 'replied_at')) {
                $table->dropColumn('replied_at');
            }
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('contact_inquiries')) {
            Schema::create('contact_inquiries', function (Blueprint $table) {
                $table->id();
                $table->string('first_name');
                $table->string('last_name');
                $table->string('email');
                $table->string('subject');
                $table->text('message');
                $table->string('status')->default('pending');
                $table->timestamps();
            });

            return;
        }

        Schema::table('contact_inquiries', function (Blueprint $table) {
            if (! Schema::hasColumn('contact_inquiries', 'first_name')) {
                $table->string('first_name')->nullable();
            }

            if (! Schema::hasColumn('contact_inquiries', 'last_name')) {
                $table->string('last_name')->nullable();
            }

            if (! Schema::hasColumn('contact_inquiries', 'email')) {
                $table->string('email')->nullable();
            }

            if (! Schema::hasColumn('contact_inquiries', 'subject')) {
                $table->string('subject')->nullable();
            }

            if (! Schema::hasColumn('contact_inquiries', 'message')) {
                $table->text('message')->nullable();
            }

            if (! Schema::hasColumn('contact_inquiries', 'status')) {
                $table->string('status')->default('pending');
            }

            if (! Schema::hasColumn('contact_inquiries', 'created_at')) {
                $table->timestamp('created_at')->nullable();
            }

            if (! Schema::hasColumn('contact_inquiries', 'updated_at')) {
                $table->timestamp('updated_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        // Intentionally left in place. This migration only repairs missing
        // columns in production databases and should not remove live data.
    }
};

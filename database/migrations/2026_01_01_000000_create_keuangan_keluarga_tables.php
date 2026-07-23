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
        Schema::create('families', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('nama');
            $table->integer('cutoff_day')->default(1);
            $table->timestamps();
        });

        Schema::create('members', function (Blueprint $table) {
            $table->string('area_key')->primary();
            $table->string('nama');
            $table->string('tipe'); // suami, istri, anak, lainnya, keluarga
            $table->string('email')->nullable();
            $table->string('family_id');
            $table->integer('urutan')->default(99);
            $table->timestamps();
        });

        Schema::create('allowed_emails', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('nama')->nullable();
            $table->string('role')->default('member'); // superadmin, leader, member
            $table->string('family_id')->nullable();
            $table->boolean('is_platform_admin')->default(false);
            $table->string('status')->default('active'); // active, pending
            $table->text('foto_url')->nullable();
            $table->string('tema')->default('pink');
            $table->string('password')->nullable();
            $table->timestamps();
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('area');
            $table->string('jenis'); // masuk, keluar
            $table->string('nama');
            $table->integer('urutan')->default(99);
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });

        Schema::create('transactions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('area');
            $table->string('category_id');
            $table->date('tanggal');
            $table->decimal('nominal', 15, 2);
            $table->text('catatan')->nullable();
            $table->string('jenis'); // masuk, keluar
            $table->string('created_by')->nullable();
            $table->timestamps();
        });

        Schema::create('transfers', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('ke_area');
            $table->decimal('nominal', 15, 2);
            $table->date('tanggal');
            $table->text('catatan')->nullable();
            $table->string('created_by')->nullable();
            $table->timestamps();
        });

        Schema::create('access_grants', function (Blueprint $table) {
            $table->id();
            $table->string('area_key');
            $table->string('viewer_email');
            $table->unique(['area_key', 'viewer_email']);
            $table->timestamps();
        });

        Schema::create('jatah_bulanan', function (Blueprint $table) {
            $table->id();
            $table->string('area')->unique();
            $table->decimal('nominal', 15, 2);
            $table->timestamps();
        });

        Schema::create('saldo_awal', function (Blueprint $table) {
            $table->id();
            $table->string('area')->unique();
            $table->decimal('nominal', 15, 2);
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });

        Schema::create('saldo_rekening', function (Blueprint $table) {
            $table->id();
            $table->string('area');
            $table->decimal('nominal', 15, 2);
            $table->date('tanggal');
            $table->timestamps();
        });

        Schema::create('saldo_log', function (Blueprint $table) {
            $table->id();
            $table->string('area');
            $table->string('jenis'); // awal, edit
            $table->decimal('dari', 15, 2);
            $table->decimal('jadi', 15, 2);
            $table->text('alasan')->nullable();
            $table->string('oleh')->nullable();
            $table->timestamp('waktu')->nullable();
            $table->timestamps();
        });

        Schema::create('tempat', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('area');
            $table->string('kat'); // bank, ewallet, cash, piutang
            $table->string('brand')->nullable();
            $table->string('nama')->nullable();
            $table->string('orang')->nullable();
            $table->string('tanggal')->nullable();
            $table->decimal('saldo', 15, 2)->default(0);
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });

        Schema::create('tx_tempat', function (Blueprint $table) {
            $table->string('tx_id')->primary();
            $table->string('tempat_id');
            $table->string('area')->nullable();
            $table->string('jenis');
            $table->decimal('nominal', 15, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('transfer_tempat', function (Blueprint $table) {
            $table->string('transfer_id')->primary();
            $table->string('dari')->nullable();
            $table->string('ke')->nullable();
            $table->decimal('nominal', 15, 2)->default(0);
            $table->string('family_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfer_tempat');
        Schema::dropIfExists('tx_tempat');
        Schema::dropIfExists('tempat');
        Schema::dropIfExists('saldo_log');
        Schema::dropIfExists('saldo_rekening');
        Schema::dropIfExists('saldo_awal');
        Schema::dropIfExists('jatah_bulanan');
        Schema::dropIfExists('access_grants');
        Schema::dropIfExists('transfers');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('allowed_emails');
        Schema::dropIfExists('members');
        Schema::dropIfExists('families');
    }
};

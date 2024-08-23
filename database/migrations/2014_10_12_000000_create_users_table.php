<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('email', 100)->nullable();
            $table->string('mobile_no', 20)->unique();
            $table->string('whatsapp_no', 20)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            // $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->enum('gender', ['male', 'female', 'trans', 'other', 'not specified'])->default('not specified')->comment('male, female, trans, other, not specified');
            $table->string('referral_code', 10);
            $table->string('referred_by')->default('');
            $table->tinyInteger('status')->comment('1: active, 0: inactive')->default(1);
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });

        $data = [
            'first_name' => 'Suvajit',
            'last_name' => 'Bardhan',
            'email' => 'bardhansuvajit@email.com',
            'mobile_no' => '9038775709',
            'password' => Hash::make('9038775709'),
            'referral_code' => 'mDy2u',
            'referred_by' => '0',
        ];

        DB::table('users')->insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}

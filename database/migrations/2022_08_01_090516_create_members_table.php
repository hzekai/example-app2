<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('account')->nullable()->default(null);
            $table->string('nick_name')->nullable()->default(null);
			$table->string('phone')->nullable()->default(null);
			$table->string('email')->nullable()->default(null);
			$table->Integer('level')->default(0);
			$table->string('lange')->nullable()->default(null);
			// $table->string('name')->nullable()->default(null);
			$table->string('bank_name')->nullable()->default(null);
			$table->string('bank_account')->nullable()->default(null);
			$table->Integer('status')->default(1)->comment("['normal'=>0, 'forbidden'=>1, 'exit'=>2]");;
			$table->Integer('point')->nullable()->default(null);

            $table->string('f_name')->nullable()->default(null);
            $table->string('l_name')->nullable()->default(null);
            $table->string('password');
            $table->tinyInteger('is_phone_verified')->default(0);
            $table->tinyInteger('is_email_verified')->default(0);
            $table->timestamp('last_active_at')->nullable()->default(null);
            $table->tinyInteger('two_factor')->nullable()->default(0);
            $table->tinyInteger('fcm_token')->nullable()->default(0);
            $table->string('eth_address')->nullable()->default(null);
            $table->string('eth_transfer_key')->nullable()->default(null);
            $table->string('trx_address')->nullable()->default(null);
            $table->string('trx_transfer_key')->nullable()->default(null);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('members');
    }
}

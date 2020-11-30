
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAllegroAccountsTable extends Migration
{
    public function up()
    {
        Schema::create(config('allegro.database.tables.allegro_accounts'), function (Blueprint $table) {
            $table->id();
            $table->string('username')->index()->nullable();
            $table->string('seller_id')->index()->nullable();
            $table->text('access_token')->nullable();
            $table->text('refresh_token')->nullable();
            $table->timestamps();
        });

        Schema::table(config('allegro.database.tables.allegro_accounts'), function (Blueprint $table) {
            $table->foreignId('user_id')
                ->constrained(config('allegro.database.tables.users'))
                ->onDelete('cascade');
        });

        Schema::table(config('allegro.database.tables.allegro_accounts'), function (Blueprint $table) {
            $table->foreignId('application_id')
                ->nullable()
                ->constrained(config('allegro.database.tables.allegro_applications'))
                ->onDelete('cascade');
        });

    }

    public function down()
    {
        Schema::table(config('allegro.database.tables.allegro_accounts'), function (Blueprint $table) {
            $table->dropForeign(['application_id']);
            $table->dropForeign(['user_id']);
        });

        Schema::drop(config('allegro.database.tables.allegro_accounts'));
    }
}

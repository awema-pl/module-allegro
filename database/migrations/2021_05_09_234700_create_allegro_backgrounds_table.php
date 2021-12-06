
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAllegroBackgroundsTable extends Migration
{
    public function up()
    {
        Schema::create(config('allegro.database.tables.allegro_backgrounds'), function (Blueprint $table) {
            $table->id();
            $table->string('offer_id')->index();
            $table->string('offer_name');
            $table->text('photo_path_before')->nullable();
            $table->text('photo_path_after')->nullable();
            $table->dateTime('sent_allegro_at')->index()->nullable();
            $table->timestamps();
        });

        Schema::table(config('allegro.database.tables.allegro_backgrounds'), function (Blueprint $table) {
            $table->foreignId('user_id')
                ->constrained(config('baselinker.database.tables.users'))
                ->onDelete('cascade');
        });

        Schema::table(config('allegro.database.tables.allegro_backgrounds'), function (Blueprint $table) {
            $table->foreignId('account_id')
                ->constrained(config('allegro-client.database.tables.allegro_client_accounts'))
                ->onDelete('cascade');
        });

        Schema::table(config('allegro.database.tables.allegro_backgrounds'), function (Blueprint $table) {
            $table->unique(['account_id'], 'TRT830H0AUNS63KE4NFT3P');
        });
    }

    public function down()
    {
        Schema::table(config('allegro.database.tables.allegro_backgrounds'), function (Blueprint $table) {
            $table->dropUnique(['account_id']);
        });

        Schema::table(config('allegro.database.tables.allegro_backgrounds'), function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['account_id']);
        });

        Schema::drop(config('allegro.database.tables.allegro_backgrounds'));
    }
}

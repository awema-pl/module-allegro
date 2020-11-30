
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAllegroApplicationsTable extends Migration
{
    public function up()
    {
        Schema::create(config('allegro.database.tables.allegro_applications'), function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('client_id');
            $table->text('client_secret');
            $table->boolean('sandbox')->default(false);
            $table->timestamps();
        });

        Schema::table(config('allegro.database.tables.allegro_applications'), function (Blueprint $table) {
            $table->foreignId('user_id')
                ->constrained(config('allegro.database.tables.users'))
                ->onDelete('cascade');
        });

    }

    public function down()
    {
        Schema::table(config('allegro.database.tables.allegro_applications'), function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::drop(config('allegro.database.tables.allegro_applications'));
    }
}

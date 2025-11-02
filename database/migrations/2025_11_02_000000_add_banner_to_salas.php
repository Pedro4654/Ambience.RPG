<?php
// database/migrations/2025_11_02_000000_add_banner_to_salas.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBannerToSalas extends Migration
{
    public function up()
    {
        Schema::table('salas', function (Blueprint $table) {
            $table->string('banner_url', 255)->nullable()->after('imagem_url');
            $table->string('banner_color', 7)->nullable()->after('banner_url'); // ex: #ff8800
        });
    }

    public function down()
    {
        Schema::table('salas', function (Blueprint $table) {
            $table->dropColumn(['banner_url','banner_color']);
        });
    }
}

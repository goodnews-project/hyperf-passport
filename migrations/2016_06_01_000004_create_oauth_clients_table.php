<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;
use function Hyperf\Config\config;

class CreateOauthClientsTable extends Migration {

    /**
     * The database schema.
     *
     * @var \Hyperf\Database\Schema\Builder
     */
    protected $schema;

    /**
     * Create a new migration instance.
     *
     * @return void
     */
    public function __construct() {
        $this->schema = (new Schema())->connection($this->getConnection())->getSchemaBuilder();
    }

    /**
     * Get the migration connection name.
     *
     * @return string|null
     */
    public function getConnection() {
        return config('passport.storage.database.connection');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        $this->schema->create('oauth_clients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('name');
            $table->string('secret', 100)->nullable();
            $table->string('provider')->nullable();
            $table->text('redirect');
            $table->boolean('personal_access_client');
            $table->boolean('password_client');
            $table->boolean('revoked');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        $this->schema->dropIfExists('oauth_clients');
    }

}

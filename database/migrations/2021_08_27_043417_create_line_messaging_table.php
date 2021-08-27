<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateLineMessagingTable.
 */
class CreateLineMessagingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('line_events', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('destination')->comment('User ID of a bot that should receive webhook events.');
            $table->string('type')->comment('Identifier for the type of event.');
            $table->json('response')->comment('Returns the body that is decoded in JSON. This body is an array.');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('line_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('user_id')->comment('User ID.');
            $table->string('display_name')->nullable()->comment('User\'s display name.');
            $table->string('language')->nullable()->comment('User\'s language, as a BCP 47 language tag.');
            $table->string('picture_url')->nullable()->comment('Profile image URL.');
            $table->string('status_message')->nullable()->comment('User\'s status message.');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('line_events');
        Schema::dropIfExists('line_users');
    }
}

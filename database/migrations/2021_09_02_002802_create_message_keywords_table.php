<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateMessageKeywordsTable.
 */
class CreateMessageKeywordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('message_keywords', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('content')->nullable()->comment('Text 內容');
            $table->json('keywords')->comment('觸發關鍵字');
            $table->json('response')->comment('回覆內容');
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
        Schema::dropIfExists('message_keywords');
    }
}

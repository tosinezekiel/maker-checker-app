<?php

use App\Constants\Type;
use App\Constants\Status;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentsTable extends Migration
{
    const TABLE = 'users';
    const COLUMN = 'id';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->json('data')->nullable();
            $table->foreignId('author_id')->constrained(self::TABLE, self::COLUMN);
            $table->foreignId('approver_id')->nullable()->constrained(self::TABLE, self::COLUMN);
            $table->enum('type', Type::ALL);
            $table->enum('status', Status::ALL);
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
        Schema::dropIfExists('documents');
    }
}

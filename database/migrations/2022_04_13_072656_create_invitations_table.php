<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class CreateInvitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invitations', function (Blueprint $table) {
            // for relational tables
            $table->engine = "InnoDB";

            $table->bigIncrements('id');
            $table->string('invitation_code');
            $table->unsignedBigInteger('created_user');
            // Subscription due date 1 month from now. Could be change
            $table->datetime('expire_date')->default(Carbon::now()->addMonth()->format('Y-m-d H:i:s'));
            $table->timestamps();

            // Relation to user table. Invitation codes will be deleted when created user deleted.
            $table->foreign('created_user')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invitations');
    }
}

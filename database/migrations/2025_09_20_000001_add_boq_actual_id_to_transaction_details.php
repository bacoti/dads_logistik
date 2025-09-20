<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBoqActualIdToTransactionDetails extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('transaction_details', function (Blueprint $table) {
            $table->unsignedBigInteger('boq_actual_id')->nullable()->after('transaction_id');
            $table->foreign('boq_actual_id')->references('id')->on('boq_actuals')->onDelete('set null');
        });

        // Add optional posted_quantity to track how much from BOQ has been posted
        Schema::table('boq_actuals', function (Blueprint $table) {
            if (!Schema::hasColumn('boq_actuals', 'posted_quantity')) {
                $table->decimal('posted_quantity', 16, 4)->default(0)->after('actual_quantity');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('transaction_details', function (Blueprint $table) {
            if (Schema::hasColumn('transaction_details', 'boq_actual_id')) {
                $table->dropForeign(['boq_actual_id']);
                $table->dropColumn('boq_actual_id');
            }
        });

        Schema::table('boq_actuals', function (Blueprint $table) {
            if (Schema::hasColumn('boq_actuals', 'posted_quantity')) {
                $table->dropColumn('posted_quantity');
            }
        });
    }
}

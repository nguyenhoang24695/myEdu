<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeToNewUploadLectureStyle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $lectures = \App\Models\Lecture::all();
        foreach($lectures as $lecture){
            /** @var \App\Models\Lecture $lecture */
            if($lecture->primary_data_type == config('course.lecture_types.document')){
                echo $lecture->id . " :: " . $lecture->get_title() . $lecture->primary_data_type . "\n";

                /** @var Document $document */
                $document = $lecture->getPrimaryData();

                if($document){
                    var_dump($lecture->setSecondaryData($document, true));
                }
                try{
                    $lecture->removePrimaryData(true);
                }catch (\Exception $ex){
                    echo $ex->getMessage() . "\n";
                }

            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_contents', function (Blueprint $table) {
            //
        });
    }
}

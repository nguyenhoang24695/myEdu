<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 4/15/16
 * Time: 17:00
 */

namespace App\Models\Traits;


use App\Models\Lecture;
use Illuminate\Database\Eloquent\Builder;

trait MediaOfLecture {
	private function updateLectureUsingAsPrimary($values, Array $exclude = []){
		return Lecture::where(function(Builder $query) use ($exclude){
			$query->whereNotIn('id', $exclude);
			$query->where('primary_data_type', config('course.lecture_types.video'));
			$query->where('primary_data_id', $this->id);
		})->update($values);
	}
	private function updateLectureUsingAsSecondary($values, Array $exclude = []){
		return Lecture::where(function(Builder $query) use ($exclude){
			$query->whereNotIn('id', $exclude);
			$query->where('secondary_data_type', config('course.lecture_types.video'));
			$query->where('secondary_data_id', $this->id);
		})->update($values);
	}
	public function updateDataLengthForLecture($new_length){
		$value1 = ['primary_data_length' => $new_length];
		$value2 = ['secondary_data_length' => $new_length];
		$update1 = $this->updateLectureUsingAsPrimary($value1);
		$update2 = $this->updateLectureUsingAsSecondary($value2);
		return $update1 || $update2;
	}
}
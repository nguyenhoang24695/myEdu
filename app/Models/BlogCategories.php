<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\BlogCategories
 *
 * @property integer $id
 * @property string $blc_title
 * @property boolean $blc_order
 * @property integer $blc_parent_id
 * @property boolean $blc_has_child
 * @property boolean $blc_active
 * @property boolean $blc_delete
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\BlogCategories whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\BlogCategories whereBlcTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\BlogCategories whereBlcOrder($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\BlogCategories whereBlcParentId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\BlogCategories whereBlcHasChild($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\BlogCategories whereBlcActive($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\BlogCategories whereBlcDelete($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\BlogCategories whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\BlogCategories whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\BlogCategories whereDeletedAt($value)
 * @mixin \Eloquent
 */
class BlogCategories extends Model
{
	 use SoftDeletes;

    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table  = 'blog_categories';

	/**
	 * The attributes that are not mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = ['id'];

	/**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
}

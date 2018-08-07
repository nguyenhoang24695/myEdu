<?php

namespace App\Models;

use Baum\Node;
use Doctrine\DBAL\Query\QueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Core\MyStorage;

/**
 * App\Models\Category
 *
 * @property integer $id
 * @property string $cat_title
 * @property boolean $cat_order
 * @property integer $cat_parent_id
 * @property boolean $cat_has_child
 * @property boolean $cat_active
 * @property boolean $cat_delete
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @property integer $parent_id
 * @property integer $lft
 * @property integer $rgt
 * @property integer $depth
 * @property integer $course_count
 * @property boolean $hot
 * @property string $disk
 * @property string $avata_path
 * @property-read \App\Models\Category $parent
 * @property-read \Baum\Extensions\Eloquent\Collection|\App\Models\Category[] $children
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Category whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Category whereCatTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Category whereCatOrder($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Category whereCatParentId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Category whereCatHasChild($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Category whereCatActive($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Category whereCatDelete($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Category whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Category whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Category whereParentId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Category whereLft($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Category whereRgt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Category whereDepth($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Category whereCourseCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Category whereHot($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Category whereDisk($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Category whereAvataPath($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Category searching($filters)
 * @method static \Illuminate\Database\Query\Builder|\Baum\Node withoutNode($node)
 * @method static \Illuminate\Database\Query\Builder|\Baum\Node withoutSelf()
 * @method static \Illuminate\Database\Query\Builder|\Baum\Node withoutRoot()
 * @method static \Illuminate\Database\Query\Builder|\Baum\Node limitDepth($limit)
 * @mixin \Eloquent
 */
class Category extends Node
{
    use SoftDeletes;
    /** @var string Tên bảng sử dụng trong Database */
    protected $table = 'categories';

    /** @var array Các field được bảo vệ */
    protected $guarded = ['id'];

    protected $dates = ['deleted_at'];
    public $timestamps = true;

    /**
     * Lấy trạng thái của Cat là đang active hoặc không
     * @return bool
     */
    public function isActive(){
        return boolval($this->cat_active);
    }

    /**
     * Tim kiem
     * @param $query
     * @param $filters
     * @return QueryBuilder
     */
    public function scopeSearching($query, $filters){
        if(empty($filters))return $query;
        foreach($filters as $filter){
            $a = $filter[0];
            $b = isset($filter[1]) ? $filter[1] : null;
            $c = isset($filter[2]) ? $filter[2] : null;
            $d = isset($filter[3]) ? $filter[3] : 'and';
            $query = $query->where($a, $b, $c, $d);
        }
        return $query;
    }

    public function getAvatar(){
        if($this->avatar_path != ''){
            return MyStorage::get_image_link($this->disk,$this->avata_path,"cate_medium");
        }
    }

}

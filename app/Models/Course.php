<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 9/8/15
 * Time: 09:44
 */

namespace App\Models;


use App\Core\Contracts\TaggableForIndex;
use App\Core\HtmlTools;
use App\Core\MyStorage;
use App\Events\Frontend\CourseContentChange;
use App\Models\Traits\CustomAsDateTimeFunction;
use Carbon\Carbon;
use Conner\Tagging\Contracts\TaggableContract;
use Conner\Tagging\Taggable;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Elasticquent\ElasticquentTrait;
use Eloquence\Behaviours\CountCache\CountCache;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use League\Flysystem\Adapter\Local;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Course
 *
 * @property integer $id
 * @property string $cou_title
 * @property string $cou_sub_title
 * @property string $cou_summary
 * @property integer $cou_cate_id
 * @property integer $cou_user_id
 * @property integer $cou_price
 * @property string $cou_cover
 * @property string $cou_goals
 * @property string $cou_requirements
 * @property string $cou_audience
 * @property string $cou_knowledge_goals
 * @property integer $cou_skill_level
 * @property boolean $cou_active
 * @property boolean $cou_delete
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @property string $language
 * @property string $cover_disk
 * @property string $cover_path
 * @property string $intro_video_disk
 * @property string $intro_video_path
 * @property integer $cou_views
 * @property integer $edit_status
 * @property string $access_privacy
 * @property integer $user_count
 * @property integer $content_count
 * @property integer $content_section_count
 * @property integer $content_lecture_count
 * @property integer $public_status
 * @property string $slug
 * @property string $sub_summary
 * @property float $rating
 * @property integer $review_count
 * @property integer $review_count_1
 * @property integer $review_count_2
 * @property integer $review_count_3
 * @property integer $review_count_4
 * @property integer $review_count_5
 * @property string $base_sub_path
 * @property string $intro_video_hd_path
 * @property string $intro_video_sd_path
 * @property string $sub_languages
 * @property integer $convert_status
 * @property-read \App\Models\Category $category
 * @property-read \App\Models\User $user
 * @property mixed $course_tags
 * @property-read mixed $description
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Reviews[] $reviews
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CourseContent[] $course_contents
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CourseStudent[] $courseStudents
 * @property-read \Illuminate\Database\Eloquent\Collection|\Conner\Tagging\Model\Tagged[] $tagged
 * @property-read mixed $tags
 * @property mixed $tag_names
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Course whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Course whereCouTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Course whereCouSubTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Course whereCouSummary($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Course whereCouCateId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Course whereCouUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Course whereCouPrice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Course whereCouCover($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Course whereCouGoals($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Course whereCouRequirements($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Course whereCouAudience($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Course whereCouKnowledgeGoals($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Course whereCouSkillLevel($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Course whereCouActive($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Course whereCouDelete($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Course whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Course whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Course whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Course whereLanguage($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Course whereCoverDisk($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Course whereCoverPath($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Course whereIntroVideoDisk($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Course whereIntroVideoPath($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Course whereCouViews($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Course whereEditStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Course whereAccessPrivacy($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Course whereUserCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Course whereContentCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Course whereContentSectionCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Course whereContentLectureCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Course wherePublicStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Course whereSlug($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Course whereSubSummary($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Course whereRating($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Course whereReviewCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Course whereReviewCount1($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Course whereReviewCount2($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Course whereReviewCount3($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Course whereReviewCount4($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Course whereReviewCount5($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Course whereBaseSubPath($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Course whereIntroVideoHdPath($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Course whereIntroVideoSdPath($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Course whereSubLanguages($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Course whereConvertStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Course withAllTags($tagNames)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Course withAnyTag($tagNames)
 * @mixin \Eloquent
 */
class Course extends Model implements CountCache, TaggableContract, SluggableInterface
{
    use SoftDeletes;
    use Taggable;
    use SluggableTrait;
    use ElasticquentTrait;
    use CustomAsDateTimeFunction;

    const INDEX_TYPE = 'course';

    /** @var string Tên bảng sử dụng trong Database */
    protected $table = 'courses';

    /** @var array Các field được bảo vệ */
    protected $guarded = ['id', 'sub_summary'];

    protected $dates = ['deleted_at'];
    public $timestamps = true;

    protected $sluggable = [
        'build_from' => 'cou_title',
        'save_to' => 'slug',
        'on_update' => true,
    ];

    protected $indexSettings = [
        'analysis' => [
            'char_filter' => [
                'my_html' => [
                    'type' => 'html_strip',
                ]
            ],
            'tokenizer' => [
                'tagSlugTokenizer' => [
                    'type' => 'pattern',
                    'pattern' => '([a-zA-Z0-9-_]+)',
                    'group' => 1,
                ],
                'exactKeywordsTokenizer' => [
                    'type' => 'pattern',
                    'pattern' => '\s*([^,]+)\s*',
                    'group' => 1,
                ]
            ],
            'analyzer' => [
                'tagSlugAnalyzer' => [
                    'type' => 'custom',
                    'tokenizer' => 'tagSlugTokenizer',
                ],
                'exactKeywordsAnalyzer' => [
                    'type' => 'custom',
                    'tokenizer' => 'exactKeywordsTokenizer',
                ],
                'stripHtml' => [
                    'type' => 'custom',
                    'char_filter' => 'my_html',
                    'tokenizer' => 'standard',
                ]
            ]
        ]
    ];

    protected $mappingProperties = array(
        'cou_title' => [
            'type' => 'string',
        ],
        'slug' => [
            'type' => 'string',
        ],
        'content' => [
            'type' => 'string',
            'analyzer' => 'stripHtml',
        ],
        'tags' => [
            'type' => 'string',
            'analyzer' => 'tagSlugAnalyzer'
        ],
//        'created_at' => [
//            'type' => 'date',
//            'format' => 'basic_date_time_no_millis',
//        ],
//        'updated_at' => [
//            'type' => 'date',
//            'format' => 'basic_date_time_no_millis',
//        ],
        'creator' => [
            'type' => 'string',
        ],
        'view' => [
            'type' => 'long',
        ],
        'content_seo' => [
            'type' => 'string',
            'analyzer' => 'stripHtml',
        ],
        'price' => [
            'type' => 'integer',
        ],
        'active_status' => [
            'type' => 'integer',
        ],
        'editing_status' => [
            'type' => 'integer',
        ],
        'privacy' => [
            'type' => 'string',
        ],

    );

    function getIndexDocumentData()
    {
        return array(
            'id' => $this->id,
            'cou_title' => $this->cou_title,
            'slug' => $this->slug,
            'cou_summary' => $this->cou_summary,
            'cou_goals' => $this->cou_goals,
            'cou_requirements' => $this->cou_requirements,
            'cou_audience' => $this->cou_audience,
            'cou_knowledge_goals' => $this->cou_knowledge_goals,
            'tags' => $this->course_slug_tags,
            'cou_user_id' => $this->cou_user_id,
            'cou_views' => $this->cou_views,
            'sub_summary' => $this->sub_summary,
            'rating' => $this->rating,
            'cou_price' => $this->cou_price,
            'cou_active' => $this->cou_active,
            'edit_status' => $this->edit_status,
            'access_privacy' => $this->access_privacy,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        );
    }

    public function countCaches() {
        return [
            'course_count' => ['App\Models\Category', 'cou_cate_id', 'id']
        ];
    }

//    public function setCreatedAtAttribute($value){
//        \Log::alert($value);
//        return "aaaa";
//    }

    public function category(){
        return $this->belongsTo('App\Models\Category', 'cou_cate_id');
    }

    public function user(){
        return $this->belongsTo('App\Models\User','cou_user_id');
    }

    public function userexams(){
        return $this->belongsTo('App\Models\UserExams','course_id');
    }

    public function setCourseTagsAttribute($new_tag){
        if($this->exists){
            if($new_tag){
                $this->retag($new_tag);
            }else{
                $this->untag();
            }
        }
    }
    public function getCourseTagsAttribute(){
        if($this->exists){
            return $this->tagged->implode('tag_name', ', ');
        }
        return "";
    }
    public function getCourseSlugTagsAttribute(){
        if($this->exists){
            return $this->tagged->implode('tag_slug', ', ');
        }
        return "";
    }

    public function setCouSummaryAttribute($value){
        if(empty($this->attributes['cou_summary']) || $this->attributes['cou_summary'] != $value){
            $this->attributes['cou_summary'] = HtmlTools::purify($value, 'description');
        }
    }

    public function setSubSummaryAttribute($value){
        if($this->attributes['sub_summary'] != $value){
            $this->attributes['sub_summary'] = HtmlTools::purify($value, 'description_seo');
        }
    }

    public function rePurify(){
        return $this->save(['re_purify' => true]);
    }

    public function getDescriptionAttribute()
    {
        if($this->cou_summary == ''){
            return $this->cou_title;
        }
        return str_limit(strip_tags($this->cou_summary), 255);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reviews()
    {
        return $this->hasMany('App\Models\Reviews','rev_cou_id');
    }

    public function getRatingAttribute()
    {
        return number_format($this->attributes['rating'], 1);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function course_contents(){
        return $this->hasMany('App\Models\CourseContent','course_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function courseStudents(){
        return $this->hasMany('App\Models\CourseStudent', 'course_id');
    }

    public function get_intro_video_stream_link($id_video_intro){

        if(!$this->exists)return "";
        $options['secure'] = true;
        $options['id'] = $id_video_intro;

        return MyStorage::get_video_stream_link($this->intro_video_disk, $this->intro_video_path, $options);
    }

    /**
     * Lấy ảnh cover của khóa học
     * @param string $template
     * @return string
     */
    public function get_cached_image($template = 'cc_large'){
        if(!$this->exists){
            return MyStorage::get_default_image($template);
        }
        return MyStorage::get_image_link($this->cover_disk, $this->cover_path, $template);
    }

    /**
     * Ánh xạ của hàm get_cached_image
     * @param string $template
     * @return string
     * @see get_cached_image
     */
    public function getThumbnail($template = 'cc_small'){
        return $this->get_cached_image($template);
    }

    public function getPrice($free_text = "Miễn phí", $currency = "VND"){
        return ($this->cou_price > 0) ? number_format($this->cou_price) . " " . $currency : $free_text;
    }

    public function save(array $options = [])
    {
        // update purifier
        if(array_get($options, 're_purify', false) == true){
            $this->attributes['cou_summary'] = HtmlTools::purify($this->attributes['cou_summary'], 'description');
            $this->attributes['sub_summary'] = HtmlTools::purify($this->attributes['sub_summary'], 'description');
        }

        $return  = parent::save($options);
        if($return == true){
            if($this->wasRecentlyCreated){
                $this->addAllToIndex();
            }else{
                event(new CourseContentChange($this->id));
            }
        }
        return $return;
    }

    public function delete()
    {
        if($this->forceDeleting){// FORCE DELETE
            // TODO: Finish it if support force delete
            // xóa các file liên quan

            // xóa các dữ liệu liên quan


        }
        $return  = parent::delete();
        if($return == true){
            $this->removeFromIndex();
            event(new CourseContentChange($this->id));
        }
        return $return;
    }

    public function editingStatus($status = null){
        if($status == null){
            return $this->edit_status;
        }else{
            $this->edit_status = $status;
        }
    }

    public function accessPrivacy($privacy = null){
        if($privacy == null){
            return $this->access_privacy;
        }else{
            $this->access_privacy = $privacy;
        }
    }

    public function isActive(){
        return $this->cou_active;
    }

    /**
     * Content khóa học sẽ được lưu vào cache 30 ngày, tự động cập nhật lại khi khóa học có thay đổi.
     *
     * @return mixed|null
     */
    public function getCachedContents(){

        if(!$this->exists) return null;

        $course_id = $this->id;

        return \Cache::remember('course_content_' . $course_id,
            60*24*30,
            function() use ($course_id){
                $saves = Course::find($course_id)
                    ->course_contents()
                    ->orderBy('content_order','asc')
                    ->get();
                foreach($saves as $k=>$v){
                    $saves[$k]->getContent();
                }
                return $saves;
            });
    }

    /**
     * Gọi hàm này khi cần cập nhật bộ đếm cho khóa học: đếm học sinh, đếm nội dung, đếm bài học, ...
     * @return bool
     */
    public function updateCounter(){
        // user count
        $this->user_count = $this->courseStudents()->count('id');
        // course content
        $this->content_count = $this->course_contents()->count('id');
        // lecture
        $this->content_lecture_count = $this->course_contents()
            ->where('content_type', config('course.content_types.section'))
            ->count('id');
        // section
        $this->content_lecture_count = $this->course_contents()
            ->where('content_type', config('course.content_types.lecture'))
            ->count('id');
        // test

        // save
        return $this->save();

    }

    public function updateReview()
    {
        /** @var Collection $reviews */
        $reviews = $this->reviews()->where('rev_active', 1)->get();
        if($reviews->count() < 1){
            $this->review_count = 0;
            $this->review_count_1 = 0;
            $this->review_count_2 = 0;
            $this->review_count_3 = 0;
            $this->review_count_4 = 0;
            $this->review_count_5 = 0;
            $this->rating = 0;
        }else{
            $this->review_count = $reviews->count();
            $this->review_count_1 = $reviews->where('rating', 1)->count();
            $this->review_count_2 = $reviews->where('rating', 2)->count();
            $this->review_count_3 = $reviews->where('rating', 3)->count();
            $this->review_count_4 = $reviews->where('rating', 4)->count();
            $this->review_count_5 = $reviews->where('rating', 5)->count();
            $this->rating = $reviews->average('rating');
        }
        return $this->save();
    }

    public function get_public_view_link(Array $additions = []){
        if(!$this->_get_public_view_link){
            if($this->slug == ""){
                $this->sluggify()->save();
            }
            $this->_get_public_view_link = route('frontend.course.public_view', ['slug' => $this->slug] + $additions);
        }
        return $this->_get_public_view_link;
    }
    public function get_registered_view_link(){
        if(!$this->_get_registered_view_link){
            if($this->slug == ""){
                $this->sluggify()->save();
            }
            $this->_get_registered_view_link = route('frontend.course.registered_view', ['slug' => $this->slug]);
        }
        return $this->_get_registered_view_link;
    }

    public function get_default_studying_link($content_id = null){

        return route('frontend.course.default_studying',
                ['slug' => $this->slug, 'content_id' => $content_id]);
    }

    public function get_public_studying_link($content_id = null){

        return route('frontend.course.public_studying',
                ['slug' => $this->slug, 'content_id' => $content_id]);
    }

    public function isPublicStudy($content_id = null){
        if(!$this->cou_active){// chưa active thì false
            return false;
        }
        // nếu khóa học miễn phí thì ok
        if($this->cou_price < 1){
            return true;
        }
        // nếu khóa học muốn truy cập là public thì cũng OK
        if($content_id != null){
            /** @var CourseContent $cc */
            $cc = CourseContent::find($content_id);
            if($cc && $cc->accessPrivacy() == 'public'){
                return true;
            }
        }else{
            // nếu khóa học có một số bài free thì cũng ok, chú ý khi hiển thị trang public study
            /** @var CourseContent $content */
            foreach($this->getCachedContents() as $content){
                if($content->get_type() == config('course.content_types.lecture')){
                    if($content->accessPrivacy() == 'public'){
                        return true;
                    }
                }
            }
        }

        // mặc đinh là false
        return false;
    }

    public function getTitle()
    {
        return $this->cou_title;
    }

    public function getSubtitle()
    {
        return $this->cou_sub_title;
    }

    public function getLink()
    {
        return $this->get_public_view_link();
    }

    public function indexType()
    {
        return self::INDEX_TYPE;
    }

    private function indexTag()
    {
        $tag_slugs = [];
        foreach($this->tagged as $tag){
            $tag_slugs[] = $tag->tag_slug;
        }
        return implode(',', $tag_slugs);
    }


    // INTRO_VIDEO PROCESS



}
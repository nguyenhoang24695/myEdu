<?php

namespace App\Http\Controllers\Frontend;

use App\Core\MySeacher;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SearchController extends Controller
{
    protected $search;
    public function __construct(MySeacher $search)
    {
        $this->search = $search;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $query  = $request->get('q');
        $type_s = $request->get('se');

        \SEOMeta::setTitle('Tìm kiếm - '. $query);

        if($query == ""){
            return redirect('/');
        }

//        Tìm kiếm khóa học
//        $courses  =  $this->search->searchCourse($query);
        $courses  =  Course::where('cou_title',"like", '%'.$query.'%') -> get();

        if(count($courses) > 0){


            $course_ids = [];
            foreach($courses as $course){
                $course_ids[] = $course['id'];
            }

            $data_course = Course::with('user')
                ->whereIn('id', $course_ids)
                ->orderByRaw('FIELD(`id`,' . implode(',', $course_ids) . ')')
                ->get();

//            dd($data_course);
        } else {
            $data_course = [];
        }

        //Tìm kiếm user
//        $users   =   $this->search->searchUser($query);
        $users   =  User::where('name',"like", '%'.$query.'%') -> get();
        if(count($users)){
            $use_id = [];
            foreach($users as $user){
                $use_id[] = $user['id'];
            }
            $data_user  =   User::whereIn('id', $use_id)->get();
        } else {
            $data_user  =   [];
        }
        

        /***************************/

        $tags         =  [];
        $appended     =  [];

        $total        = count($courses)+count($users);

        //Kết quả khóa học
        $data['data_course'] = $data_course;
        //Kết quả từ khóa liên quan
        $data['tags']        = $tags;
        //Kết quả thành viên
        $data['data_user']   = $data_user;
        //Append
        $data['appended']    = $appended;
        //Breadcrumb
        $data['query']       = $query;
        //Tổng số kết quả trả về
        $data['total']       = $total;
        $data['total_course']= count($courses);
        $data['total_user']  = count($users);
        $data['type_s']      = $type_s;

        return view('frontend.search.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
    }

    public function apiSearch(Request $request){

        if($request->ajax()) {
            $query      = $request->get('phrase');
            $arr_course = array();

            //Tìm kiếm khóa học
            $courses  =  $this->search->searchCourse($query);
            if(isset($courses['course'])){
                foreach($courses['course'] as $course){
                    $arr_course[] =   [
                                        'name'  => $course->cou_title,
                                        'src'   => url('course/preview').'/'.$course->slug,
                                      ];
                }
            }

            $arr_search       = $arr_course;
            return response()->json($arr_search);
        } else {
            abort(404);
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

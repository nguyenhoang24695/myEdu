<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 10/20/15
 * Time: 11:16
 */

namespace App\Http\Controllers\Backend;


use App\Http\Controllers\Controller;
use Nayjest\Grids\Grids;

class GridController extends Controller
{
    public function user(){
        $cfg = [
            'src' => 'App\Models\User',
            'columns' => [
                'id',
                'name',
                'email'
            ]
        ];
        echo Grids::make($cfg);
    }
}
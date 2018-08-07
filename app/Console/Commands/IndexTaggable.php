<?php

namespace App\Console\Commands;

use App\Core\MyIndexer;
use App\Models\Course;
use Illuminate\Console\Command;
use Illuminate\Database\Query\Builder;

class IndexTaggable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'indexer:taggable
    {type : Index can thao tac course/blog/document}
    {action : Thao tac delete/index/update}
    {--ids=all : cac id cach nhau dau , hoac all }
    {--idmin=0 : id nho nhat can thao tac }
    {--idmax=0 : id lon nhat can thao tac}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $accepted_indexs = [
        "course"
    ];

    private $bulk_size = 1000;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param $type
     * @return \Illuminate\Database\Eloquent\Builder
     * @throws \Exception
     */
    private function getQueryModel($type){

        if(in_array($type, $this->accepted_indexs)) {
            switch ($type) {
                case 'course' :
                    return Course::query();
            }
        }

        throw new \Exception(" Khong ho tro index nay");
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $action = $this->argument('action');
        $type = $this->argument('type');
        $ids =$this->option('ids');
        if($ids != 'all'){
            $ids = explode(',', $ids);
        }
        $idmin = intval($this->option('idmin'));
        $idmax = intval($this->option('idmax'));
        $idmax = $idmax ? $idmax : PHP_INT_MAX;

        $this->info("Dang thuc hien " . $action);

        switch($action){
            case 'index' :
                /** @var Builder $query */
                $query = $this->getQueryModel($type);
                if($ids == 'all'){
                    if($idmin > 0){
                        $query->where('id', '>=', $idmin);
                    }
                    if($idmax < PHP_INT_MAX){
                        $query->where('id', '<=', $idmax);
                    }
                }else{
                    $query->whereIn('id', $ids);
                }

                $query->chunk($this->bulk_size, function($taggables){
                    MyIndexer::indexTaggables($taggables);
                });

                break;
            case 'delete' :
                if($ids == 'all'){
                    MyIndexer::deleteTaggableIndex($type);
                }else if($ids == 'range'){

                }else {
                    MyIndexer::deleteTaggableIndex($type, $ids);
                }
                break;
            case 'update' :

                break;
        }

        $this->info("DONE");
    }
}

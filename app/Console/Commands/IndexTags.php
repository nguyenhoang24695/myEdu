<?php

namespace App\Console\Commands;

use App\Core\MyIndexer;
use Conner\Tagging\Model\Tag;
use Illuminate\Console\Command;

class IndexTags extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'indexer:tag
    {action : Thao tac delete/index/update}
    {ids=all : cac id cach nhau dau , hoac all }
    {idmin=0 : id nho nhat can thao tac }
    {idmax=0 : id lon nhat can thao tac}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mot so thao tac voi index TAG khi su dung search engine';

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
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $action = $this->argument('action');
        $ids =$this->argument('ids');
        if($ids != 'all'){
            $ids = explode(',', $ids);
        }
        $idmin = intval($this->argument('idmin'));
        $idmax = intval($this->argument('idmax'));
        $idmax = $idmax ? $idmax : PHP_INT_MAX;

        $this->info("Dang thuc hien " . $action);

        switch($action){
            case 'index' :
                $query = Tag::query();
                if($ids == 'all'){
                    if($idmin > 0){
                        $query->where('id', '>', $idmin);
                    }
                    if($idmax < PHP_INT_MAX){
                        $query->where('id', '<', $idmax);
                    }
                }else{
                    $query->whereIn('id', $ids);
                }

                $query->chunk($this->bulk_size, function($tags){
                    MyIndexer::indexTags($tags);
                });

                break;
            case 'delete' :
                if($ids == 'all'){
                    MyIndexer::deleteTagIndex();
                }else{
                    MyIndexer::deleteTags($ids);
                }
                break;
            case 'update' :

                break;
        }

        $this->info("DONE");
    }
}

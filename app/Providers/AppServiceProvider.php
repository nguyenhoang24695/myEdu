<?php namespace App\Providers;

use App\Listeners\Observer\TagObserver;
use App\Models\Course;
use Carbon\Carbon;
use Conner\Tagging\Model\Tag;
use Eloquence\Behaviours\CountCache\CountCacheObserver;
use GrahamCampbell\Flysystem\Facades\Flysystem;
use Illuminate\Support\Facades\Cache;
use Validator;
use App\Models\Category;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        $this->customValidator();

        //
        $this->registerEvents();

        $this->prepareCommonViewVar();

        Carbon::setLocale(config('app.locale'));
    }

    /**
     * Register any application services.
     *
     * This service provider is a great spot to register your various container
     * bindings with the application. As you can see, we are registering our
     * "Registrar" implementation here. You can add your own bindings too!
     *
     * @return void
     */
    public function register()
    {
        if (env('APP_DEBUG')) {
            $this->app->register('Barryvdh\Debugbar\ServiceProvider');
            /**
             * Support IDE
             */
            if($this->app->environment() == 'local'){
                $this->app->register('Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider');
                $this->app->register('Laracasts\Generators\GeneratorsServiceProvider');
                //Back up
                $this->app->register('BackupManager\Laravel\Laravel5ServiceProvider');
            }


        }


        $this->registerBindings();

    }


    /**
     * Khai báo binding các Contract và Repository của app
     */
    public function registerBindings()
    {
        // Backend
        $this->app->bind(
            'App\Repositories\Backend\Category\CategoryContract',
            'App\Repositories\Backend\Category\EloquentCategoryRepository'
        );

        // Frontend
        $this->app->bind(
            'App\Repositories\Frontend\Course\CourseContract',
            'App\Repositories\Frontend\Course\EloquentCourseRepository'
        );

        $this->app->bind(
            'App\Repositories\Frontend\Course\Cods\CourseCodsContract',
            'App\Repositories\Frontend\Course\Cods\EloquentCourseCodsRepository'
        );

        $this->app->bind(
            'App\Repositories\Frontend\Blog\BlogContract',
            'App\Repositories\Frontend\Blog\EloquentBlogRepository'
        );

        $this->app->bind(
            'App\Repositories\Frontend\BlogCategories\BlogCategoriesContract',
            'App\Repositories\Frontend\BlogCategories\EloquentBlogCategoriesRepository'
        );

        $this->app->bind(
            'App\Repositories\Frontend\Category\CategoryContract',
            'App\Repositories\Frontend\Category\EloquentCategoryRepository'
        );

        $this->app->bind(
            'App\Repositories\Frontend\Reviews\ReviewsContract',
            'App\Repositories\Frontend\Reviews\EloquentReviewsRepository'
        );

        $this->app->bind(
            'App\Repositories\Frontend\Discussion\DiscussionContract',
            'App\Repositories\Frontend\Discussion\EloquentDiscussionRepository'
        );

        $this->app->bind(
            'App\Repositories\Frontend\Notification\NotificationContract',
            'App\Repositories\Frontend\Notification\EloquentNotificationRepository'
        );
    }

    private function customValidator()
    {

        Validator::extend('cat_exist', function ($attribute, $value, $parameters) {
            return Category::whereId($value)->exists();
        });

        Validator::extend('cat_active', function ($attribute, $value, $parameters) {
            return Category::whereId($value)->where('cat_active', '>', 0)->exists();
        });

        Validator::extend('tmp_exist', function ($attribute, $value, $parameters) {
            $type = $parameters[0];
            return \Flysystem::connection('tmp')->has($type . DIRECTORY_SEPARATOR . $value);
        });
    }

    private function registerEvents()
    {
        Course::observe(new CountCacheObserver());
        Tag::observe(new TagObserver());
    }

    private function prepareCommonViewVar()
    {
        $hot_tags = [];
        try{
            $hot_tags = \Cache::remember('unibee_hot_tags', config('cache.hot_tags_cache_time', 10), function(){
                $hot_tags = Tag::orderBy('count', 'desc')->limit(20)->get(['name']);
                return $hot_tags;
            });
        }catch (\Exception $ex){
            \Log::error($ex->getMessage());
        }finally{
            view()->share('hot_tags', $hot_tags);
        }

        javascript()->put([
            'unibee_tags_link' => '',
            'default_summernote_toolbar' => config('app.summernote_toolbars.default'),
            'tiny_summernote_toolbar' => config('app.summernote_toolbars.tiny')
        ]);

        view()->share('video_player', config('app.video_player', 'videojs'));
    }
}

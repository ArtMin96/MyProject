<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class CRUD extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:crud';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command will create all crud methods with dynamic variables';

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
     * @return int
     */
    public function handle()
    {
        $className = $this->ask('Please select Class Name for Controller and Model ENGLISH ONLY');

        if(preg_match('/[a-zA-Z]/', $className) ) {
            $dirname = $this->ask('Choose Route Name For Resource in English');
            if(preg_match('/[a-zA-Z]/', $dirname)) {
            $dirname = Str::snake($className);
                if(preg_match('/\s/',$className))
                {
                    $className = ucfirst(Str::camel(strtolower($className)));
                }
                else{
                    $className =ucfirst(Str::camel($className));
                }

            $table_name = Str::plural(Str::snake($className));

            $this->info('Please Press Enter to Confirm');

                Artisan::call('make:controller',['name'=>$className.'Controller','--resource' => true, '--model'=>'Models/'.$className]);

                Artisan::call('make:migration create_'.$table_name.'_table');

                    if(!is_dir('resources/views/admin')) {
                        mkdir('resources/views/admin');
                    }

                    if(!is_dir('resources/views/admin/pages')) {
                        mkdir('resources/views/admin/pages');
                    }

                    if(!is_dir('resources/views/admin/pages/'.$dirname)) {
                        mkdir('resources/views/admin/pages/'.$dirname);
                    }
                        if(is_dir('resources/views/admin/pages/'.$dirname))
                        {
                            $create = fopen('resources/views/admin/pages/'.$dirname.'/create.blade.php',"w");
                            $createHTML = '#call your data using variable $$item';
                            fwrite($create,$createHTML);
                            fclose($create);

                            $edit = fopen('resources/views/admin/pages/'.$dirname.'/edit.blade.php',"w");
                            $editHTML = '#call your edit element using variable $$item';
                            fwrite($edit,$editHTML);
                            fclose($edit);

                            $index = fopen('resources/views/admin/pages/'.$dirname.'/index.blade.php',"w");
                            $indexHTML = '#call your all data elements using variable $all';
                            fwrite($index,$indexHTML);
                            fclose($index);


                            $show = fopen('resources/views/admin/pages/'.$dirname.'/show.blade.php',"w");
                            $showHTML = '#call your show element using variable $$item';
                            fwrite($show,$showHTML);
                            fclose($show);

                            $web = fopen('routes/web.php','a+');
                            $webHTML = PHP_EOL.PHP_EOL.'Route::resource(\''.$dirname.'\', \''.$className.'Controller\')->parameters(['.PHP_EOL.'     \''.$dirname.'\' => \''.lcfirst($className).'\''.PHP_EOL.']);';
                            fwrite($web,$webHTML);
                            fclose($web);
                        }
                        $controllerContent = file_get_contents('app/Http/Controllers/'.$className.'Controller.php');
                        $replaced = str_replace('||!!VARNAME!!||',$dirname,$controllerContent);
                        file_put_contents('app/Http/Controllers/'.$className.'Controller.php',$replaced);

                            $this->info('Everything is created! Have fun!');
                    }
                    else {
                        $this->info('Please Select a Valid name in English');
                    }
            }else {
                $this->info('Please Select a Valid name in English');
            }

    }
}

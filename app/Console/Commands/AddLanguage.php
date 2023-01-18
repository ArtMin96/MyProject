<?php

namespace App\Console\Commands;

use App\Models\Language;
use Illuminate\Console\Command;

class AddLanguage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:language';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new language';

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
        $name = $this->ask('Please write language name');

        $short_code = $this->ask('Please write language short code');



        if($this->confirm('Are you confirm to create language <"Short code:'.$short_code.'"> ?')){
            if(strpos($name,'rmen'))
            {
                $name='հայերեն';
            }
            if(strpos($name,'ussi'))
            {
                $name='русский';
            }
            $language = Language::create([
                'name' =>$name,
                'code' => $short_code,
            ]);
            if($language){
                return $this->info(  'Language successfully created');
            }
            else{
                $this->warn('Language was not created');
            }
        }
        $this->warn('Language was not created');
    }
}

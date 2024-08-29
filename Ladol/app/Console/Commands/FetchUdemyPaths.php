<?php

namespace App\Console\Commands;
use App\UdemyActivity;
use App\LearningPathSections;
use App\LearningPath;
use GuzzleHttp\Client;  
use Illuminate\Console\Command;

class FetchUdemyPaths extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:learningpath';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches all the learning path created on udemy';

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
        try{
            $client_id     = env('UDEMY_CLIENT_ID');
            $client_secret = env('UDEMY_CLIENT_SECRET');
            $account_name  = env('UDEMY_ACCOUNT_NAME');
            $account_id    = env('UDEMY_ACCOUNT_ID');
            $client        = new Client();
            $response      = $client->get("https://{$account_name}.udemy.com/api-2.0/organizations/{$account_id}/learning-paths/list/", [
                'auth' => [$client_id, $client_secret]
            ]);

            $courses = json_decode($response->getBody()->getContents(), true);
            if(isset($courses["results"])){
                foreach($courses["results"] as $course){
                    $learningpath = LearningPath::updateOrCreate([
                        'path_id' => $course['id']
                    ], [
                        'title'                      => $course['title'],
                        'description'                => $course['description'],
                        'editor_name'                => $course['editors'][0]['display_name'],
                        'editor_email'               => $course['editors'][0]['email'],
                        'created'                    => $course['created'],
                        'estimated_content_length'   => $course['estimated_content_length'],
                        'number_of_content_items'    => $course['number_of_content_items'],
                        'is_pro_path'                => $course['is_pro_path'],
                        'last_modified'              => $course['last_modified'],
                        'url'                        => $course['url'],
                        'path_id'                    => $course['id']
                    ]);
                   
                    if(isset($course["sections"][0])){
                        foreach($course["sections"][0]['items'] as $item){
                            LearningPathSections::updateOrCreate(['learning_path_id' => $learningpath->id, 'duration' => $item["duration"]], 
                            [
                                'learning_path_id' => $learningpath->id, 
                                'title'            => $item["title"],
                                'url'              => $item["url"],
                                'type'             => $item["type"],
                                'duration'         => $item["duration"],
                                'order'            => $item["order"],
                                'thumbnail'        => $item["thumbnail"]["size_96x54"],
                                'resource_url'     => $item["resource_url"],
                                'no_of_items' => $item["number_of_items"]
                            ]);   
                        }
                    }
                } 
            }
       }catch(\Exception $e){
            dd($e->getMessage());
       }
    }
}

<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use GuzzleHttp\Client;
use App\UdemyCourses;

class FetchUdemyCourses implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
     
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //Job handles fetch of Udemy courses:
        $page          = 1;
        $perPage       = 100;
        $client_id     = env('UDEMY_CLIENT_ID');
        $client_secret = env('UDEMY_CLIENT_SECRET');
        $account_name  = env('UDEMY_ACCOUNT_NAME');
        $account_id    = env('UDEMY_ACCOUNT_ID');
        $client = new Client();
        do{
           
            $response = $client->get("https://{$account_name}.udemy.com/api-2.0/organizations/{$account_id}/courses/list/?page_size={$perPage}&page={$page}", [
                'auth' => [$client_id, $client_secret]
            ]);

            $courses = json_decode($response->getBody()->getContents(), true);
            $courseData = [];
            foreach($courses["results"] as $course){
                $courseData[] =  [
                    'name'          => $course['title'],
                    'description'   => $course['description'],
                    'url'           => $course['url'],
                    'duration'      => $course["estimated_content_length"],
                    'categories'    => $course["categories"][0],
                    'instructor'    => $course["instructors"][0],
                    'images'        => $course["images"]["size_50x50"],
                    'headline'      => $course["headline"],
                    'levels'        => $course["level"]
                ];
            }

            UdemyCourses::insert($courseData);

            $page++;
            
        }while($courses["next"] != null);
       
    }
}

<?php

use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $keys =
            [
                'logo'=>
                    [
                        'logo','favicon','footer_logo','additional_logo'
                    ],
                'social_media' => [
                    'facebook' , 'LinkedIn','Instagram', 'Youtube','Twitter','VK'
                ],
                  'mail' => [
                  'mail_driver','mail_host','mail_port','mail_username','mail_password','mail_encryption','mail_from','mail_to'
                ],
                'integration' => [
                    'integrations','Google_API_KEY','Instagram_API_KEY'
                ],
                'contacts' => [
                    'address','phone_main','phone_secondary'
                ]
        
            ];

        foreach ($keys as $sub_key => $key)
        {
            if(is_array($key)) {

                foreach ($key as $value)
                {
                    $row = \App\Models\Settings::where('key',$value)->where('group',$sub_key)->count();

                    if($row < 1){
                        \App\Models\Settings::create([
                            'key' => $value,
                            'group' => $sub_key,
                        ]);
                    }
                }
            }
            else
            {
                \App\Models\Settings::create([
                    'key' => $key,
                    'group' => $key,
                ]);
            }
        }

    }
}

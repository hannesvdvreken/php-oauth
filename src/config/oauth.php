<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Consumer credentials
    |--------------------------------------------------------------------------
    |
    | Configure all your desired credentials right here. Use the snake_case version
    | of the services class name.
    |
    */
    'consumers' => array(

        /*
        |--------------------------------------------------------------------------
        | Twitter
        |--------------------------------------------------------------------------
        */
        'twitter' => array(
            'client_id'     => '',
            'client_secret' => '',
        ),

        /*
        |--------------------------------------------------------------------------
        | Facebook
        |--------------------------------------------------------------------------
        |
        | For all possible scopes, look here: https://developers.facebook.com/docs/reference/login/
        |
        */
        'facebook' => array(
            'client_id'     => '',
            'client_secret' => '',
            'scopes' => array(),
        ),

        /*
        |--------------------------------------------------------------------------
        | Github
        |--------------------------------------------------------------------------
        |
        | For all possible scopes, look here: http://developer.github.com/v3/oauth/#scopes
        |
        */
        'github' => array(
            'client_id'     => '',
            'client_secret' => '',
        ),

        /*
        |--------------------------------------------------------------------------
        | CampaignMonitor
        |--------------------------------------------------------------------------
        |
        | For all possible scopes, look here: 
        | http://www.campaignmonitor.com/api/getting-started/#authenticating_with_oauth
        |
        */
        'campaign_monitor' => array(
            'client_id'     => '',
            'client_secret' => '',
            'scopes' => array(),
        ),

        /*
        |--------------------------------------------------------------------------
        | Mailchimp
        |--------------------------------------------------------------------------
        */
        'mailchimp' => array(
            'client_id'     => '',
            'client_secret' => '',
        ),

    ),

);
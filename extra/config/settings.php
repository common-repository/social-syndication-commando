<?php

return 
 array( 
  array(
  'title'  => 'General',
  'slug'  => 'general',
  'before' => '',
  'after'  => '',
  'stacks' => 
   array(
     array(
    'title'   => 'URL Shortener', 
    'title_text' => '',
    'body_text'  => '',
    'options'  => array(
        array(
         'data_type' => 'bool',
         'label'  => 'Use URL Shortener',
         'name'   => 'url_shortener',
         'type'   => array('input' => 'checkbox', 'value' => 1, 'text' => 'Use URL shortener for links posted to social sites' ),
         'value'  => ''
         ),
       ),

    ),
     )
  ),
  array( 
  'title'  => 'Sites APIs',
  'slug'  => 'apis',
  'before' => '',
  'after'  => '',
  'stacks' => 
   array(
     array(
    'title'   => 'Wordpress',
    'slug'   => 'wordpress', 
    'title_text'  => 'Redirect URL: ' . get_option("siteurl") . '/wp-admin/admin.php?page=stcsettings&stcwordpress=wordpress',
    'body_text' => '',
    'options'  => array(
        array(
         'label'  => 'Client ID',
         'name'   => 'wordpress_api_key',
         'type'   => 'text',
         'value'  => ''
         ),
        array(
         'label'  => 'Client Secret',
         'name'   => 'wordpress_api_secret',
         'type'   => 'text',
         'value'  => ''
         ),
       ),

    ),
     array(
    'title'   => 'Facebook', 
    'slug'   => 'facebook', 
    'title_text'  => 'Site URL:' . get_option("siteurl") . '/wp-admin/admin.php',
    'body_text' => '',
    'options'  => array(
        array(
         'label'  => 'API Key',
         'name'   => 'facebook_api_key',
         'type'   => 'text',
         'value'  => ''
         ),
        array(
         'label'  => 'API Secret',
         'name'   => 'facebook_api_secret',
         'type'   => 'text',
         'value'  => ''
         ),
       ),

    ),
     array(
    'title'   => 'Diigo', 
    'title_text' => '',
    'body_text'  => '',
    'options'  => array(
        array(
         'label'  => 'API Key',
         'name'   => 'diigo_api_key',
         'type'   => 'text',
         'value'  => ''
         )
       ),

    ),
     array(
    'title'   => 'Tumblr', 
    'title_text' => '',
    'body_text'  => '',
    'options'  => array(
        array(
         'label'  => 'API Key',
         'name'   => 'tumblr_api_key',
         'type'   => 'text',
         'value'  => ''
         ),
        array(
         'label'  => 'API Secret',
         'name'   => 'tumblr_api_secret',
         'type'   => 'text',
         'value'  => ''
         ),
       ),

    ),
     array(
    'title'   => 'Plurk', 
    'title_text' => '',
    'body_text'  => '',
    'options'  => array(
        array(
         'label'  => 'API Key',
         'name'   => 'plurk_api_key',
         'type'   => 'text',
         'value'  => ''
         ),
        array(
         'label'  => 'API Secret',
         'name'   => 'plurk_api_secret',
         'type'   => 'text',
         'value'  => ''
         ),
       ),

    ),

     array(
    'title'   => 'Twitter', 
    'title_text' => '',
    'body_text'  => '',
    'options'  => array(
        array(
         'label'  => 'API Key',
         'name'   => 'twitter_api_key',
         'type'   => 'text',
         'value'  => ''
         ),
        array(
         'label'  => 'API Secret',
         'name'   => 'twitter_api_secret',
         'type'   => 'text',
         'value'  => ''
         ),
       ),

    ),
     array(
    'title'   => 'Linkedin', 
    'title_text' => '',
    'body_text'  => '',
    'options'  => array(
        array(
         'label'  => 'API Key',
         'name'   => 'linkedin_api_key',
         'type'   => 'text',
         'value'  => ''
         ),
        array(
         'label'  => 'API Secret',
         'name'   => 'linkedin_api_secret',
         'type'   => 'text',
         'value'  => ''
         ),
       ),

    ),
   )
  )
 );

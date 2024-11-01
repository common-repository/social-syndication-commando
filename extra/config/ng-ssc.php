<?php
return array (  
 'ssc-angilarjs'  => array(
    'path'  => 'vendor/angular.min.js',
    'deps' => array('jquery'),
    'ver' => false,
    'footer'  => true
   ),  
 'ssc-ng-resources'  => array(
    'path'  => 'vendor/angular-resource.min.js',
    'deps' => array('ssc-angilarjs'),
    'ver' => false,
    'footer'  => true
   ),  
 'ssc-ng-sanitize'  => array(
    'path'  => 'vendor/angular-sanitize.min.js',
    'deps' => array('ssc-angilarjs'),
    'ver' => false,
    'footer'  => true
   ),
 'ssc-ui-router'  => array(
    'path'  => 'vendor/angular-ui-router.min.js',
    'deps' => array('ssc-angilarjs'),
    'ver' => false,
    'footer'  => true
   ),
 /*'ssc-ui-bootstrap'  => array(
    'path'  => 'vendor/ui-bootstrap.js',
    'deps' => array('ssc-angilarjs'),
    'ver' => false,
    'footer'  => true
   ),*/
 'ssc-ng-app'  => array(
    'path'  => 'app/ssc-app.js',
    'deps' => array('ssc-ui-router'),
    'ver' => false,
    'footer'  => true
   ),
 'ssc-globals'  => array(
    'path'  => 'app/shared/services/globals.js',
    'deps' => array('ssc-ng-app'),
    'ver' => false,
    'footer'  => true
   ),
 'ssc-dash-api'  => array(
    'path'  => 'app/shared/services/dash-api.js',
    'deps' => array('ssc-ng-app'),
    'ver' => false,
    'footer'  => true
   ),
 'ssc-sites-controllers'  => array(
    'path'  => 'app/sites/controllers.js',
    'deps' => array('ssc-sites-services'),
    'ver' => false,
    'footer'  => true
   ),
 'ssc-sites-services'  => array(
    'path'  => 'app/sites/services.js',
    'deps' => array('ssc-ng-app'),
    'ver' => false,
    'footer'  => true
   ),
 'ssc-settings-services'  => array(
    'path'  => 'app/settings/services.js',
    'deps' => array('ssc-ng-app'),
    'ver' => false,
    'footer'  => true
   ),
 'ssc-settings-controllers'  => array(
    'path'  => 'app/settings/controllers.js',
    'deps' => array('ssc-ng-app'),
    'ver' => false,
    'footer'  => true
   )
 );

<?php

    require_once(dirname(__FILE__).'/config.inc.php');
    require_once(dirname(__FILE__).'/classes/Router.php');
    require_once(dirname(__FILE__).'/classes/Template.php');
    
    Router::run();

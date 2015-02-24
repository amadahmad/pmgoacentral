<?php defined('BASEPATH') OR exit('No direct script access allowed');
/* 
 *  ============================================================================== 
 *  Author      : Axess Tech
 *  Email       : info@axesstech.com
 *  For         : PMGOA 
 *  Web         : http://axesstech.com
 *  For		: Zend Library (Barcode)
 *  Web		: http://zend.com
 *  License	: New BSD License
 *  ============================================================================== 
 */  
 
class Zend
{
    /**
     * Constructor
     *
     * @param    string $class class name
     */
    function __construct($class = NULL)
    {
        // include path for Zend Framework
        // alter it accordingly if you have put the 'Zend' folder elsewhere
        ini_set('include_path',
        ini_get('include_path') . PATH_SEPARATOR . APPPATH . 'third_party');
        define('EXT', '.php');
        
        if ($class)
        {
            require_once (string) $class . EXT;
            log_message('debug', "Zend Class $class Loaded");
        }
        else
        {
            log_message('debug', "Zend Class Initialized");
        }
    }

    /**
     * Zend Class Loader
     *
     * @param    string $class class name
     */
    function load($class)
    {
        require_once (string) $class . EXT;
        log_message('debug', "Zend Class $class Loaded");
    }
}

<?php

/**
 * debug helper
 */

/**
 * Extended and formated var_dump() function
 *
 * @param all $var
 * @param string or array $options
 */
function varDump($var, $options = null)
{
    if ( ! empty($options) && is_array($options)) {
        foreach ($options as $key => $value) {
            echo $key.': '.$value.'<br/>';
        }
    } else {
        echo $options.'<br/>';
    }
    echo '<pre>';
    ob_start();
    var_dump($var);
    $output = ob_get_contents();
    ob_end_clean();
    echo htmlspecialchars($output, ENT_QUOTES);
    echo '</pre>';
}

/**
 * Extended and formated print_r() function
 *
 * @param all  $var
 * @param string or array $options
 * @param bool $return
 */
function printR($var, $options = null, $return = false)
{
    if ( ! empty($options) && is_array($options)) {
        foreach ($options as $key => $value) {
            echo $key.': '.$value.'<br/>';
        }
    } else {
        echo $options.'<br/>';
    }
    echo '<pre>';
    ob_start();
    print_r($var, $return);
    $output = ob_get_contents();
    ob_end_clean();
    echo htmlspecialchars($output, ENT_QUOTES);
    echo '</pre>';
}

/**
 * Return light global vars. Superglobals or specified variables can be defined
 * to be skipped
 *
 * @return array References all variables available in global scope
 */
function globalVars()
{
    $result = [];

    //define php superglobals, will be skipped
    $skip = [
        'GLOBALS',
        '_ENV',
        'HTTP_ENV_VARS',
        '_POST',
        'HTTP_POST_VARS',
        '_GET',
        'HTTP_GET_VARS',
        '_COOKIE',
        'HTTP_COOKIE_VARS',
        '_SERVER',
        'HTTP_SERVER_VARS',
        '_FILES',
        'HTTP_POST_FILES',
        '_REQUEST',
        'HTTP_SESSION_VARS',
        '_SESSION',
        'mobile_detect',
        'content',
    ];

    //define specified user variables, will be replaced by simple string
    $long_vars = ['page_content' => ['content', 'page_text'], 'page_text'];
    foreach ($GLOBALS as $k => $v) {
        //skip superglobals
        if ( ! in_array($k, $skip)) {

            //replaced content of the user vars
            if (in_array($k, $long_vars)) {
                foreach ($long_vars as $sk => $sv) {
                    if (is_array($sv)) {
                        foreach ($sv as $k1 => $v1) {
                            $result[$sk][$v1] = 'debug: disabled content';
                        }
                    } else {
                        $result[$sv] = 'debug: disabled content';
                    }
                }
            } else {
                $result[$k] = $v;
            }
        }
    }

    return $result;
}

/**
 * Log file in uploads/temp/
 *
 * @param string  $file_name
 * @param string/array $data
 * @param boolean $cookie force to use cookie, cookie "logDebug"
 *
 * @return boolean
 */
function logDebug($file_name, $data, $cookie = false)
{
    if ($cookie && empty($_COOKIE['logDebug'])) {
        return false;
    }

    $path = UPLOADPATH.'temp/';
    $file = $path.$file_name.'-'.date('Ymd').'.log';

    if (file_put_contents($file, $data, FILE_APPEND) !== false) {
        return true;
    }

    return false;
}

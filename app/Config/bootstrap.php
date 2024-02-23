<?php
/**
 * This file is loaded automatically by the app/webroot/index.php file after core.php
 *
 * This file should load/create any application wide configuration settings, such as
 * Caching, Logging, loading additional configuration files.
 *
 * You should also use this file to include any files that provide global functions/constants
 * that your application uses.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.10.8.2117
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

// Setup a 'default' cache configuration for use in the application.
Cache::config('default', array('engine' => 'File'));

/**
 * The settings below can be used to set additional paths to models, views and controllers.
 *
 * App::build(array(
 *     'Model'                     => array('/path/to/models/', '/next/path/to/models/'),
 *     'Model/Behavior'            => array('/path/to/behaviors/', '/next/path/to/behaviors/'),
 *     'Model/Datasource'          => array('/path/to/datasources/', '/next/path/to/datasources/'),
 *     'Model/Datasource/Database' => array('/path/to/databases/', '/next/path/to/database/'),
 *     'Model/Datasource/Session'  => array('/path/to/sessions/', '/next/path/to/sessions/'),
 *     'Controller'                => array('/path/to/controllers/', '/next/path/to/controllers/'),
 *     'Controller/Component'      => array('/path/to/components/', '/next/path/to/components/'),
 *     'Controller/Component/Auth' => array('/path/to/auths/', '/next/path/to/auths/'),
 *     'Controller/Component/Acl'  => array('/path/to/acls/', '/next/path/to/acls/'),
 *     'View'                      => array('/path/to/views/', '/next/path/to/views/'),
 *     'View/Helper'               => array('/path/to/helpers/', '/next/path/to/helpers/'),
 *     'Console'                   => array('/path/to/consoles/', '/next/path/to/consoles/'),
 *     'Console/Command'           => array('/path/to/commands/', '/next/path/to/commands/'),
 *     'Console/Command/Task'      => array('/path/to/tasks/', '/next/path/to/tasks/'),
 *     'Lib'                       => array('/path/to/libs/', '/next/path/to/libs/'),
 *     'Locale'                    => array('/path/to/locales/', '/next/path/to/locales/'),
 *     'Vendor'                    => array('/path/to/vendors/', '/next/path/to/vendors/'),
 *     'Plugin'                    => array('/path/to/plugins/', '/next/path/to/plugins/'),
 * ));
 *
 */

/**
 * Custom Inflector rules can be set to correctly pluralize or singularize table, model, controller names or whatever other
 * string is passed to the inflection functions
 *
 * Inflector::rules('singular', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 * Inflector::rules('plural', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 *
 */

/**
 * Plugins need to be loaded manually, you can either load them one by one or all of them in a single call
 * Uncomment one of the lines below, as you need. Make sure you read the documentation on CakePlugin to use more
 * advanced ways of loading plugins
 *
 * CakePlugin::loadAll(); // Loads all plugins at once
 * CakePlugin::load('DebugKit'); //Loads a single plugin named DebugKit
 *
 */

/**
 * To prefer app translation over plugin translation, you can set
 *
 * Configure::write('I18n.preferApp', true);
 */

/**
 * You can attach event listeners to the request lifecycle as Dispatcher Filter. By default CakePHP bundles two filters:
 *
 * - AssetDispatcher filter will serve your asset files (css, images, js, etc) from your themes and plugins
 * - CacheDispatcher filter will read the Cache.check configure variable and try to serve cached content generated from controllers
 *
 * Feel free to remove or add filters as you see fit for your application. A few examples:
 *
 * Configure::write('Dispatcher.filters', array(
 *      'MyCacheFilter', //  will use MyCacheFilter class from the Routing/Filter package in your app.
 *      'MyCacheFilter' => array('prefix' => 'my_cache_'), //  will use MyCacheFilter class from the Routing/Filter package in your app with settings array.
 *      'MyPlugin.MyFilter', // will use MyFilter class from the Routing/Filter package in MyPlugin plugin.
 *      array('callable' => $aFunction, 'on' => 'before', 'priority' => 9), // A valid PHP callback type to be called on beforeDispatch
 *      array('callable' => $anotherMethod, 'on' => 'after'), // A valid PHP callback type to be called on afterDispatch
 *
 * ));
 */
Configure::write('Dispatcher.filters', array(
    'AssetDispatcher',
    'CacheDispatcher'
));

/**
 * Configures default file logging options
 */
App::uses('CakeLog', 'Log');
CakeLog::config('debug', array(
    'engine' => 'File',
    'types' => array('notice', 'info', 'debug'),
    'file' => 'debug',
));
CakeLog::config('error', array(
    'engine' => 'File',
    'types' => array('warning', 'error', 'critical', 'alert', 'emergency'),
    'file' => 'error',
));

define('GOOGLE_URL', 'https://www.google.com/search?q=');
// define('BING_URL', 'https://www.bing.com/search?q=');
define('YOUTUBE_URL', 'https://www.youtube.com/results?search_query=');
define('API_KEY', 'AIzaSyBM-yfyBNQ4AgnJjGZMgpQ3DBlY1O8g6Go');

define('URL_PROXY', 'http://multiproxy.org/txt_all/proxy.txt');


Configure::write('LIST_BING_KEY', array(
    'qo4P91c/jPgrBy3AWR5yLIbmJKOPH11Dd2XGenvidMU=',
    'rXfVRzisNmqwQGNCIvxNKPo9tvTIQCeQRfuF9OLb8lY=',
    'Bf7SR4FCenAWONbPMp8GnaB2y0qxpqBaypBQtX1DHk4',
    'g9+FprsqsC84SpuX8s6vFcwO40EtPO6NuJBN+KV3YoQ',
    'p+R42Y6WtZ1TMVx6rJcSXktiRekxc950oIG3BAGpYVk',
    'T8WI64LPgvfOFb2I2csFiLNoWRKGzm02i6WfyN0rQLw',
    'YGJHk+4Zp3B0AusV/4v1j7A+GSziC38yCi3QiyfAhjo',
    'VmchzqJ37XJ8zYd2v5OQpRE8zjqnHXa0VrcPrqOouhQ',
    'DIl0X5hFn6YtSwLlxc4F8GJud3qt3ag0eU5lb1ARUf4',
    'LUO8VktLFNShf8dnKs+hcbXQ3VbMRN1K4jUtRVS40So',
    'IYegk7ME7aOyhpXGLri+Q5QKNxdR58ZAvVVzzrld6Ho',
    '4iS5q3fR5WqfGOQwGHke3vyWwvW3ocR2AHDwpb9Xslk',
    'sfkj+DVltTu5OHNAULiDRXq0bw3+UdA+xQGF96jQPes',
    'ZsGfZ8KoOl4D+nTP1nTMGgXAlXkdL3euHsHLyYfryFk',
    'F2kkTW7L+lFwIQvpdNc3/skhMTcQecs0754skqx7IwM',
    '+VP/bkVlKGn5fXCHTb1aNY+hzDaRo/3ufvzZOeY23CU',
    'hNzcDAZIdU0ZBsqpM2OYzPii8AmjXvpPYUTIovfJVak',
    'sJXKD4OsE6wxegLiLo9RTnUuJHwpfm56pnz2+93LLPA',
    'WoqHAIzr+T7y8yNFsmsuzL+S4hqnz01JnKM7ABZBdp4',
    'QGf8Jub3HblTSw1nutEeOKV0I0vmAHeFzBczGDkvjhw',
    'PR96a5bPx90tGMsWG4gQiRSFIQLIBLeVD3FsrV6eIBc',
    's+DPxunWTPGSbu3fXi4dkJr8SFZFvyB3BZaSitVtmsY',
    'KKaTyRXreQiA1hVW+VnfdNMh1JqnBIKob2C3RP5QokU',
    'S1aJu4ydaR2dQ571jj9dSzIJ5h+2zSWUG+ZMcBNkyRk'
));


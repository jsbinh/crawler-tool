<?php

/**
 * Licensed under The GPL V3 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @package       GoogleAPI.Controller
 * @license       http://opensource.org/licenses/GPL-3.0 GPL V3 License
 */

App::uses('Controller', 'Controller');
App::uses('AppController', 'Controller');

class GoogleAPIAppController extends AppController
{
	 public $components = array(
        'GoogleAPI.GoogleAPI' => array(
            'Service' => array(
                'YouTube'
            )
        )
    );

    public function index() {
        $yt = $this->GoogleAPI->Service['YouTube'];
        $results = $yt->videos->listVideos('snippet', array(
            'chart' => 'mostPopular'
        ));
        foreach ($results['items'] as $item) {
            echo $item['snippet']['title'] . "<br /> \n";
        }
    }
}

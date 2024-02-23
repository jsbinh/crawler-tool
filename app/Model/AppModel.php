<?php
/**
 * Application model for CakePHP.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
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
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {

    /**
    * search_google function
    * @author Binh Hoang
    * @since 2015.08.28
    **/
    public function search_google($file, $proxy, $size_proxy){
        ini_set('default_charset', 'UTF-8');
        $results = array();
        $pr_count = 0;
        $count = 0;
        while(!feof($file)){
            $text = fgetcsv($file);
            $url = "https://ajax.googleapis.com/ajax/services/search/web?v=1.0&q=".urlencode($text[0]).'&rsz=large&key='.API_KEY;

            $json = $this->changeProxy($url, @$proxy[$pr_count]);
            if(empty($json)){
                if($pr_count < $size_proxy)
                    $pr_count++;
                $json = $this->changeProxy($url, @$proxy[$pr_count]);
            }
            $data = json_decode($json, true);

            $count++;
            if($count == 50){
                sleep(1);
                $count = 0;
            }

            $out = '';
            if(!empty($data['responseData']['results'])){
                foreach ($data['responseData']['results'] as $val) {
                    $out .= @$val['content'].'.';
                }
            }

            if(empty($out)){
                $url = GOOGLE_URL.urlencode($text[0]).'&key='.API_KEY;
                if(file_get_contents($url)){
                    $contents = file_get_contents($url);
                }

                $contents = str_replace(array("\r\n", "\r", "\n"), "", $contents);
                $contents = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $contents);
                $contents = preg_replace('#<style(.*?)>(.*?)</style>#is', '', $contents);

                if (preg_match_all('/<span class=\"st\">(.*?)<\/span>/', $contents, $tmp)) {
                    if(!empty($tmp[0])){
                        foreach ($tmp[0] as $tmp_val) {
                            $out .= html_entity_decode(strip_tags($tmp_val)).'. ';
                        }
                    }
                }
            }

            $results[] = array(
                'text' => $text[0],
                'content' => $out
            );
        }
        return $results;
    }

    /**
    * search_bing function
    * @author Binh Hoang
    * @since 2015.08.28
    **/
    public function search_bing($file, $proxy, $size_proxy){
        ini_set('default_charset', 'UTF-8');
        $results = array();
        $acc_count = 0;
        $count = 0;
        $arr_account = Configure::read('LIST_BING_KEY');

        while(! feof($file)){
            $text = fgetcsv($file);
            // $account_key = $arr_account[$acc_count];
            $bing_url = 'https://api.datamarket.azure.com/Bing/Search/v1/Web?Query=%27';
            $sub_url = '%27&%24format=json';
            $json = $this->getBingApi($bing_url.urlencode($text[0]).$sub_url, $arr_account[$acc_count], @$proxy[$pr_count]);

            // DECODE THE JSON RESULT AND LOOP THROUGH IT
            $data = json_decode($json, true);

            if(!is_array($data)){
                if($acc_count < count($arr_account) - 1){
                    $acc_count++;
                    $json = $this->getBingApi($bing_url.urlencode($text[0]).$sub_url, $arr_account[$acc_count], @$proxy[$pr_count]);
                    $data = json_decode($json, true);
                }
            }
            $out_link = '';
            for($i = 0; $i < 10; $i++){
                if(!empty($data['d']['results'][$i]['Description']))
                    $out_link .= $data['d']['results'][$i]['Description'].'.';
            }

            $results[] = array(
                'text' => $text[0],
                'content' => $out_link
            );
        }
        return $results;
    }

    /**
    * search_youtube function
    * @author Binh Hoang
    * @since 2015.09.14
    **/
    public function search_youtube($file){
        ini_set('default_charset', 'UTF-8');
        App::import('Vendor', 'Google_Client', array('file'=>'youtube/src/Google_Client.php'));
        App::import('Vendor', 'autoload', array('file'=>'youtube/src/contrib/Google_YouTubeService.php'));

        while(! feof($file)){
            $text = fgetcsv($file);
            $DEVELOPER_KEY = 'AIzaSyBM-yfyBNQ4AgnJjGZMgpQ3DBlY1O8g6Go';

            $client = new Google_Client();
            $client->setDeveloperKey($DEVELOPER_KEY);
            $youtube = new Google_YoutubeService($client);

            $searchResponse = $youtube->search->listSearch('id,snippet', array(
                'q' => $text[0],
            ));

            $out = '';
            if(!empty($searchResponse['items'][0])){
                $out = array(
                    'id' => @$searchResponse['items'][0]['id']['videoId'],
                    'url' => 'https://www.youtube.com/watch?v='.@$searchResponse['items'][0]['id']['videoId'],
                    'title' => $searchResponse['items'][0]['snippet']['title'],
                    'uploader' => '',
                    'date' => $searchResponse['items'][0]['snippet']['publishedAt'],
                    'snippet' => $searchResponse['items'][0]['snippet']['description'],
                );
            }

            $results[] = array(
                'text' => $text[0],
                'content' => $out
            );
        }
        return $results;
    }

    public function getBingApi($url, $account_key, $proxy){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT,true);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_USERPWD, $account_key . ":" . $account_key);

        // if(!empty($proxy)){
        //     curl_setopt($ch, CURLOPT_PROXY, $proxy);
        //     curl_setopt($ch, CURLOPT_TIMEOUT, 240);
        //     curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 240);
        //     curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        //     curl_setopt($ch, CURLOPT_ENCODING, '');
        // }
        $json = curl_exec($ch);
        curl_close($ch);

        return $json;
    }

    public function changeProxy($url, $proxy){
        $options = array(
            CURLOPT_RETURNTRANSFER => true,     // return web page
            CURLOPT_HEADER         => false,    // don't return headers
            CURLOPT_FOLLOWLOCATION => true,     // follow redirects
            CURLOPT_ENCODING       => "",       // handle all encodings
            CURLOPT_USERAGENT      => "spider", // who am i
            CURLOPT_AUTOREFERER    => true,     // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 240,      // timeout on connect
            CURLOPT_TIMEOUT        => 240,      // timeout on response
            CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
            CURLOPT_SSL_VERIFYPEER => false,     // Disabled SSL Cert checks
        );

        if(!empty($proxy))
            $options[] = array(CURLOPT_PROXY => $proxy);

        $ch = curl_init($url);
        curl_setopt_array( $ch, $options );
        $data = curl_exec( $ch );
        curl_close( $ch );

        return $data;
    }

    // public function getContentWiki($out){
    //     $out_link = '';

    //     $contents_link = file_get_contents($out);

    //     $contents_link = str_replace(array("\r\n", "\r", "\n"), "", $contents_link);
    //     $contents_link = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $contents_link);
    //     $contents_link = preg_replace('#<style(.*?)>(.*?)</style>#is', '', $contents_link);

    //     if (preg_match('/<table class="infobox?(.*?)".*?<\/table>.*?<p>(.*?)<\/p><p>(.*?)<\/p>/', $contents_link, $tmp_link)) {
    //         $out_link = html_entity_decode(strip_tags($tmp_link[2]). strip_tags($tmp_link[3]));
    //     }

    //     return $out_link;
    // }

}

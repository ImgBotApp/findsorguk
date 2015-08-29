<?php

/**
 * Created by PhpStorm.
 * User: danielpett
 * Date: 29/08/15
 * Time: 08:04
 */
class Pas_Service_What3words
{

    const WHAT3WORDS_URI = 'http://api.what3words.com/';
    protected $_apiKey;
    protected $_language = 'en';

    /**
     * Convert 3 words or OneWord into position
     * Takes words either as string, or array of words
     * Returns array of [lat, long]
     */
    public function wordsToPosition($words)
    {
        if (is_array($words)) {
            $words = implode('.', $words);
        } elseif (!is_string($words)) {
            throw new Pas_Exception('Invalid words passed', 500);
        }
        $data = array('string' => $words);
        return self::requestWords('w3w', $data);

    }

    /**
     * Convert a position into 3 words
     * Takes position either as string, or array of 2 values
     * Returns array of [word1, word2, word3]
     */
    public function positionToWords($position)
    {
        if (is_array($position)) {
            $position = implode(',', $position);
        } elseif (!is_string($position)) {
            throw new Pas_Exception('Invalid position passed', 500);
        }
        $data = array('position' => $position);
        return self::requestWords('position', $data);

    }

    public function requestWords($url, $data)
    {
        $data['key'] = $this->getApiKey();
        $data['lang'] = $this->getLanguage();
        Zend_Debug::dump($data);
        $curl = new Pas_Curl();
        $curl->setUri(self::WHAT3WORDS_URI . $url);
        $curl->setConfig(array(
            'adapter' => 'Zend_Http_Client_Adapter_Curl',
            'curloptions' => array(
                CURLOPT_POST => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HEADER => false,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_LOW_SPEED_TIME => 1,
                CURLOPT_POST => count($data),
                CURLOPT_POSTFIELDS => http_build_query($data)
            ),
        ));
        $curl->getRequest();
        if($curl->getResponseCode() == 200){
            return $curl->getJson();
        } else {
            return false;
        }
    }

    /**
     * @return mixed
     */
    public function getApiKey()
    {
        return $this->_apiKey;
    }

    /**
     * @param mixed $apiKey
     * @return Pas_Service_What3words
     */
    public function setApiKey($apiKey)
    {
        $this->_apiKey = $apiKey;
        return $this;
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->_language;
    }

    /**
     * @param string $language
     * @return Pas_Service_What3words
     */
    public function setLanguage($language)
    {
        $this->_language = $language;
        return $this;
    }
}
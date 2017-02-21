<?php

/**
 * Codon PHP Framework
 *    www.nsslive.net/codon
 * Software License Agreement (BSD License)
 *
 * Copyright (c) 2008 Nabeel Shahzad, nsslive.net
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 * 2.  Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in the
 *    documentation and/or other materials provided with the distribution.
 * 3. The name of the author may not be used to endorse or promote products
 *    derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE AUTHOR ``AS IS'' AND ANY EXPRESS OR
 * IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES
 * OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.
 * IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT
 * NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF
 * THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @author Nabeel Shahzad
 * @copyright Copyright (c) 2008, Nabeel Shahzad
 * @link http://www.nsslive.net/codon
 * @license BSD License
 * @package codon_core
 */
class CodonWebService
{

    public $options = [];
    public $errors = [];

    protected $type = 'curl';
    protected $curl = null;
    protected $_separator = '&';
    protected $curl_exists = true;

    public function __construct()
    {
        if (!function_exists('curl_init')) {
            $this->curl_exists = false;
            $this->error('cURL not installed');
        }

        if (!$this->curl = curl_init()) {
            $this->error();
            $this->curl = null;
        }

        $this->setType('curl');
        $this->setOptions([
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_AUTOREFERER => true,
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_HEADER => false,
            CURLOPT_FOLLOWLOCATION => true,
        ]);
    }

    public function __destruct()
    {
        if ($this->curl) {
            curl_close($this->curl);
        }
    }

    /**
     * Internal error handler
     * @param string $txt Error text
     */
    protected function error($txt = '')
    {
        if ($txt != '') {
            $last = $txt;
        } else {
            $last = curl_error($this->curl) . ' (' . curl_errno($this->curl) . ')';
        }

        $this->errors[] = $last;
    }

    /**
     * Set the transfer type (curl or fopen). cURL is better
     * POST cannot be done by fopen, it will be ignored
     * @param string $type curl or fopen
     * @return null
     */
    public function setType($type = 'curl')
    {
        if ($type != 'curl' && $type != 'fopen') {
            $this->error('Invalid connection type');

            return false;
        }

        $this->type = $type;
    }

    /**
     * Set the curl options, that are different from the default
     * @param array $opt
     * @return null
     */
    public function setOptions($opt)
    {
        if (!$this->curl) {
            $this->error('No valid cURL session');

            return false;
        }

        curl_setopt_array($this->curl, $opt);
    }

    /**
     * Grab a URL, but use SSL. Returns the reponse
     *
     * @param string $url
     * @param array $params Associative array of key=value
     * @param string $type post or get (get by default)
     */
    public function getSSL($url, $params, $type = 'get')
    {
        // set SSL options and then go
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, 0);
        $this->get($url, $params, $type);
    }

    /**
     * Grab a URL, return the reponse
     * @param string $url
     * @param array $params Associative array of key=value
     * @return string
     */
    public function get($url, $params = [])
    {
        # Builds the parameters list
        if (is_array($params)) {
            $q_string = '';
            foreach ($params as $name => $value) {
                $q_string .= $name . '=' . urlencode($value) . $this->_separator;
            }

            $q_string = substr($q_string, 0, strlen($q_string) - 1);
            $url = $url . '?' . $q_string;
        }

        if (!$this->curl || $this->curl == null) {
            $this->error('cURL not installed or initialized!');

            return false;
        }

        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_URL, $url);
        if (($ret = curl_exec($this->curl)) === false) {
            $this->error();

            return false;
        }

        return $ret;
    }

    /**
     * Grab a URL, return the reponse, POST params
     * @param string $url
     * @param array $params Associative array of key=value
     * @return bool
     */
    public function post($url, $params = [])
    {
        if (!$this->curl) {
            $this->error('cURL not initialized');

            return false;
        }

        //encode our url data properly
        if (is_array($params)) {
            foreach ($params as $key => $value) {
                $cleaned_params[$key] = urlencode($value);
            }
        } else {
            $cleaned_params = urlencode($params);
        }

        curl_setopt($this->curl, CURLOPT_POST, 1);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $cleaned_params);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_URL, $url);
        if (($ret = curl_exec($this->curl)) === false) {
            $this->error();

            return false;
        }

        return $ret;
    }

    /**
     * Download a file from $url to the $tofile
     * @param string $url URL of file to download
     * @param string $tofile Path of file to download to
     * @return bool
     */
    public function download($url, $tofile)
    {
        if (!$this->curl || $this->curl == null) {
            return false;
        }

        if (($fp = fopen($tofile, 'wb')) == false) {
            $this->error('Error opening ' . $tofile);

            return false;
        }

        curl_setopt($this->curl, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_FILE, $fp);
        curl_setopt($this->curl, CURLOPT_URL, $url);

        if (($ret = curl_exec($this->curl)) === false) {
            $this->error();
            unlink($tofile);

            return false;
        }
    }
}

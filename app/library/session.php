<?php
/**
 * Created by PhpStorm.
 * User: attapon.th
 * Date: 2/2/2558
 * Time: 11:25
 */

namespace EThesis\Library;

use Phalcon\Session\Adapter\Files as SessionAdapter;

class Session
{

    const SESSION_NAME = 'EThesisUP';

    private $phalcon_session = FALSE;

    private $session_config;

    public function __construct()
    {
        $config = include __DIR__ . "/../../app/config/config.php";

        $this->session_config = $config['session'];

        $this->create_session();

    }

    private function create_session()
    {
        $now = date(DATE_ISO8601);
        $this->phalcon_session = new SessionAdapter(['uniqueId' => Session::SESSION_NAME]);
        $this->phalcon_session->start();
        $now = date(DATE_ISO8601);
        $this->phalcon_session->set('auth', true);
        $this->phalcon_session->set('user_ip', $this->get_userip());
        $this->phalcon_session->set('last_active', $now);
        $this->phalcon_session->has('user_agent') || $this->phalcon_session->set('user_agent', get_browser( null,true)[comment]);

    }

    public function init()
    {
        $deff_time = strtotime('now') - strtotime($this->phalcon_session->get('last_active'));

        if (!empty($this->session_config['lifetime']) && $deff_time > $this->session_config['lifetime']) {
            $this->destroy();
            return FALSE;
        } else if ($deff_time > $this->session_config['interval_refresh']) {
            $now = date(DATE_ISO8601);
            $this->phalcon_session->set('user_ip', $this->get_userip());
            $this->phalcon_session->set('last_active', $now);
        }
        return TRUE;

    }

    public function has($name = FALSE)
    {
        if ($name)
            return $this->phalcon_session->has($name);
        return FALSE;
    }

    public function get($name = 'all')
    {
        $name = strtolower($name);
        if ($name == '' || $name == 'all') {
            foreach ($this->phalcon_session->getIterator() as $key => $val) {
                if (strpos($key, Session::SESSION_NAME) !== FALSE) {
                    $key = str_replace(Session::SESSION_NAME, '', $key);
                    $item[$key] = $val;
                }
            }

        } else if ($this->phalcon_session->has($name)) {
            $item = $this->phalcon_session->get($name);
        } else {
            $item = FALSE;
        }
        return $item;
    }


    public function set($name, $value)
    {
        if (!$this->init()) {
            return FALSE;
        }

        $this->phalcon_session->set($name, $value);
        return TRUE;
    }


    public function muti_set(array $array_set)
    {

        if (is_array($array_set)) {
            foreach ($array_set as $key => $val) {
                $this->phalcon_session->set($key, $val);
            }
        }
        return TRUE;
    }


    public function destroy()
    {
        $this->phalcon_session->destroy();
    }


    public function get_userIP()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
        {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
        {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
}
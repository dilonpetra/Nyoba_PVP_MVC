<?php

class App
{
    protected $controller = 'Home';
    protected $method = 'index';
    protected $params = [];

    public function __construct()
    {
        $url = $this->parseURL();

        if ($url == NULL) {
            $url = [$this->controller];
        }

        //controler
        if (file_exists('../app/controllers/' . $url[0] . '.php')) {
            $this->controller = $url[0];
            unset($url[0]);
        }
        require_once '../app/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        //method
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        //param
        if (!empty($url)) {
            $this->params = array_values($url);
        }

        //jalankan controler dan method serta kriimkan parameter jika ada
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseURL()
    {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/'); //membersihkan / terakhir
            $url = filter_var($url, FILTER_SANITIZE_URL); //membersihkan url dari carakter aneh
            $url = explode('/', $url); //memecah berdasarkan /
            return $url;
        }
    }
}

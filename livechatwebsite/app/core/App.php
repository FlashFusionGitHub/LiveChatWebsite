<?php
class App {

    protected $controller = 'home';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {

        $this->prepareURL();

        if(!empty($_POST['friendName'])) {

            if(isset($_POST['btnSearch'])) {

                header("Location: /profile/user/" . trim($_POST['friendName']));
            }
        }

        if(file_exists(CONTROLLER . $this->controller . '.php')) {

            if(method_exists($this->controller, $this->method)) {

                $this->controller = new $this->controller;

                call_user_func_array([$this->controller, $this->method], $this->params);
            }
            else {

                $error = new Controller;

                $error->view('/error', ['error_title' => 'We can\'t find what your looking for', 'error_message' => 'Invalid path'])->render();
            }
        }
        else {

            $error = new Controller;

            $error->view('/error', ['error_title' => 'We can\'t find what your looking for', 'error_message' => 'Invalid path'])->render();
        }
    }

    protected function prepareURL() {

        $request = trim($_SERVER['REQUEST_URI'], '/');

        if(!empty($request)) {

            $url = explode('/', $request);

            $this->controller = isset($url[0]) ? $url[0] : 'home';
            $this->method = isset($url[1]) ? $url[1] : 'index';

            unset($url[0], $url[1]);

            $this->params = !empty($url) ? array_values($url) : [];
        }
    }
}
?>
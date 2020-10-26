<?php
class View {

    protected $view;
    protected $data;

    public function __construct($view, $data) {

        $this->view = $view;
        $this->data = $data;
    }

    public function render() {

        if(file_exists(VIEW . $this->view . '.phtml')) {

            require_once VIEW . 'header.phtml';

            include VIEW . $this->view . '.phtml';

            require_once VIEW . 'footer.phtml';
        }
    }
}
?>

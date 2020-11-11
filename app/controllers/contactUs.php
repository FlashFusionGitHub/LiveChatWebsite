<?php
class contactUs extends Controller {

    public function index() {

        if(isset($_POST['contact-us'])) {

            if(!empty($_POST['message']) && !empty($_POST['email'])) {

                $this->model('Contact');

                $this->model->createMessage($_POST['message'], $_POST['email']);

                header('Location: /contactUs/index/?message-sent=1', false);
            }
        }

        $this->view('/contactUs');

        $this->view->render();
    }
}
?>
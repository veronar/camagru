<?php

class ProfileController extends Controller {
	public function __construct($controller, $action) {
		parent::__construct($controller, $action);
		$this->load_model('Users');
		$this->load_model('Posts');
	}

	public function indexAction() {
		$user_id = currentUser()->user_id;
		$user_posts = $this->PostsModel->getUserPosts($user_id);
		$_SESSION['u_posts'] = $user_posts;

		if($_POST) {
			if(array_key_exists('delete', $_POST)) {
				$user_id = $this->UsersModel->currentLoggedInUser()->user_id;
				$post_id = $_POST['postid'];
				$this->PostsModel->delPost($post_id, $user_id);
				header("Refresh:0");
			}
		}

		$this->view->render('profile/index');
	}

	public function settingsAction() {
		if($_POST && $user = currentUser()){
			if ($_POST['mail'] == 'on'){
				$this->UsersModel->update($user->user_id, ['notify' => 1]);
			}
			else if ($_POST['mail'] == 'off') {
				$this->UsersModel->update($user->user_id, ['notify' => 0]);
			}
		}

		$this->view->render('profile/settings');
	}

	public function uploadAction() {
		$this->view->render('profile/upload');
	}

	public function changepassAction() {
		$validation = new Validate();
		$posted_values = ['oldpass' => '', 'newpass' => '',  'confirm' => ''];

		if($_POST) {
			//form validation
			$validation->check($_POST, [
				'oldpass' => [
					'display' => "Old Password",
					'required' => true
				],
				'password' => [
					'display' => 'New Password',
					'required' => true,
					'min' => 6,
					'lcase' => true
				],
				'confirm' => [
					'display' => 'Confirm Password',
					'required' => true,
					'matches' => 'password'
				]
			]);
			
			if($user = currentUser()) {
				if ($user && password_verify(Input::get('oldpass'), $user->password)) {
					$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
					$this->UsersModel->update($user->user_id, ['password' => $password]);
					Router::redirect('profile/settings');
				}
				else {
					$validation->addError(["Old password is incorrect", ""]);
				}
			}
			else {
				$validation->addError(["You are not authorised to perform this action", ""]);
			}
		}

		$this->view->post = $posted_values;
		$this->view->displayErrors = $validation->displayErrors();
		$this->view->render('profile/changepass');
	}

	public function changemailAction() {
		$validation = new Validate();
		$posted_values = ['email' => ''];

		if($_POST) {
			$posted_values = posted_values($_POST);
			$validation->check($posted_values, [
				'email' => [
					'display' => 'Email',
					'required' => true,
					'unique' => 'users',
					'max' => 150,
					'valid_email' => true
				]
			]);

			if($validation->passed()) {
				$user = currentUser();
				$this->UsersModel->update($user->user_id, ['email' => $posted_values['email']]);
				Router::redirect('profile/settings');
			}

		}

		$this->view->post = $posted_values;
		$this->view->displayErrors = $validation->displayErrors();
		$this->view->render('profile/changemail');
	}

	public function changeusernameAction() {
		$validation = new Validate();
		$posted_values = ['username' => ''];

		if($_POST) {
			$posted_values = posted_values($_POST);
			$validation->check($posted_values, [
				'username' => [
					'display' => 'Username',
					'required' => true,
					'unique' => 'users',
					'min' => 6,
					'max' => 150
				]
			]);

			if($validation->passed()) {
				$user = currentUser();
				$this->UsersModel->update($user->user_id, ['username' => $posted_values['username']]);
				Router::redirect('profile/settings');
			}

		}

		$this->view->post = $posted_values;
		$this->view->displayErrors = $validation->displayErrors();
		$this->view->render('profile/changeusername');
	}

	public function userAction() {
		
		$u = $_GET['user'];
		$user = $this->UsersModel->findById($u);
		$this->view->data['user'] = $user;

		$result = $this->PostsModel->getUserPosts($user->user_id);
		$this->view->data['posts'] = $result;

		$this->view->render('profile/user');
	}

}
?>

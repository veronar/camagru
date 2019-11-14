<?php

class HomeController extends Controller {
	public function __construct($controller, $action) {
		parent::__construct($controller, $action);
		$this->view->setLayout('default');
		$this->load_model('Users');
		$this->load_model('Posts');
		$this->load_model('Comments');
		$this->load_model('Likes');
	}

	public function indexAction() {
		// $db = DB::getInstance();
		//dnd($_SESSION);

		$users = $this->UsersModel->getUsers();
		$result = $this->PostsModel->getPosts();
		$comments = $this->CommentsModel->getComments();
		$_SESSION['comments'] = $comments;
		$_SESSION['posts'] = $result;
		$_SESSION['users'] = $users;

		$validation = new Validate();

		if($_POST){
			
			if(array_key_exists('delcomm', $_POST)){
				$user_id = $this->UsersModel->currentLoggedInUser()->user_id;
				$comm_id = $_POST['commid'];
				$this->CommentsModel->delComment($comm_id, $user_id);
				header("Refresh:0");
			}

			else if (array_key_exists('addcomm', $_POST)) {
				$validation->check($_POST, [
					'addcomm' => [
						'display' => "Comment",
						'required' => true
					]
				]);
				if($validation->passed()) {
					$user_id = $this->UsersModel->currentLoggedInUser()->user_id;
					$post_id = $_POST['postid'];
					$comment = htmlspecialchars($_POST['addcomm']);
					$this->CommentsModel->uploadComment($post_id, $user_id, $comment);
					header("Refresh:0");
				}
			}	
		}
		
		$this->view->render('home/index');
		

		// $contacts = $db->findFirst('users', [
		// 	'conditions' => 'user_id = ?',
		// 	'bind' => [1],
		// ]);
		// dnd($contacts);

		//$sql = "SELECT * FROM users";
		// $fields = [
		// 	'fname' => 'Antoinette',
		// 	'lname' => 'Parham',
		// 	'email' => 'anti@blah.com'
		// ];
		//$result = $db->query($sql);
		//dnd($result);
		// $contactsQ = $db->update('users', 7, $fields);
		//$contactsQ = $db->insert('users', $fields);
		//dnd($contactsQ);

		//$contactsQ = $db->delete('users', 7);

		// $contactsQ = $db->query("SELECT * FROM users ORDER BY lname, fname");
		// $contacts = $contactsQ->first();
		// dnd($contacts->fname);

		// $columns = $db->get_columns('users');
		// dnd($columns);

		
	}

	public function discoverAction() {
		
		$this->view->render('home/discover');
	}
}

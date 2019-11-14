
<?php $this->setSiteTitle(MENU_BRAND.' | '.ucwords(currentUser()->fname)); ?>
<?php $this->start('body'); ?>

<div class="page-header text-center">
  <h1>Sup <?=ucwords(currentUser()->fname)?><br>
  	<small>Your profile</small></h1>
</div>

<?php $u_posts = $_SESSION['u_posts']?>

	<div class="container row">
		<?php foreach ($u_posts as $post): ?>
			<div class="col-xs-6 col-md-4">
				<a href="<?=PROOT."post?p=".$post->post_id?>" class="thumbnail"> 
					<img src="<?=PROOT."/imgs/". ($post->img)?>" alt="">
				</a>
				<?php if(currentUser()->user_id == $post->user_id):?>
				<form action="" method="post" class="form">
					<div class="form-group text-center">
						<input type="hidden" id="postid" name="postid" value="<?=($post->post_id)?>">
						<input class="btn btn-warning btn-sm btn-block"type="submit" id="delete" name="delete" value="Delete">
					</div>
				</form>
				<hr>
				<?php endif ?>
			</div>
		<?php endforeach ?>
	</div>

<?php $this->end(); ?>

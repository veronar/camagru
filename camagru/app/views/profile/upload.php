<?php $this->setSiteTitle(MENU_BRAND.' | Create'); ?>
<?php $this->setSiteTitle('Create'); ?>
<?php $this->start('body'); ?>
<div class="page-header text-center">
  <h1>Sup <?=ucwords(currentUser()->fname)?><br>
  	<small>Lets create shit</small></h1>
</div>
<?php $this->end(); ?>

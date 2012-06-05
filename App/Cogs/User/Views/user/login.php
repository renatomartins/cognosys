<div class="span3"></div>
<div class="span6">
	<?=form(function ($f) { ?>
		<?=$f->text('username', array('placeholder' => 'Username'))?><br>
		<?=$f->password('password', array('placeholder' => 'Password'))?><br>
		<?=$f->submit('Login', array('class' => 'btn btn-primary'))?>
	<?php }, array('class' => 'well')); ?>
</div>

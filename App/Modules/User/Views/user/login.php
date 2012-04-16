<?=form(function ($f) { ?>
	<?=$f->text('username', array('placeholder' => 'Username'))?><br>
	<?=$f->password('password', array('placeholder' => 'Password'))?><br>
	<?=$f->submit('Login')?>
<?php }); ?>
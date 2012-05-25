User Info:
<ul>
	<li>ID: <?=$this->getUser()->id()?></li>
	<li>Name: <?=$this->getUser()->name()?></li>
	<li>Email: <?=$this->getUser()->email()?></li>
	<li>Login: <?=$this->getUser()->login()?></li>
	<li>Register Date: <?php var_dump($this->getUser()->register_date());?></li>
</ul>

User Info:
<ul>
	<li>ID: <?=$this->_user->id()?></li>
	<li>Name: <?=$this->_user->name()?></li>
	<li>Email: <?=$this->_user->email()?></li>
	<li>Login: <?=$this->_user->login()?></li>
	<li>Register Date: <?php var_dump($this->_user->register_date());?></li>
</ul>

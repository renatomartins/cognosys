<p>
	User Info:
	<ul>
		<li>ID: <?=$this->user->id?></li>
		<li>Name: <?=$this->user->name?></li>
		<li>Email: <?=$this->user->email?></li>
		<li>Login: <?=$this->user->login?></li>
		<li>Register Date: <?php var_dump($this->user->register_date);?></li>
		<li>Privileges: <?=$this->user->privileges?></li>
	</ul>
</p>

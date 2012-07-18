<?php if (isset($username)): ?>
<div class="btn-group pull-right">
  <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
    <i class="icon-user"></i> <?=$username?>
    <span class="caret"></span>
  </a>
  <ul class="dropdown-menu">
    <li><a href="#/user/settings">Settings</a></li>
    <li class="divider"></li>
    <li><a href="/user/logout">Sign Out</a></li>
  </ul>
</div>
<?php else: ?>
<div class="btn-group pull-right">
  <a class="btn" href="/user/login">
    <i class="icon-user"></i> Login
  </a>
</div>
<?php endif; ?>

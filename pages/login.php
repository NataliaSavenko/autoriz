<h1>Login page</h1> 

<?php Message::show() ?>

<form name="/login"  method="post">
<label >ВХІД</label><br>

<div>
<label>Email:</label>
<input type="email" placeholder="Введіть email" name="email" required> <br>

<label>Пароль:</label>
<input type="text" placeholder="Введіть пароль" name="password" required> <br>

<button class="btn btn-primary mt-3" name="action" value="logins">Ввійти</button>
</div>

</form>
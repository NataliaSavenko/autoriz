<h1>Registration page</h1>

<?php Message::show() ?>

<form name="/registration"  method="post">
<label >РЕЄСТРАЦІЯ</label><br>
<div>
<label>Email:</label>
<input type="email" placeholder="Введіть email" name="email" required> <br>

<label>Пароль:</label>
<input type="text" placeholder="Введіть пароль" name="password" required> <br>

<label>Повтор пароля:</label>
<input type="text" placeholder="Повторіть пароль" name="repeatPassword" required> <br>


<button class="btn btn-primary mt-3" name="action" value="registrations">Зареєструватись</button>
</div>


</form>



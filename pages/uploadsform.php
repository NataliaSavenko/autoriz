<h1>UploadsForm page</h1> 

<?php Message::show() ?>

<form name="/uploadsform"  method="post" enctype="multipart/form-data">


<div>
<label>Назва папки куди завантажити файл:</label>
<input type="text" placeholder="Введіть назву" name="dir" required> <br>

<label>Виберіть файл:</label>

<input type="file" name="images[]" /><br />
<input type="file" name="images[]" /><br />
<input type="file" name="images[]" /><br />
<button class="btn btn-primary mt-3" name="action" value="uploadsforms">Завантажити</button>
</div>

</form>
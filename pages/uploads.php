<h1>Uploads</h1>

<?php Message::show() ?>
<form action="/uploads" method="post" enctype="multipart/form-data">
  <input type="file" name="file">
  <button class="btn btn-primary" name="action" value="uploadImage">Send</button>
</form>


<!-- читанная файлів з дерикторій-->
<?php
 $files = scandir('./uploaded');
  $files = array_diff($files, ['.', '..']);
  foreach($files as $file){
    if(!is_dir('./uploaded/' . $file)){
      echo "<img src='./uploaded/$file'>";
      echo "<a href=\"./uploaded/$file\"><img src=\"./uploaded/small/$file\" alt=\"\"></a>";
    }
  } 

/* $dir = opendir('./uploaded');
while($file = readdir($dir) ){
  echo $file . '<br>';
}
closedir($dir); */

// $files = glob('./uploaded/*', GLOB_ONLYDIR);
$files = glob('./uploaded/*.{jpg,jpeg,png,gif,webp,avif}', GLOB_BRACE);
foreach($files as $file){
  extract(pathinfo($file));
  echo "<a href=\"$file\"><img src=\"$dirname/small/$basename\" alt=\"\"></a>";
}
dump($files);


/* 

<a href="big"><img src="small" alt=""></a>
<a href="big"><img src="small" alt=""></a>
<a href="big"><img src="small" alt=""></a>


*/
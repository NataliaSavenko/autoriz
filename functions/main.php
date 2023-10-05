<?php
require_once __DIR__ . "/helper.php";
require_once __DIR__ . "/Message.php";
require_once __DIR__ . "/OldInputs.php";

session_start();

//ini_set('post_max_size', '500M');
//ini_set('upload_max_filesize', '500M');
//ini_set('error_reporting', E_ALL); // включаємо вивід помилок php
//echo phpinfo();

$action = $_POST['action'] ?? null; 

if(!empty($action)){
  $action(); 
}


function sendEmail() {
  $name = $_POST['name'] ?? '';
  $email = $_POST['email'] ?? '';
  $message = $_POST['message'] ?? '';

  if (empty($name) || empty($email) || empty($message)) {
    Message::set('All fields are required', 'danger') ;
    OldInputs::set($_POST);
  } 
  else 
  {
    mail('kudriashova.ag@gmail.com', 'Mail from site', "$name, $email, $message");
    Message::set('Thank!');
  }

  redirect('contacts');
}






function uploadsforms(){
  
   
  $filename=$_POST['dir'] ??'';

  if (!is_dir($_POST["dir"])) 
  {
   
   $mode = '0755';               // - права на создаваемую папку.
   $recursive = true;            // - несуществующие папки будут воссозданы.
   mkdir($_POST["dir"], $mode, $recursive);
     
  }
     else{
      Message::set('Папка уже существует', 'danger') ;
     }
if($_FILES)
{
    foreach ($_FILES["images"]["error"] as $key => $error) {
        if ($error == UPLOAD_ERR_OK) {
            $tmp_name = $_FILES["images"]["tmp_name"][$key];
            $name = $_FILES["images"]["name"][$key];

            $fName = uniqid() . '_' . session_id() . '.' . end(explode('.', $name));
 
           move_uploaded_file($tmp_name, "./$filename/" . $fName);
        }
    }
    echo "Файлы загружены";
}
}






function uploadImage(){
  extract($_FILES['file']);

  if($error === 4){
    Message::set('File is required', 'danger');
    redirect('uploads');
  }
  if ($error !== 0) {
    Message::set('File is not uploaded', 'danger');
    redirect('uploads');
  }

  $allowsTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/avif'];

  if(!in_array($type, $allowsTypes)){
    Message::set('File is not image', 'danger');
    redirect('uploads');
  }

  $fName = uniqid() . '_' . session_id() . '.' . end(explode('.', $name));
  
  move_uploaded_file($tmp_name, './uploaded/' . $fName);



  resizeImage('./uploaded/' . $fName, 150, true);
  resizeImage('./uploaded/' . $fName, 300);



  Message::set('File is uploaded');
  redirect('uploads');
}

function resizeImage(string $path, int $size = 200, bool $crop = false): void
{
  extract(pathinfo($path));
  $extension = strtolower($extension) === 'jpg' ? 'jpeg' : strtolower($extension);

  $functionCreate = 'imagecreatefrom' . $extension;
  $src = $functionCreate($path);

  list($src_width, $src_height) = getimagesize($path);

  if ($crop) {
    // жорстка обрізка (квадрат)
    $dest = imagecreatetruecolor($size, $size);
    if ($src_width > $src_height) {
      imagecopyresized($dest, $src, 0, 0, $src_width / 2 - $src_height / 2, 0, $size, $size, $src_height, $src_height);
    } else {
      imagecopyresized($dest, $src, 0, 0, 0, $src_height / 2 - $src_width / 2, $size, $size, $src_width, $src_width);
    }
  }
  else{
    // пропорційне зменшення
    $dest_width = $size;
    $dest_height = $size * $src_height / $src_width;

    $dest = imagecreatetruecolor($dest_width, $dest_height);
    imagecopyresized($dest, $src, 0, 0, 0, 0,$dest_width, $dest_height, $src_width, $src_height);
  }

  $functionSave = 'image' . $extension;
  $dir = $crop ? 'small' : 'medium';
  if($extension === 'jpg')
    $functionSave($dest, "$dirname/$dir/$basename", 100);
  else
    $functionSave($dest, "$dirname/$dir/$basename");
}




/*function registrations()
{

  
 $email=$_POST['email'] ?? '';
$password=$_POST['password']?? '';
$repeatPassword=$_POST['repeatPassword']?? '';

if(empty($email)||empty($password)||empty($repeatPassword))
{
   
  Message::set('Помилка. Не всі поля заповнені', 'danger') ;
 
}



elseif($password!==$repeatPassword)
{

  Message::set("Помилка. Паролі не співпадають",'danger');
  
}
else
{

  Message::set("Раді Вас вітати");
   
    setcookie($email,$password,time() + 3600);
    header('Location: /registration');
    exit;

    
}

redirect('registration');
}*/


function registrations()
{

  
 $email=$_POST['email'] ?? '';
$password=$_POST['password']?? '';
$repeatPassword=$_POST['repeatPassword']?? '';

if(empty($email)||empty($password)||empty($repeatPassword))
{
   
  Message::set('Помилка. Не всі поля заповнені', 'danger') ;
 
}

elseif($password!==$repeatPassword)
{

  Message::set("Помилка. Паролі не співпадають",'danger');
  
}
else
{
  
 
  $filename = 'registrationsFile.txt';
$content = json_decode(file_get_contents($filename));


$keyemail = array_column($content, 'email'); 

for($i=0;$i<count($keyemail);$i++)
{

    if($keyemail[$i]===$_POST['email'])
    {
      Message::set("Помилка. Такий користувач вже існує",'danger');
      redirect('registration');
    }

}
  

   
  Message::set("Раді Вас вітати");
  $_SESSION['user'] = $email;
  $registr=file_exists('registrationsFile.txt') ? json_decode(file_get_contents('registrationsFile.txt')): [];
  $registr[]=compact('email','password');
  
  $file=fopen('registrationsFile.txt', 'w');
  fwrite($file,json_encode($registr));
  fclose($file);
  
  header('Location: /registration');
  exit;
    
    
}

redirect('registration');
}




function logins()
{

  $email=$_POST['email'] ?? '';
  $password=$_POST['password'] ?? '';
  
  
  if(empty($email) || empty($password) )
  {
    Message::set("Помилка. Не всі поля заповнені");
       
  }
  
  else
  {

    $filename = 'registrationsFile.txt';
    $content = json_decode(file_get_contents($filename));
    
    
    $keyemail = array_column($content, 'email'); 
    $keypassword = array_column($content, 'password'); 
    $nam=0;
    for($i=0;$i<count($keyemail);$i++)
    {
    
        if($keyemail[$i]!==$_POST['email'])
        {
          Message::set("Помилка. Такого користувача не знайдено");
    
          redirect('login');
        }
        elseif ($keyemail[$i]===$_POST['email'] && $keypassword[$i]!==$_POST['password'])
        {

          Message::set("Помилка. Пароль введено не вірно");
          redirect('login');
        }
        else
        {
         
            Message::set('Раді Вас вітати');
            
          
          
          header('Location: /contacts');
            exit;
          

        }
    
    }

    
  }
    
 }





/*function logins()
{

  $email=$_POST['email'] ?? '';
  $password=$_POST['password'] ?? '';
  
  
  if(empty($email) || empty($password) )
  {
    Message::set("Помилка. Не всі поля заповнені");
   
    
  }
  
  else
  {
    $passwordFromCookie=htmlspecialchars($_COOKIE['email'] ?? '');
   
    

    if(empty($passwordFromCookie))
  {
       
  Message::set("Помилка. Такого користувача не знайдено");
    
  }




  elseif($passwordFromCookie!==$password)
  {
    
    Message::set("Помилка. Email або пароль введено не вірно");
    
  }  
      
      else
      {
        Message::set('Раді Вас вітати');
           
      
      
      header('Location: /contacts');
        exit;
      }
  }
    
 }*/




 function sendReview()
 {

  $name=$_POST['name'] ?? '';
  $review=$_POST['review'] ?? '';
  $time=time();

  if(empty($name) || empty($review))
  {
    Message::set('All fields requared','denger');
    redirect('reviews');
  }

  $reviews=file_exists('reviews.txt') ? json_decode(file_get_contents('reviews.txt')): [];
  $reviews[]=compact('name','review','time');
  
  $file=fopen('reviews.txt', 'w');
  fwrite($file,json_encode($reviews));
  fclose($file);

  redirect('reviews');

 }

 function showReviwes()
 {
  $reviews=file_exists('reviews.txt') ? array_reverse(json_decode(file_get_contents('reviews.txt'))): [];

  if(count($reviews)===0)
  {
echo 'No reviwes';
  }
  else
  {

$perPage=3;
//$page=ceil(count($reviews)/$perPage);
$reviews=array_chunk($reviews,$perPage);
$currentPage=$_GET['p'] ?? 1;



//dump(($reviews));
foreach($reviews[$currentPage-1] as $rev):
  
echo "<div class='mt-3 border p-3'>
<strong>$rev->name (" . date('d.m.Y.H:i', $rev->time) .')'."</strong>
<div> $rev->review</div>
</div>";

endforeach;
  
echo "<nav";
echo "<ul class='pagination'>";
for($i=1; $i<=count($reviews);$i++)
{
  echo "<li class='page-item ".($currentPage==$i ?"active": "" )."'><a href='/reviews?p=$i' class='page-link'>$i</a></li>";

}

echo "</ul>";

echo "</nav";


}
}

function logout()
{

  unset($_SESSION['user']); 
  redirect('/');

}
 



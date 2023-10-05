<h1>Reviews</h1>

<?php Message::show() ?>

<form name="/reviews"  method="post">
    <div class="form-group">
    <label>Your name:</label>
    <input type="text" name="name" class="form-control">
    </div>

    <div class="form-group mt-3">
    <label>Your Reviews:</label>
    <textarea type="text" name="review" class="form-control"></textarea>
    </div>

   <!-- <img src="./pages/img/captcha.php" alt="">-->

    <button class="btn btn primary mt-3"   type="submit" name="action" value="sendReview"  >send</button>

</form>
    

<?php
showReviwes();

?>
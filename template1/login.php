<?php 
require_once ('../incs-template1/config.php');
include_once ('../incs-template1/cookie-session.php'); 
?>
<?php
if(isset($_SESSION['user_id'])){
	header("Location:./");
	exit();
}
?>
<?php
$login_array = array();
if(isset($_POST['login']) AND $_SERVER['REQUEST_METHOD']== "POST" ){

if(filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)){
	$em = mysqli_real_escape_string($connect,$_POST['email']);
}else{
	$login_array['email_error'] = "Please enter a valid email address";
}

if(preg_match('/^.{6,255}$/i',$_POST['password'])){
	$pw =  mysqli_real_escape_string($connect,$_POST['password']);
}else{
	$login_array['password_error'] = "Please enter a password that is 6 characters and above";
}



if(empty($login_array)){
		$vl =  mysqli_query($connect, "SELECT * FROM users WHERE(email='".$em."' AND password='".$pw."' AND active='1')") or die(db_conn_error);
		if(mysqli_num_rows($vl)== 1){
		 $row = mysqli_fetch_array($vl,MYSQLI_NUM);
		 
		 $value = md5(uniqid(rand(), true));
		 $query_confirm_sessions = mysqli_query ($connect, "SELECT cookies_session FROM users WHERE email='".$em."' AND active = '1'") or die(db_conn_error);
	$cookie_value_if_empty = mysqli_fetch_array($query_confirm_sessions);
	
	if (empty($cookie_value_if_empty[0])){
	mysqli_query($connect,"UPDATE users SET cookies_session = '".$value."' WHERE email='".$em."' AND active = '1'") or die(db_conn_error);		
	setcookie("remember_me", $value, time() + 432000);	//session time out is 5 days
	
	}else if(!empty($cookie_value_if_empty[0])){
	
	setcookie("remember_me", $cookie_value_if_empty[0], time() + 432000);	
	}
	
		 
		 $_SESSION['user_id'] = $row[0];
		$_SESSION['email'] = $row[2];
		 
		header("Location:./"); exit;
		}else{ 
		 $login_array['password_mismatch']= "Email and password do not match";}
}
}



?>



<?php include ('../incs-template1/header.php'); ?>

<div class="ps-page--my-account">
    <div class="ps-breadcrumb">
        <div class="container">
            <ul class="breadcrumb">
                <li><a href="<?='./';?>">Home</a></li>
                <li>Login</li>
            </ul>
        </div>
    </div>
    <div class="ps-my-account">
        <div class="container">
            <form class="ps-form--account ps-tab-root" action="" method="POST">
                <ul class="ps-tab-list">
                    <li class="active"><a href="#sign-in">Admin Login</a></li>
                    <!-- <li><a href="#register">Register</a></li> -->
                </ul>
                <div class="ps-tabs">
                    <div class="ps-tab active" id="sign-in">
                        <div class="ps-form__content">
                            <h5>Log in to your account </h5>
                            <div class="form-group">
                            <?php 
                            if(array_key_exists('email-error', $login_array)){echo '<p class="text-danger">'.$login_array['email-error'].'</p>';}
                            
                           
                            if(array_key_exists('password_mismatch', $login_array)){echo '<p class="text-danger">'.$login_array['password_mismatch'].'</p>';}
                            
                            ?>  
                                <input class="form-control" type="text" placeholder="Email address" name="email" value="<?php if(isset($_POST['email'])){echo $_POST['email'] ;} ?>">
                            </div>
                           <?php if(array_key_exists('password_error', $login_array)){echo '<p class="text-danger">'.$login_array['password_error'].'</p>';}?>

                            <div class="form-group form-forgot">
                                <input class="form-control" type="password" name="password" placeholder="Password" value="<?php if(isset($_POST['password'])){echo '';} ?>"><!--<a href="">Forgot?</a>-->
                            </div>
                            <!-- <div class="form-group">
                                <div class="ps-checkbox">
                                    <input class="form-control" type="checkbox" id="remember-me" name="remember-me" />
                                    <label for="remember-me">Rememeber me</label>
                                </div>
                            </div> -->
                            <div class="form-group submtit">
                                <button class="ps-btn ps-btn--fullwidth" type="submit" name="login">Login</button>
                            </div>
                        </div>
                         <div class="ps-form__footer">
                            <p></p>
                            <ul class="ps-list--social">
                                <!-- <li><a class="facebook" href="#"><i class="fa fa-facebook"></i></a></li>
                                <li><a class="google" href="#"><i class="fa fa-google-plus"></i></a></li>
                                <li><a class="twitter" href="#"><i class="fa fa-twitter"></i></a></li>
                                <li><a class="instagram" href="#"><i class="fa fa-instagram"></i></a></li> -->
                            </ul>
                        </div> 
                    </div>
                    <!-- <div class="ps-tab" id="register">
                        <div class="ps-form__content">
                            <h5>Register An Account</h5>
                            <div class="form-group">
                                <input class="form-control" type="text" placeholder="Username or email address">
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="text" placeholder="Password">
                            </div>
                            <div class="form-group submtit">
                                <button class="ps-btn ps-btn--fullwidth">Login</button>
                            </div>
                        </div>
                        <div class="ps-form__footer">
                            <p>Connect with:</p>
                            <ul class="ps-list--social">
                                <li><a class="facebook" href="#"><i class="fa fa-facebook"></i></a></li>
                                <li><a class="google" href="#"><i class="fa fa-google-plus"></i></a></li>
                                <li><a class="twitter" href="#"><i class="fa fa-twitter"></i></a></li>
                                <li><a class="instagram" href="#"><i class="fa fa-instagram"></i></a></li>
                            </ul>
                        </div>
                    </div> -->
                </div>
            </form>
        </div>
    </div>
    
</div>





<?php include ('../incs-template1/footer.php'); ?>
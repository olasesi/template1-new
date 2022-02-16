<?php 
require_once ('../incs-template1/config.php'); 
include_once ('../incs-template1/cookie-session.php'); 


if(!isset($_SESSION['user_id'])){
	header("Location:./");
	exit();

}


$errors = array();
if ($_SERVER['REQUEST_METHOD'] == 'POST' AND isset($_POST['submit'])){
	 
     if (preg_match ('/^[a-zA-Z]{3,20}$/i', trim($_POST['firstname']))) {		//only 20 characters are allowed to be inputted
         $firstname = mysqli_real_escape_string ($connect, trim($_POST['firstname']));
     } else {
         $errors['firstname'] = 'Please enter firstname.';
     } 
      
  
     if (preg_match ('/^[a-zA-Z]{3,20}$/i', trim($_POST['surname']))) {		//only 20 characters are allowed to be inputted
         $surname = mysqli_real_escape_string ($connect, trim($_POST['surname']));
     } else {
         $errors['surname'] = 'Please enter surname.';
     } 
 
     if ($_POST['age'] == "Enter age") {
         $errors['age'] = 'Please select student age';
     } else{
     $age = $_POST['age'];
     }
     
     if ($_POST['gender'] == "Choose gender") {
         $errors['gender'] = 'Please select gender';
     } else{
     $gender = $_POST['gender'];
     }
     
   if ($_POST['pri_class'] == "Choose school class") {
         $errors['pri_class'] = 'Please select school class';
     } else{
     $pri_class = $_POST['pri_class'];
     }
 
 
 
     if(preg_match('/^(0)[0-9]{10}$/i',$_POST['phone'])){
         $phone =  mysqli_real_escape_string($connect,$_POST['phone']);
     }else{
         $errors['phone'] = "Enter a valid phone number";
     }
     
     
     if (preg_match ('/^.{3,300}+$/i', trim($_POST['address']))) {		
         $address = mysqli_real_escape_string ($connect, trim($_POST['address']));
         } else {
         $errors['address'] = 'Please enter a invalid address';
         }
         
       
     
    
     if (is_uploaded_file($_FILES['img']['tmp_name']) AND $_FILES['img']['error'] == UPLOAD_ERR_OK){ 
         
             if($_FILES['img']['size'] > 2097152){ 		//conditions for the file size 2MB
                 $errors['editfile_size']="File size is too big. Max file size 2MB";
             }
         
             $editallowed_extensions = array('jpeg', '.png', '.jpg', '.JPG', 'JPEG', '.PNG');		
             $editallowed_mime = array('image/jpeg', 'image/png', 'image/pjpeg', 'image/JPG', 'image/X-PNG', 'image/PNG', 'image/x-png');
             $editimage_info = getimagesize($_FILES['img']['tmp_name']);
             $ext = substr($_FILES['img']['name'], -4);
             
             
             
             
             if (!in_array($_FILES['img']['type'], $editallowed_mime) || !in_array($editimage_info['mime'], $editallowed_mime) || !in_array($ext, $editallowed_extensions)){
                 $errors['wrong_upload'] = "Please choose correct file type. JPG or PNG";
                 
             }
             
         }else{
         $errors['upload_image'] = 'Please upload photo';	
         
         }
    
 
 
     //now to edit the product	
     if (empty($errors)){
 
       
         $new_name= (string) sha1($_FILES['img']['name'] . uniqid('',true));
             $new_name .= ((substr($ext, 0, 1) != '.') ? ".{$ext}" : $ext);
             $dest = "students/".$new_name;
             
             if (move_uploaded_file($_FILES['img']['tmp_name'], $dest)) {
             
             $_SESSION['images']['new_name'] = $new_name;
             $_SESSION['images']['file_name'] = $_FILES['img']['name'];
             
       $query_term_session = mysqli_query($connect, "SELECT school_session FROM term_start_end ORDER BY term_start_end_id  DESC LIMIT 1") or die(db_conn_error);
 while($loop_term_session=mysqli_fetch_array($query_term_session)){
  $current_session_term = $loop_term_session['school_session'];
 }
 
 
 mysqli_query($connect, "UPDATE primary_school_students SET pri_active='1', pri_admit = '1', pri_year = '".$current_session_term."', pri_firstname = '".$firstname."', pri_surname = '".$surname."', pri_age = '".$age."', pri_sex = '".$gender."', pri_class_id = '".$pri_class."', pri_photo = '".$new_name."', pri_address= '".$address."' WHERE primary_id = '".mysqli_real_escape_string ($connect, $_GET['id'])."' AND pri_admit = '0' AND pri_active_email = '1' AND pri_paid = '1'") or die(db_conn_error);
             if (mysqli_affected_rows($connect) == 1) {
             
             $_POST = array();		
             $_FILES = array();
                 
             unset($_FILES['img'], $_SESSION['images']);
             header('Location:'.GEN_WEBSITE.'/admin/search-paid.php?confirm='.$firstname);
             exit();
            
             
   }
 
 } else {
             trigger_error('The file could not be moved.');
             $errors['not_moved'] = "The file could not be moved.";
             unlink ($_FILES['img']['tmp_name']);
             }	
 
 } 
 
 
  }














 include ('../incs-template1/header.php'); ?>











<header class="header header--mobile" data-sticky="true">
        <div class="header__top">
            <div class="header__left">
                <p>Welcome to Martfury Online Shopping Store !</p>
            </div>
            <div class="header__right">
                <ul class="navigation__extra">
                    <li><a href="#">Sell on Martfury</a></li>
                    <li><a href="#">Tract your order</a></li>
                    <li>
                        <div class="ps-dropdown"><a href="#">US Dollar</a>
                            <ul class="ps-dropdown-menu">
                                <li><a href="#">Us Dollar</a></li>
                                <li><a href="#">Euro</a></li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <div class="ps-dropdown language">
                            <a href="#"><img src="img/flag/en.png" alt="" />English</a>
                            <ul class="ps-dropdown-menu">
                                <li>
                                    <a href="#"><img src="img/flag/germany.png" alt="" /> Germany</a>
                                </li>
                                <li>
                                    <a href="#"><img src="img/flag/fr.png" alt="" /> France</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="navigation--mobile">
            <div class="navigation__left">
                <a class="ps-logo" href="index.html"><img src="img/logo_light.png" alt="" /></a>
            </div>
            <div class="navigation__right">
                <div class="header__actions">
                    <div class="ps-cart--mini"><a class="header__extra" href="#"><i class="icon-bag2"></i><span><i>0</i></span></a>
                        <div class="ps-cart__content">
                            <div class="ps-cart__items">
                                <div class="ps-product--cart-mobile">
                                    <div class="ps-product__thumbnail">
                                        <a href="#"><img src="img/products/clothing/7.jpg" alt="" /></a>
                                    </div>
                                    <div class="ps-product__content"><a class="ps-product__remove" href="#"><i class="icon-cross"></i></a><a href="product-default.html">MVMTH Classical Leather Watch In Black</a>
                                        <p><strong>Sold by:</strong> YOUNG SHOP</p><small>1 x $59.99</small>
                                    </div>
                                </div>
                                <div class="ps-product--cart-mobile">
                                    <div class="ps-product__thumbnail">
                                        <a href="#"><img src="img/products/clothing/5.jpg" alt="" /></a>
                                    </div>
                                    <div class="ps-product__content"><a class="ps-product__remove" href="#"><i class="icon-cross"></i></a><a href="product-default.html">Sleeve Linen Blend Caro Pane Shirt</a>
                                        <p><strong>Sold by:</strong> YOUNG SHOP</p><small>1 x $59.99</small>
                                    </div>
                                </div>
                            </div>
                            <div class="ps-cart__footer">
                                <h3>Sub Total:<strong>$59.99</strong></h3>
                                <figure><a class="ps-btn" href="shopping-cart.html">View Cart</a><a class="ps-btn" href="checkout.html">Checkout</a></figure>
                            </div>
                        </div>
                    </div>
                    <div class="ps-block--user-header">
                        <div class="ps-block__left"><a href="my-account.html"><i class="icon-user"></i></a></div>
                        <div class="ps-block__right"><a href="my-account.html">Login</a><a href="my-account.html">Register</a></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="ps-search--mobile">
            <form class="ps-form--search-mobile" action="index.html" method="get">
                <div class="form-group--nest">
                    <input class="form-control" type="text" placeholder="Search something..." />
                    <button><i class="icon-magnifier"></i></button>
                </div>
            </form>
        </div>
    </header>


<main class="ps-page--my-account">
        <div class="ps-breadcrumb">
            <div class="container">
                <ul class="breadcrumb">
                    <li><a href="index.html">Home</a></li>
                    <li>User Information</li>
                </ul>
            </div>
        </div>
        <section class="ps-section--account">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="ps-section__left">
                            <aside class="ps-widget--account-dashboard">
                                <div class="ps-widget__header"><img src="img/users/3.jpg" alt="" />
                                    <figure>
                                        <figcaption>Hello</figcaption>
                                        <p><a href="#">username@gmail.com</a></p>
                                    </figure>
                                </div>
                                <div class="ps-widget__content">
                                    <ul>
                                        <li class="active"><a href="#"><i class="icon-user"></i> Account Information</a></li>
                                        <li><a href="#"><i class="icon-alarm-ringing"></i> Notifications</a></li>
                                        <li><a href="#"><i class="icon-papers"></i> Invoices</a></li>
                                        <li><a href="#"><i class="icon-map-marker"></i> Address</a></li>
                                        <li><a href="#"><i class="icon-store"></i> Recent Viewed Product</a></li>
                                        <li><a href="#"><i class="icon-heart"></i> Wishlist</a></li>
                                        <li><a href="#"><i class="icon-power-switch"></i>Logout</a></li>
                                    </ul>
                                </div>
                            </aside>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="ps-section__right">
                            <form class="ps-form--account-setting" action="" method="POST" enctype="multipart/form-data">
                                <div class="ps-form__header">
                                    <h3> Slider Banners</h3>
                                </div>
                                <div class="ps-form__content">
                                    <!-- <div class="form-group">
                                        <label>Name</label>
                                        <input class="form-control" type="text" placeholder="Please enter your name...">
                                    </div> -->
                                    <div class="row">
                                        <!-- <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Phone Number</label>
                                                <input class="form-control" type="text" placeholder="Please enter phone number...">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Email</label>
                                                <input class="form-control" type="text" placeholder="Please enter your email...">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Birthday</label>
                                                <input class="form-control" type="text" placeholder="Please enter your birthday...">
                                            </div>
                                        </div> -->
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Slider banner number</label>
                                                <select class="form-control" name="slider-banner">
                                                    <option value="1">Slider banner 1</option>
                                                    <option value="2">Slider banner 2</option>
                                                    <option value="3">Slider banner 3</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Upload</label>
                                                <input class="form-control" type="file" placeholder="Upload slider">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group submit">
                                    <button class="ps-btn" type="submit" name="submit">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <div class="ps-newsletter">
            <div class="ps-container">
                <form class="ps-form--newsletter" action="do_action" method="post">
                    <div class="row">
                        <div class="col-xl-5 col-lg-12 col-md-12 col-sm-12 col-12 ">
                            <div class="ps-form__left">
                                <h3>Newsletter</h3>
                                <p>Subcribe to get information about products and coupons</p>
                            </div>
                        </div>
                        <div class="col-xl-7 col-lg-12 col-md-12 col-sm-12 col-12 ">
                            <div class="ps-form__right">
                                <div class="form-group--nest">
                                    <input class="form-control" type="email" placeholder="Email address">
                                    <button class="ps-btn">Subscribe</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>










<?php include ('../incs-template1/footer.php'); ?>
<?php 
require_once ('../incs-template1/config.php'); 
include_once ('../incs-template1/cookie-session.php'); 


if(!isset($_SESSION['user_id'])){
	header("Location:./");
	exit();

}


$errors = array();
if ($_SERVER['REQUEST_METHOD'] == 'POST' AND isset($_POST['submit'])){
	 
    if ($_POST['slider-banner'] == "Choose banner") {
         $errors['choose-slider-banner'] = 'Please select a banner to upload';
     } else{
     $slider_banner = $_POST['slider-banner'];
     }
     
 if (is_uploaded_file($_FILES['slider_banner']['tmp_name']) AND $_FILES['slider_banner']['error'] == UPLOAD_ERR_OK){ 
         
             if($_FILES['slider_banner']['size'] > 2097152){ 		//conditions for the file size 2MB
                 $errors['editfile_size']="File size is too big. Max file size 2MB";
             }
         
             $editallowed_extensions = array('jpeg', '.png', '.jpg', '.JPG', 'JPEG', '.PNG');		
             $editallowed_mime = array('image/jpeg', 'image/png', 'image/pjpeg', 'image/JPG', 'image/X-PNG', 'image/PNG', 'image/x-png');
             $editimage_info = getimagesize($_FILES['slider_banner']['tmp_name']);
             $ext = substr($_FILES['slider_banner']['name'], -4);
             
             
             
             
             if (!in_array($_FILES['slider_banner']['type'], $editallowed_mime) || !in_array($editimage_info['mime'], $editallowed_mime) || !in_array($ext, $editallowed_extensions)){
                 $errors['wrong_upload'] = "Please choose correct file type. JPG or PNG";
                 
             }
             
         }else{
         $errors['slider_banner'] = 'Please upload banner';	
         
         }
    
 
 
     //now to edit the product	
     if (empty($errors)){
 
       
         $new_name= (string) sha1($_FILES['slider_banner']['name'] . uniqid('',true));
             $new_name .= ((substr($ext, 0, 1) != '.') ? ".{$ext}" : $ext);
             $dest = "images/banners/".$new_name;
             
             if (move_uploaded_file($_FILES['slider_banner']['tmp_name'], $dest)) {
             
             $_SESSION['images']['new_name'] = $new_name;
             $_SESSION['images']['file_name'] = $_FILES['slider_banner']['name'];
             
      
 
 mysqli_query($connect, "UPDATE banner SET banner_image='".$new_name."' WHERE banner_name = '".$slider_banner."'") or die(db_conn_error);
             if (mysqli_affected_rows($connect) == 1) {
             
             $_POST = array();		
             $_FILES = array();
                 
             unset($_FILES['slider_banner'], $_SESSION['images']);
             header('Location:'.GEN_WEBSITE);
             exit();
            
             
   }
 
 } else {
             trigger_error('The file could not be moved.');
             $errors['not_moved'] = "The file could not be moved.";
             unlink ($_FILES['slider_banner']['tmp_name']);
             }	
 
 } 
 
 
  }














 include ('../incs-template1/header.php'); ?>









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
                                                
                                                    
                                                <?php        
                        $banner_list = array('Banner 1', 'Banner 2', 'Banner 3', 'Banner 4', 'Banner 5', 'Banner 6', 'Banner 7');    
                       
                        echo '<option value="Choose banner">Choose banner</option>';
                                        
                        if(isset ($_POST['slider-banner'])){
                        foreach ($banner_list as $banner_as){
                        $sel_banner = ($banner_as==$_POST['slider-banner'])?"Selected='selected'":"";
                        echo '<option '.$sel_banner. 'value="'.$banner_as.'">'.$banner_as.'</option>';}
                        }else{
                        foreach ($banner_list as $banner_as){
                        echo '<option value="'.$banner_as.'">'.$banner_as.'</option>';
                        }
                        }
                        ?>            
                                                
                                                
                                                
                                              
                                                </select>
                                                <?php if (array_key_exists('choose-slider-banner', $errors)) {
	                    echo '<p class="text-danger" >'.$errors['choose-slider-banner'].'</p>';
	                    }
                    ?>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Upload</label>
                                                <input class="form-control" type="file" placeholder="Upload banner" name="slider_banner">
                                                <?php 
                        if (array_key_exists('slider_banner', $errors)) {
				        echo '<p class="text-danger">'.$errors['slider_banner'].'</p>';}
                        
                        if (array_key_exists('editfile_size', $errors)) {
                            echo '<p class="text-danger">'.$errors['editfile_size'].'</p>';}

                        if (array_key_exists('wrong_upload', $errors)) {
                            echo '<p class="text-danger">'.$errors['wrong_upload'].'</p>';}

                        if (array_key_exists('not_moved', $errors)) {
                            echo '<p class="text-danger">'.$errors['not_moved'].'</p>';}
                            


                        ?>
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
        <!-- <div class="ps-newsletter">
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
        </div> -->
    </main>










<?php include ('../incs-template1/footer.php'); ?>
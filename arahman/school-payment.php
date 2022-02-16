<?php
require_once ('../incs-arahman/config.php');
require_once ('../incs-arahman/gen_serv_con.php');


if(!isset($_SESSION['primary_id'])){   
	header('Location:'.GEN_WEBSITE);
	exit();
}
  if(isset($_SESSION['primary_id']) AND $_SESSION['pri_admit'] == 1){   //logged in students dont have right to login again.
    header('Location:'.GEN_WEBSITE.'/students/home.php');
    exit();
  }
  

 



include ('../incs-arahman/header.php');



?>

<header class="header-area header-sticky">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav class="main-nav">
                        <!-- ***** Logo Start ***** -->
                        <a href="index.html" class="logo">
                          Edu Meeting
                      </a>
                        <!-- ***** Logo End ***** -->
                        <!-- ***** Menu Start ***** -->
                        <ul class="nav">
                           
<?php
if(isset($_SESSION['primary_id'])){
echo '<li class=""><a href="logout.php">Logout</a></li>';

}
?>


                            
                        </ul>
                        <a class='menu-trigger'>
                            <span>Menu</span>
                        </a>
                        <!-- ***** Menu End ***** -->
                    </nav>
                </div>
            </div>
        </div>
    </header>



<section class="contact-us" id="contact">
    <div class="container">
      <div class="row">
        <div class="col-lg-9 align-self-center center-block">
          <div class="row">
            <div class="col-lg-12">
              <form id="contact" action="pay.php" method="post">
                <div class="row">
                  <div class="col-lg-12">
                    <h2>Primary school class fees</h2>
                  </div>
                 
                 
                 
<input type="hidden" name="email" value="<?= $_SESSION['pri_email']; ?>"/>

                  <div class="col-lg-4">
                 

                  <fieldset>
                    <?php if(isset($_GET['select-class']) AND $_GET['select-class'] == 'not-selected'){
                      echo '<p class="text-danger">Please select a primary class to pay</p>';
                    } ?>
                   <select name="primary_payment">
                   <option>Choose school class</option>
                    <option value="<?=BASIC_ONE_FEES;?>">Basic one</option>
<option value="<?=BASIC_TWO_FEES;?>">Basic two</option>
<option value="<?=BASIC_THREE_FEES;?>">Basic three</option>
<option value="<?=BASIC_FOUR_FEES;?>">Basic four</option>
<option value="<?=BASIC_FIVE_FEES;?>">Basic five</option>
<option value="<?=BASIC_SIX_FEES;?>">Basic six</option>

                   </select>
                  </fieldset>


</div>
                 
               



                  
                  
                
<div class="col-lg-12">
                    <fieldset>
                      <button type="submit" id="form-submit" class="button" name="pay">Pay School fees</button>
                    </fieldset>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
        
      </div>
    </div>
    </section>











    <section class="contact-us" id="contact">
    <div class="footer">
            <p>Copyright © 2022 Edu Meeting Co., Ltd. All Rights Reserved.
                <br>Design: <a href="https://templatemo.com" target="_parent" title="free css templates">TemplateMo</a></p>
        </div>
    </section>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script src="assets/js/isotope.min.js"></script>
    <script src="assets/js/owl-carousel.js"></script>
    <script src="assets/js/lightbox.js"></script>
    <script src="assets/js/tabs.js"></script>
    <script src="assets/js/video.js"></script>
    <script src="assets/js/slick-slider.js"></script>
    <script src="assets/js/custom.js"></script>
    <script>
        //according to loftblog tut
        $('.nav li:first').addClass('active');

        var showSection = function showSection(section, isAnimate) {
            var
                direction = section.replace(/#/, ''),
                reqSection = $('.section').filter('[data-section="' + direction + '"]'),
                reqSectionPos = reqSection.offset().top - 0;

            if (isAnimate) {
                $('body, html').animate({
                        scrollTop: reqSectionPos
                    },
                    800);
            } else {
                $('body, html').scrollTop(reqSectionPos);
            }

        };

        var checkSection = function checkSection() {
            $('.section').each(function() {
                var
                    $this = $(this),
                    topEdge = $this.offset().top - 80,
                    bottomEdge = topEdge + $this.height(),
                    wScroll = $(window).scrollTop();
                if (topEdge < wScroll && bottomEdge > wScroll) {
                    var
                        currentId = $this.data('section'),
                        reqLink = $('a').filter('[href*=\\#' + currentId + ']');
                    reqLink.closest('li').addClass('active').
                    siblings().removeClass('active');
                }
            });
        };

        $('.main-menu, .responsive-menu, .scroll-to-section').on('click', 'a', function(e) {
            e.preventDefault();
            showSection($(this).attr('href'), true);
        });

        $(window).scroll(function() {
            checkSection();
        });
    </script>
    </body>



    </html>

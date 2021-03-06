<?php include("config.php");?>
<?php

if(isset($_SESSION['login_user']))
{
    if(!isset($_SESSION['cart']))
    {
        $_SESSION['cart'] = array();
    }
    $name = $_SESSION['login_user'];
    $q = "SELECT * from user where uname='$name';";
    $results = $db->query($q);
    $row = $results->fetch_assoc();
    $_SESSION['cart'] = unserialize($row['cart_details']);
    foreach($_SESSION['cart'] as $key=>$value){
        if($value == 0)
        {
            unset($_SESSION['cart'][$key]);
        }
    }
    
}

if(isset($_POST['newdata'])) 
{
    if(!isset($_SESSION['cart']))
    {
        $_SESSION['cart'] = array();
    }
    $newdata = $_POST['newdata'];
    $d = json_decode($newdata,true);
    $_SESSION['cartdata'] = $d;
    foreach($_SESSION['cartdata'] as $k=>$v)
    {
        $_SESSION['cart'][$k] = $v;
    }
    updatedb();

}

if($_SERVER["REQUEST_METHOD"] == "GET") {
	// username and password sent from form 
	
    $id = isset($_GET['id']) ? $_GET['id'] : "";
    
    if(!isset($_SESSION['cart']))
    {
        $_SESSION['cart'] = array();
    }

    if(array_key_exists($id,$_SESSION['cart']))
    {
        

    }
    elseif($id!='')
    {
        $_SESSION['cart'][$id] = 1;
        
    }
    updatedb();
    
}
function updatedb()
{
    if(isset($_SESSION['login_user']))
    {
        global $db;
        $var = serialize($_SESSION['cart']);
        $name = $_SESSION['login_user'];
        $q = "UPDATE user SET cart_details='$var' WHERE uname='$name';";
        $result = $db->query($q);
        

    }
    
}
?>
<!DOCTYPE html>
<html lang="zxx" class="no-js">

<head>
    <!-- Mobile Specific Meta -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Favicon-->
    <link rel="shortcut icon" href="img/fav.png">
    <!-- Author Meta -->
    <meta name="author" content="CodePixar">
    <!-- Meta Description -->
    <meta name="description" content="">
    <!-- Meta Keyword -->
    <meta name="keywords" content="">
    <!-- meta character set -->
    <meta charset="UTF-8">
    <!-- Site Title -->
    <title>VMKART</title>

    <!--
            CSS
            ============================================= -->
    <link rel="stylesheet" href="css/linearicons.css">
    <link rel="stylesheet" href="css/owl.carousel.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/themify-icons.css">
    <link rel="stylesheet" href="css/nice-select.css">
    <link rel="stylesheet" href="css/nouislider.min.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/main.css">
</head>

<body>

    <!-- Start Header Area -->
	<?php include("header.php");?>
	<!-- End Header Area -->

    <!-- Start Banner Area -->
    <section class="banner-area organic-breadcrumb">
        <div class="container">
            <div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
                <div class="col-first">
                    <h1>Shopping Cart</h1>
                    <nav class="d-flex align-items-center">
                        <a href="index.html">Home<span class="lnr lnr-arrow-right"></span></a>
                        <a href="category.html">Cart</a>
                    </nav>
                </div>
            </div>
        </div>
    </section>
    <!-- End Banner Area -->

    <!--================Cart Area =================-->
    <section class="cart_area">
        <div class="container">
            <div class="cart_inner">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Product</th>
                                <th scope="col">Price</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($_SESSION['cart'] as $key=>$value){
                            
                            $q = "select * from product_db where product_id=$key";
                            $r = $db->query($q);
                            $row = $r->fetch_assoc();
                            ?>
                            <tr>
                                
                                <td>
                                    <div class="media">
                                        <div class="d-flex">
                                            <img style="height:170px;width:170px;border:none" src="product_images/<?php echo $row['product_id']?>.jpg" alt="">
                                        </div>
                                        <div class="media-body">
                                            <p><?php echo $row['product_name'];?></p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <h5 class="p"><?php echo $row['price'];?></h5>
                                </td>
                                
                                <td>
                                    <div class="product_count">
                                        <input type="hidden" name="id" class="i" value="<?php echo $row['product_id'];?>">
                                        <input type="text" name="qty" id="cqty-<?php echo $row['product_id'];?>" maxlength="12" value="<?php echo $value;?>" title="Quantity:"
                                            class="q input-text qty" onchange="updateCart()">
                                        <button onclick="increase(<?php echo $row['product_id'];?>,<?php echo $row['availability'];?>)" class="increase items-count" type="button"><i class="lnr lnr-chevron-up"></i></button>
                                        <button onclick="decrease(<?php echo $row['product_id'];?>)" class="reduced items-count" type="button"><i class="lnr lnr-chevron-down"></i></button>
                                    </div>
                                </td>
                                <td>
                                    <h5 class="t">₹0</h5>
                                </td>
                            </tr>
                            <?php }?>
                            
                            <tr class="bottom_button">
                                <td>
                        <a class="gray_btn" onclick="updateSession()" href="#">Update Cart</a>
                                </td>
                                <td>

                                </td>
                               
                                <td>
                                   
                                </td>
                                
                            </tr>
                            <tr>
                                <td>

                                </td>
                                <td>

                                </td>
                                <td>
                                    <h5>Total</h5>
                                </td>
                                <td>
                                    <h5 id='ot'>₹ 0</h5>
                                </td>
                            </tr>
                            
                            <tr class="out_button_area">
                                <td>

                                </td>
                                <td>

                                </td>
                                <td>

                                </td>
                                <td>
                                    <div class="checkout_btn_inner d-flex align-items-center">
                                        <a class="gray_btn" href="index.php">Continue Shopping</a>
                                        <a class="primary-btn" href="#">Proceed to checkout</a>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
    <!--================End Cart Area =================-->

    <!-- start footer Area -->
    <?php include("footer.php");?>
    <!-- End footer Area -->

    <script>

        var jdata={};
       
        updateCart()
        function increase(pid,max)
        {
            var id = "cqty-"+pid
            var ele = document.getElementById(id)
            if(parseInt(ele.value)<parseInt(max))
            ele.value = (parseInt(ele.value)+1);
            updateCart()
        }
        function decrease(pid)
        {
            var id = "cqty-"+pid
            var ele = document.getElementById(id)
            ele.value = parseInt(ele.value)-1;
            if(parseInt(ele.value)<0)
                ele.value = 0;
            updateCart()
        }
        function updateCart()
        {
            var pro = document.querySelectorAll('.q');
            var pri = document.querySelectorAll('.p');
            var tot = document.querySelectorAll('.t');
            var sum = 0;
            for( i=0;i<pro.length;i++)
            {
                tot[i].innerHTML = parseInt(pro[i].value) * parseInt(pri[i].innerHTML); 
                sum = sum + parseInt(tot[i].innerHTML);
            }
            document.getElementById('ot').innerHTML = "₹ "+sum;
            

        }
        function updateDatabase()
        {
            var qty = document.querySelectorAll('.q');
            var id = document.querySelectorAll('.i');
            for( i=0;i<id.length;i++)
            {
                jdata[id[i].value] = qty[i].value;
               
            }
            $.ajax({
                type: 'POST',
                url: 'cart.php',
                data: {json: JSON.stringify(jdata)},
                dataType: 'json'
            });  
        }

        function updateSession()
        {
            var qty = document.querySelectorAll('.q');
            var id = document.querySelectorAll('.i');
            for( i=0;i<id.length;i++)
            {
                jdata[id[i].value] = qty[i].value;
               
            }
            newData = JSON.stringify(jdata);
            console.log(newData)
            $.ajax({
                    url: 'cart.php',
                    type: "post",
                    data: {'newdata' : newData},
                    success: function(data){
                        alert(data);
                    }
            });


        }
        
    </script>

    <script src="js/vendor/jquery-2.2.4.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4"
	 crossorigin="anonymous"></script>
	<script src="js/vendor/bootstrap.min.js"></script>
	<script src="js/jquery.ajaxchimp.min.js"></script>
	<script src="js/jquery.nice-select.min.js"></script>
	<script src="js/jquery.sticky.js"></script>
    <script src="js/nouislider.min.js"></script>
	<script src="js/jquery.magnific-popup.min.js"></script>
	<script src="js/owl.carousel.min.js"></script>
	<!--gmaps Js-->
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCjCGmQ0Uq4exrzdcL6rvxywDDOvfAu6eE"></script>
	<script src="js/gmaps.min.js"></script>
	<script src="js/main.js"></script>
</body>

</html>
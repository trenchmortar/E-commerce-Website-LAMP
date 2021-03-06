<?php
session_start();
include "includes/db.php";
include "functions/functions.php";
?>
<!DOCTYPE html>
<html>
<head>
<title>E commerce Store </title>
<link href="http://fonts.googleapis.com/css?family=Roboto:400,500,700,300,100" rel="stylesheet" >
<link href="styles/bootstrap.min.css" rel="stylesheet">
<link href="styles/style.css" rel="stylesheet">
<link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css">
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>
<body>
<div id="top">
<div class="container">
<div class="col-md-6 offer">
<a href="#" class="btn btn-success btn-sm" >
<?php
if (!isset($_SESSION['customer_email'])) {
    echo "Welcome :Guest";
} else {
    echo "Welcome : " . $_SESSION['customer_email'] . "";
}
if (
    isset($_GET['itemId']) &&
    isset($_GET['quantity']) &&
    isset($_GET['price']) &&
    isset($_GET['size'])) {
    addToCart($_GET['itemId'], $_GET['quantity'], $_GET['price'], $_GET['size']);
}
?>
</a>
<a href="cart.php">
Shopping Cart Total Price: <?php total_price();?>, Total Items <?php items();?>
</a>
</div>
<div class="col-md-6">
<ul class="menu">
<li>
<a href="customer_register.php">
Register
</a>
</li>
<li>
<?php
if (!isset($_SESSION['customer_email'])) {
    echo "<a href='checkout.php' >My Account</a>";
} else {
    echo "<a href='customer/my_account.php?my_orders'>My Account</a>";
}
?>
</li>
<li>
<a href="cart.php">
Go to Cart
</a>
</li>
<li>
<?php
if (!isset($_SESSION['customer_email'])) {
    echo "<a href='checkout.php'> Login </a>";
} else {
    echo "<a href='logout.php'> Logout </a>";
}
?>
</li>
</ul>
</div>
</div>
</div>
<div class="navbar navbar-default" id="navbar">
<div class="container" >
<div class="navbar-header">
<a class="navbar-brand home" href="index.php" >
<img src="images/logo.png" width="80px" height="40px" alt="logo" class="hidden-xs animated bounce" >
<img src="images/logo-small.png" alt="logo" class="visible-xs animated bounce" >
</a>
</div>
<div class="navbar-collapse collapse" id="navigation" >
<div class="padding-nav" >
<ul class="nav navbar-nav navbar-left">
<li>
<a href="index.php"> Home </a>
</li>
<li>
<a href="shop.php"> Shop </a>
</li>
<li>
<?php
if (!isset($_SESSION['customer_email'])) {
    echo "<a href='checkout.php' >My Account</a>";
} else {
    echo "<a href='customer/my_account.php?my_orders'>My Account</a>";
}
?>
</li>
<li class="active" >
<a href="cart.php"> Shopping Cart </a>
</li>
<li>
<a href="about.php"> About Us </a>
</li>
<li>
<a href="contact.php"> Contact Us </a>
</li>
</ul>
</div>
<a class="btn btn-primary navbar-btn right" href="cart.php">
<i class="fa fa-shopping-cart"></i>
<span> <?php items();?> items in cart </span>
</a>
<div class="navbar-collapse collapse right">
<button class="btn navbar-btn btn-primary" type="button" data-toggle="collapse" data-target="#search">
<span class="sr-only">Toggle Search</span>
<i class="fa fa-search"></i>
</button>
</div>
<div class="collapse clearfix" id="search">
<form class="navbar-form" method="get" action="results.php">
<div class="input-group">
<input class="form-control" type="text" placeholder="Search" name="user_query" required>
<span class="input-group-btn">
<button type="submit" value="Search" name="search" class="btn btn-primary">
<i class="fa fa-search"></i>
</button>
</span>
</div>
</form>
</div>
</div>
</div>
</div>
<div id="content" >
<div class="container" >
<div class="col-md-12" >
<ul class="breadcrumb" >
<li>
<a href="index.php">Home</a>
</li>
<li>Cart</li>
</ul>
</div>
<div class="col-md-9" id="cart" >
<div class="box animated zoomIn" >
<form action="cart.php" method="post" enctype="multipart-form-data" >
<h1> Shopping Cart </h1>
<?php
$ip_add = getRealUserIp();
$select_cart = "select * from cart where ip_add='$ip_add'";
$run_cart = mysqli_query($con, $select_cart);
$count = mysqli_num_rows($run_cart);
?>
<p class="text-muted" > You currently have <?php echo $count; ?> item(s) in your cart. </p>
<div class="table-responsive" >
<table class="table" >
<thead>
<tr>
<th colspan="2" >Product</th>
<th>Quantity</th>
<th>Unit Price</th>
<th>Size</th>
<th colspan="1">Delete</th>
<th colspan="2"> Sub Total </th>
</tr>
</thead>
<tbody>
<?php
$total = 0;
while ($row_cart = mysqli_fetch_array($run_cart)) {
    $pro_id = $row_cart['p_id'];
    $pro_size = $row_cart['size'];
    $pro_qty = $row_cart['qty'];
    $only_price = $row_cart['p_price'];
    $get_products = "select * from products where product_id='$pro_id'";
    $run_products = mysqli_query($con, $get_products);
    while ($row_products = mysqli_fetch_array($run_products)) {
        $product_title = $row_products['product_title'];
        $product_img1 = $row_products['product_img1'];
        $sub_total = $only_price * $pro_qty;
        $_SESSION['pro_qty'] = $pro_qty;
        $total += $sub_total;
        ?>
<tr>
<td>
<img src="admin_area/product_images/<?php echo $product_img1; ?>" >
</td>
<td>
<a href="#" > <?php echo $product_title; ?> </a>
</td>
<td>
<input type="text" name="quantity" value="<?php echo $_SESSION['pro_qty']; ?>" data-product_id="<?php echo $pro_id; ?>" class="quantity form-control">
</td>
<td>
Rs.<?php echo $only_price; ?>.00
</td>
<td>
<?php echo $pro_size; ?>
</td>
<td>
<input type="checkbox" name="remove[]" value="<?php echo $pro_id; ?>">
</td>
<td>
Rs.<?php echo $sub_total; ?>.00
</td>
</tr>
<?php
}
}?>
</tbody>
<tfoot>
<tr>
<th colspan="5"> Total </th>
<th colspan="2"> Rs.<?php echo $total; ?>.00 </th>
</tr>
</tfoot>
</table>
<div class="form-inline pull-right">
<div class="form-group">
<label>Coupon Code : </label>
<input type="text" name="code" class="form-control">
</div>
<input class="btn btn-primary" type="submit" name="apply_coupon" value="Apply Coupon Code" >
</div>
</div>
<div class="box-footer">
<div class="pull-left">
<a href="index.php" class="btn btn-default">
<i class="fa fa-chevron-left"></i> Continue Shopping
</a>
</div>
<div class="pull-right">
<button class="btn btn-default" type="submit" name="update" value="Update Cart">
<i class="fa fa-refresh"></i> Update Cart
</button>
<a href="checkout.php" class="btn btn-primary">
Proceed to checkout <i class="fa fa-chevron-right"></i>
</a>
</div>
</div>
</form>
</div>
<?php
if (isset($_POST['apply_coupon'])) {
    $code = $_POST['code'];
    if ($code == "") {
    } else {
        $get_coupons = "select * from coupons where coupon_code='$code'";
        $run_coupons = mysqli_query($con, $get_coupons);
        $check_coupons = mysqli_num_rows($run_coupons);
        if ($check_coupons == 1) {
            $row_coupons = mysqli_fetch_array($run_coupons);
            $coupon_pro = $row_coupons['product_id'];
            $coupon_price = $row_coupons['coupon_price'];
            $coupon_limit = $row_coupons['coupon_limit'];
            $coupon_used = $row_coupons['coupon_used'];
            if ($coupon_limit == $coupon_used) {
                echo "<script>swal('Your Coupon Code Has Been Expired')</script>";
            } else {
                $get_cart = "select * from cart where p_id='$coupon_pro' AND ip_add='$ip_add'";
                $run_cart = mysqli_query($con, $get_cart);
                $check_cart = mysqli_num_rows($run_cart);
                if ($check_cart == 1) {
                    $add_used = "update coupons set coupon_used=coupon_used+1 where coupon_code='$code'";
                    $run_used = mysqli_query($con, $add_used);
                    $update_cart = "update cart set p_price='$coupon_price' where p_id='$coupon_pro' AND ip_add='$ip_add'";
                    $run_update = mysqli_query($con, $update_cart);
                    echo "<script>swal('Coupon Code','Applied','error')</script>";
                    echo "<script>window.open('cart.php','_self')</script>";
                } else {
                    echo "<script>swal('Product Does Not Exist In Cart')</script>";
                }
            }
        } else {
            echo "<script> swal('Coupon Code','Invalid','error') </script>";
        }
    }
}
?>
<?php
function update_cart()
{
    global $con;
    if (isset($_POST['update'])) {
        foreach ($_POST['remove'] as $remove_id) {
            $delete_product = "delete from cart where p_id='$remove_id'";
            $run_delete = mysqli_query($con, $delete_product);
            if ($run_delete) {
                echo "<script>window.open('cart.php','_self')</script>";
            }
        }
    }
}
echo @$up_cart = update_cart();
?>
</div>
<div class="col-md-3">
<div class="box" id="order-summary">
<div class="box-header">
<h3>Order Summary</h3>
</div>
<div class="table-responsive">
<table class="table">
<tbody>
<tr>
<td> Order Subtotal </td>
<th> Rs.<?php echo $total; ?>.00 </th>
</tr>
<tr>
<td>Tax</td>
<th>Rs.0.00</th>
</tr>
<tr class="total">
<td>Total</td>
<th>Rs.<?php echo $total; ?>.00</th>
</tr>
</tbody>
</table>
</div>
</div>
</div>
</div>
</div>
<?php
include "includes/footer.php";
?>
<script src="js/jquery.min.js"> </script>
<script src="js/bootstrap.min.js"></script>
<script>
$(document).ready(function(data){
$(document).on('keyup', '.quantity', function(){
var id = $(this).data("product_id");
var quantity = $(this).val();
if(quantity  != ''){
$.ajax({
url:"change.php",
method:"POST",
data:{id:id, quantity:quantity},
success:function(data){
$("body").load('cart_body.php');
}
});
}
});
});
</script>
</body>
</html>

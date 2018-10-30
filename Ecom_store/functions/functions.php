<?php

$db = mysqli_connect("localhost", "root", "my_password", "Ecom_Store");

/// IP address code starts /////
function getRealUserIp()
{
    switch (true) {
        case (!empty($_SERVER['HTTP_X_REAL_IP'])):
            return $_SERVER['HTTP_X_REAL_IP'];
        case (!empty($_SERVER['HTTP_CLIENT_IP'])):
            return $_SERVER['HTTP_CLIENT_IP'];
        case (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])):
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        default:
            return $_SERVER['REMOTE_ADDR'];
    }
}

/// IP address code Ends /////

// items function Starts ///

function items()
{

    global $db;

    $ip_add = getRealUserIp();

    $get_items = "select * from cart where ip_add='$ip_add'";

    $run_items = mysqli_query($db, $get_items);

    $count_items = mysqli_num_rows($run_items);

    echo $count_items;

}

// items function Ends ///

// total_price function Starts //

function total_price()
{

    global $db;

    $ip_add = getRealUserIp();

    $total = 0;

    $select_cart = "select * from cart where ip_add='$ip_add'";

    $run_cart = mysqli_query($db, $select_cart);

    while ($record = mysqli_fetch_array($run_cart)) {

        $pro_id = $record['p_id'];

        $pro_qty = $record['qty'];

        $sub_total = $record['p_price'] * $pro_qty;

        $total += $sub_total;

    }

    echo "$" . $total;

}

// total_price function Ends //

function addToCart($item_id, $qty, $price, $size)
{
    global $db;

    $ip_add = getRealUserIp();

    $insert_cart = "insert into cart (p_id, ip_add, qty, p_price, size) VALUES
($item_id, '$ip_add', $qty, '$price', '$size')";

    $run_cart = mysqli_query($db, $insert_cart);

    if ($run_cart) {

        echo "<script> alert('Item has been added to Cart')</script>";

        echo "<script> window.open('cart.php','_self') </script>";

    }

}
// getPro function Starts //

function getPro()
{

    global $db;

    $get_products = "select * from products order by 1 DESC LIMIT 0,8";

    $run_products = mysqli_query($db, $get_products);

    while ($row_products = mysqli_fetch_array($run_products)) {

        $pro_id = $row_products['product_id'];

        $pro_title = $row_products['product_title'];

        $pro_price = $row_products['product_price'];

        $pro_img1 = $row_products['product_img1'];

        $pro_label = $row_products['product_label'];

        $manufacturer_id = $row_products['manufacturer_id'];

        $get_manufacturer = "select * from manufacturers where manufacturer_id='$manufacturer_id'";

        $run_manufacturer = mysqli_query($db, $get_manufacturer);

        $row_manufacturer = mysqli_fetch_array($run_manufacturer);

        $manufacturer_name = $row_manufacturer['manufacturer_title'];

        $pro_psp_price = $row_products['product_psp_price'];

        $pro_url = 'images/' . $row_products['product_url'] . '.php';

        if ($pro_label == "Sale" or $pro_label == "Gift") {

            $product_price = "<del> $$pro_price </del>";

            $product_psp_price = "| $$pro_psp_price";

        } else {

            $product_psp_price = "";

            $product_price = "$$pro_price";

        }

        if ($pro_label == "") {

        } else {

            $product_label = "

<a class='label sale' href='#' style='color:black;'>

<div class='thelabel'>$pro_label</div>

<div class='label-background'> </div>

</a>

";

        }

        echo "

<div class='col-md-4 col-sm-6 single' >

<div class='product' >

<a href='$pro_url' >

<img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAArIAAAKyCAQAAACDcPULAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAIGNIUk0AAHolAACAgwAA+f8AAIDpAAB1MAAA6mAAADqYAAAXb5JfxUYAAF1CSURBVHja7N17fFX1ne//17rtS0LQYEWNNragRhsvUaOFVlToQAes0GKrbbWjrXZGztHz0znVc/QMzBTP4BydGZ3RGe1UZ2Sq00qLU6zCVFRUbEVFAZVqhHhBRUUhQEj2Zd1+f+yVnZ1k79wgZCe8nzzEZCfRrO9e+72/67u+38/XCBERkaFiqglERBSyIiIKWRERUciKiChkRUQUsiIiopAVEVHIiogoZEVERCErIqKQFRFRyIqIiEJWREQhKyKikBUREYWsiIhCVkREISsiIgpZERGFrIiIKGRFRBSyIiIKWRERUciKiChkRUQUsiIiopAVEVHIiogoZEVERCErIqKQFRFRyIqIiEJWREQhKyKikBUREYWsiIhCVkREFLIiIgpZERGFrIiIKGRFRBSyIiIKWRERUciKiChkRUQUsiIiopAVEVHIiogoZEVERCErIqKQFRFRyIqIiEJWREQhKyIiClkREYWsiIhCVkREFLIiIgpZERGFrIiIKGRFRBSyIiIKWRERUciKiChkRUQUsiIiopAVEVHIiogoZEVERCErIqKQFRERhayIiEJWREQhKyIiClkREYWsiIhCVkREFLIiIgpZERGFrIiIKGRFRBSyIiIKWRERUciKiChkRUQUsiIiopAVEVHIioiIQlZERCErIqKQFRERhayIiEJWREQhKyIiClkREYWsiIhCVkREFLIiIgpZERGFrIiIKGRFRMqdrSaQ8rJhopWKtWaq7XRg+7aT7vpVw+39p/1k/Va1oShkRUp4qf7pRenZCdoxsTAGfOkVW5JdcGqT2lE0XCBSlNOamW3hYWMRdu3DYnQJXSP/x8TExMLCwr/QTqkVRSErIqKQFRERhayIiEJWREQhKyIiClkREYWsiIgoZEVEFLIiIgpZERFRyIqIKGRFRBSyIiKikBURUciKiChkRYaeAYSEUe3YUA0iClkREVHIiogoZEVEFLIiIqKQFRFRyIqIKGRlJFpX9+srX2xQO4wMTdV6rkYvW00w+mxOrrvqnemfTD9sxRmzDrRjNzC8EfiMzd5V+8Gkw9dOWquz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VNOJQlZERCErIiIKWRERhayIiEJWREQUsiIiClkREVHIStnykwYOJh4hSVzC6A/5j8IuM2K7P+bga3NQKSs6IaWsVG2xacMhjk8Ks6AXUHyxQfdHM1ieWlEUsiIlfDgpRhqTOBkCYmTpfS1X969VEeiclrJiaDGilJdf3GCQrbJSEDp2qvDiP3TAcLuFrNP181T1OTce26pWFIWsiMgBQTe+REQUsiIiClkREVHIiogoZEVEFLIiIqKQFRFRyIqIKGRFREQhKyKikBURUciKiIhCVkREISsiopAVERGFrIiIQlZERCGrJhARUciKiChkRUREISsiopAVEVHIioiIQlZERCErIqKQFRERhayIiEJWREQhKyIiClkREYWsiIhCVkREFLIiIgpZERGFrIiIKGRFRBSyIiKikBURUciKiChkRUREISsiopAVEVHIioiIQlZERCErIqKQFRERhayIiEJWREQhKyIiClkREYWsiIhCVkREFLIiIgpZERFRyIqIKGRFRBSyIiKikBURUciKiChkRUREISsiopAVEVHIioiIQlZERCErIqKQFRERhayIiEJWREQhKyIiClkREYWsiIgoZEVEFLIiIgpZERFRyIqIKGRFRBSyIiKikBURUciKiChkRUREISsiopAVEVHIioiIQlZERCErIqKQFRERhayIiEJWREQUsiIiClkREYWsiIgoZEVEFLIiIgpZERFRyIqIKGRFRBSyIiKikBURUciKiChkRUREISsiUvb+/wEAKHTTaIKgQW4AAAAASUVORK5CYII=' class='img-responsive img_main' id='img-main'>
<canvas id="c"></canvas>

</a>

<div class='text' >

<center>

<p class='btn btn-primary'> $manufacturer_name </p>


</center>

<hr>

<h3><a href='$pro_url' >$pro_title</a></h3>

<p class='price' > $product_price $product_psp_price </p>

<p class='buttons' >

<a href='$pro_url' class='btn btn-default' >View details</a>

<a href='cart.php?itemId=$pro_id&quantity=1&price=$pro_price&size=Medium' class='btn btn-primary'>

<i class='fa fa-shopping-cart'></i> Add to cart

</a>



</p>

</div>

$product_label


</div>

</div>

";

    }

}

// getPro function Ends //

/// getProducts Function Starts ///

function getProducts()
{

/// getProducts function Code Starts ///

    global $db;

    $aWhere = array();

/// Manufacturers Code Starts ///

    if (isset($_REQUEST['man']) && is_array($_REQUEST['man'])) {

        foreach ($_REQUEST['man'] as $sKey => $sVal) {

            if ((int) $sVal != 0) {

                $aWhere[] = 'manufacturer_id=' . (int) $sVal;

            }

        }

    }

/// Manufacturers Code Ends ///

/// Products Categories Code Starts ///

    if (isset($_REQUEST['p_cat']) && is_array($_REQUEST['p_cat'])) {

        foreach ($_REQUEST['p_cat'] as $sKey => $sVal) {

            if ((int) $sVal != 0) {

                $aWhere[] = 'p_cat_id=' . (int) $sVal;

            }

        }

    }

/// Products Categories Code Ends ///

/// Categories Code Starts ///

    if (isset($_REQUEST['cat']) && is_array($_REQUEST['cat'])) {

        foreach ($_REQUEST['cat'] as $sKey => $sVal) {

            if ((int) $sVal != 0) {

                $aWhere[] = 'cat_id=' . (int) $sVal;

            }

        }

    }

/// Categories Code Ends ///

    $per_page = 6;

    if (isset($_GET['page'])) {

        $page = $_GET['page'];

    } else {

        $page = 1;

    }

    $start_from = ($page - 1) * $per_page;

    $sLimit = " order by 1 DESC LIMIT $start_from,$per_page";

    $sWhere = (count($aWhere) > 0 ? ' WHERE ' . implode(' or ', $aWhere) : '') . $sLimit;

    $get_products = "select * from products  " . $sWhere;

    $run_products = mysqli_query($db, $get_products);

    while ($row_products = mysqli_fetch_array($run_products)) {

        $pro_id = $row_products['product_id'];

        $pro_title = $row_products['product_title'];

        $pro_price = $row_products['product_price'];

        $pro_img1 = $row_products['product_img1'];

        $pro_label = $row_products['product_label'];

        $manufacturer_id = $row_products['manufacturer_id'];

        $get_manufacturer = "select * from manufacturers where manufacturer_id='$manufacturer_id'";

        $run_manufacturer = mysqli_query($db, $get_manufacturer);

        $row_manufacturer = mysqli_fetch_array($run_manufacturer);

        $manufacturer_name = $row_manufacturer['manufacturer_title'];

        $pro_psp_price = $row_products['product_psp_price'];

        $pro_url = 'images/' . $row_products['product_url'] . '.php';

        if ($pro_label == "Sale" or $pro_label == "Gift") {

            $product_price = "<del> $$pro_price </del>";

            $product_psp_price = "| $$pro_psp_price";

        } else {

            $product_psp_price = "";

            $product_price = "$$pro_price";

        }

        if ($pro_label == "") {

        } else {

            $product_label = "

<a class='label sale' href='#' style='color:black;'>

<div class='thelabel'>$pro_label</div>

<div class='label-background'> </div>

</a>

";

        }

        echo "

<div class='col-md-4 col-sm-6 center-responsive' >

<div class='product' >

<a href='$pro_url' >

<img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAArIAAAKyCAQAAACDcPULAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAIGNIUk0AAHolAACAgwAA+f8AAIDpAAB1MAAA6mAAADqYAAAXb5JfxUYAAF1CSURBVHja7N17fFX1ne//17rtS0LQYEWNNragRhsvUaOFVlToQAes0GKrbbWjrXZGztHz0znVc/QMzBTP4BydGZ3RGe1UZ2Sq00qLU6zCVFRUbEVFAZVqhHhBRUUhQEj2Zd1+f+yVnZ1k79wgZCe8nzzEZCfRrO9e+72/67u+38/XCBERkaFiqglERBSyIiIKWRERUciKiChkRUQUsiIiopAVEVHIiogoZEVERCErIqKQFRFRyIqIiEJWREQhKyKikBUREYWsiIhCVkREISsiIgpZERGFrIiIKGRFRBSyIiIKWRERUciKiChkRUQUsiIiopAVEVHIiogoZEVERCErIqKQFRFRyIqIiEJWREQhKyKikBUREYWsiIhCVkREFLIiIgpZERGFrIiIKGRFRBSyIiIKWRERUciKiChkRUQUsiIiopAVEVHIiogoZEVERCErIqKQFRFRyIqIiEJWREQhKyIiClkREYWsiIhCVkREFLIiIgpZERGFrIiIKGRFRBSyIiIKWRERUciKiChkRUQUsiIiopAVEVHIiogoZEVERCErIqKQFRERhayIiEJWREQhKyIiClkREYWsiIhCVkREFLIiIgpZERGFrIiIKGRFRBSyIiIKWRERUciKiChkRUQUsiIiopAVEVHIioiIQlZERCErIqKQFRERhayIiEJWREQhKyIiClkREYWsiIhCVkREFLIiIgpZERGFrIiIKGRFRMqdrSaQ8rJhopWKtWaq7XRg+7aT7vpVw+39p/1k/Va1oShkRUp4qf7pRenZCdoxsTAGfOkVW5JdcGqT2lE0XCBSlNOamW3hYWMRdu3DYnQJXSP/x8TExMLCwr/QTqkVRSErIqKQFRERhayIiEJWREQhKyIiClkREYWsiIgoZEVEFLIiIgpZERFRyIqIKGRFRBSyIiKikBURUciKiChkRYaeAYSEUe3YUA0iClkREVHIiogoZEVEFLIiIqKQFRFRyIqIKGRlJFpX9+srX2xQO4wMTdV6rkYvW00w+mxOrrvqnemfTD9sxRmzDrRjNzC8EfiMzd5V+8Gkw9dOWquz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VNOJQlZERCErIiIKWRERhayIiEJWREQUsiIiClkREVHIStnykwYOJh4hSVzC6A/5j8IuM2K7P+bga3NQKSs6IaWsVG2xacMhjk8Ks6AXUHyxQfdHM1ieWlEUsiIlfDgpRhqTOBkCYmTpfS1X969VEeiclrJiaDGilJdf3GCQrbJSEDp2qvDiP3TAcLuFrNP181T1OTce26pWFIWsiMgBQTe+REQUsiIiClkREVHIiogoZEVEFLIiIqKQFRFRyIqIKGRFREQhKyKikBURUciKiIhCVkREISsiopAVERGFrIiIQlZERCGrJhARUciKiChkRUREISsiopAVEVHIioiIQlZERCErIqKQFRERhayIiEJWREQhKyIiClkREYWsiIhCVkREFLIiIgpZERGFrIiIKGRFRBSyIiKikBURUciKiChkRUREISsiopAVEVHIioiIQlZERCErIqKQFRERhayIiEJWREQhKyIiClkREYWsiIhCVkREFLIiIgpZERFRyIqIKGRFRBSyIiKikBURUciKiChkRUREISsiopAVEVHIioiIQlZERCErIqKQFRERhayIiEJWREQhKyIiClkREYWsiIgoZEVEFLIiIgpZERFRyIqIKGRFRBSyIiKikBURUciKiChkRUREISsiopAVEVHIioiIQlZERCErIqKQFRERhayIiEJWREQUsiIiClkREYWsiIgoZEVEFLIiIgpZERFRyIqIKGRFRBSyIiKikBURUciKiChkRUREISsiUvb+/wEAKHTTaIKgQW4AAAAASUVORK5CYII=' class='img-responsive img_main' id='img-main'>
<canvas id="c"></canvas>


</a>

<div class='text' >

<center>
<p class='btn btn-primary'> $manufacturer_name </p>

</center>

<hr>

<h3><a href='$pro_url' >$pro_title</a></h3>

<p class='price' > $product_price $product_psp_price </p>


<p class='buttons' >

<a href='$pro_url' class='btn btn-default' >View details</a>

<a href='cart.php?itemId=$pro_id&quantity=1&price=$pro_price&size=Medium' class='btn btn-primary'>

<i class='fa fa-shopping-cart'></i> Add to cart

</a>




</p>

</div>

$product_label


</div>

</div>

";

    }
/// getProducts function Code Ends ///

}

/// getProducts Function Ends ///

/// getPaginator Function Starts ///

function getPaginator()
{

/// getPaginator Function Code Starts ///

    $per_page = 6;

    global $db;

    $aWhere = array();

    $aPath = '';

/// Manufacturers Code Starts ///

    if (isset($_REQUEST['man']) && is_array($_REQUEST['man'])) {

        foreach ($_REQUEST['man'] as $sKey => $sVal) {

            if ((int) $sVal != 0) {

                $aWhere[] = 'manufacturer_id=' . (int) $sVal;

                $aPath .= 'man[]=' . (int) $sVal . '&';

            }

        }

    }

/// Manufacturers Code Ends ///

/// Products Categories Code Starts ///

    if (isset($_REQUEST['p_cat']) && is_array($_REQUEST['p_cat'])) {

        foreach ($_REQUEST['p_cat'] as $sKey => $sVal) {

            if ((int) $sVal != 0) {

                $aWhere[] = 'p_cat_id=' . (int) $sVal;

                $aPath .= 'p_cat[]=' . (int) $sVal . '&';

            }

        }

    }

/// Products Categories Code Ends ///

/// Categories Code Starts ///

    if (isset($_REQUEST['cat']) && is_array($_REQUEST['cat'])) {

        foreach ($_REQUEST['cat'] as $sKey => $sVal) {

            if ((int) $sVal != 0) {

                $aWhere[] = 'cat_id=' . (int) $sVal;

                $aPath .= 'cat[]=' . (int) $sVal . '&';

            }

        }

    }

/// Categories Code Ends ///

    $sWhere = (count($aWhere) > 0 ? ' WHERE ' . implode(' or ', $aWhere) : '');

    $query = "select * from products " . $sWhere;

    $result = mysqli_query($db, $query);

    $total_records = mysqli_num_rows($result);

    $total_pages = ceil($total_records / $per_page);

    echo "<li><a href='shop.php?page=1";

    if (!empty($aPath)) {
        echo "&" . $aPath;
    }

    echo "' >" . 'First Page' . "</a></li>";

    for ($i = 1; $i <= $total_pages; $i++) {

        echo "<li><a href='shop.php?page=" . $i . (!empty($aPath) ? '&' . $aPath : '') . "' >" . $i . "</a></li>";

    }
    ;

    echo "<li><a href='shop.php?page=$total_pages";

    if (!empty($aPath)) {
        echo "&" . $aPath;
    }

    echo "' >" . 'Last Page' . "</a></li>";

/// getPaginator Function Code Ends ///

}

/// getPaginator Function Ends ///

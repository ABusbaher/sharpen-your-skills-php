<?php

use App\Classes\Discount;
use App\Classes\Product as Product;
use App\Classes\Tax as Tax;
?>
<h2>REPORT</h2>
<?php
$tax = new Tax();
$discount = new Discount(15);
$productWithoutDiscount = new Product("The Little Prince", 12345, 20.25, $tax);
$productWithDiscount = new Product("The Little Prince", 12345, 20.25, $tax, $discount);
?>

<h4><?php echo $productWithDiscount->reportCosts() ?></h4>
<h4><?php echo $productWithoutDiscount->reportCosts() ?></h4>
<?php

use App\Classes\Cap;
use App\Classes\Discount;
use App\Classes\Product as Product;
use App\Classes\Tax as Tax;
use App\Classes\UpcDiscount;

?>

<h2>CAP</h2>

<?php
$tax = new Tax(21);
$discount = new Discount(15);
$upcDiscount = new UpcDiscount(7,12345);
$capLessThanDiscountPercentage = new Cap(20, 'percentage');
$capLessThanDiscountAbsolute = new Cap(4);
$productCapDiscountPercentage = new Product("The Little Prince", 12345, 20.25,
    $tax, $discount, $upcDiscount, null, null, $capLessThanDiscountPercentage);
$productCapDiscountAbsolute = new Product("The Little Prince", 12345, 20.25,
    $tax, $discount, $upcDiscount, null, null, $capLessThanDiscountAbsolute);
$capHigherThanDiscount = new Cap(30, 'percentage');
$productHigherCupThenDiscount = new Product("The Little Prince", 12345, 20.25,
    $tax, $discount, $upcDiscount, null, null, $capHigherThanDiscount);
?>

<h4><?php echo $productCapDiscountPercentage->reportCosts() ?></h4>
<h4><?php echo $productCapDiscountAbsolute->reportCosts() ?></h4>
<h4><?php echo $productHigherCupThenDiscount->reportCosts() ?></h4>
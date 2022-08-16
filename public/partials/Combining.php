<?php

use App\Classes\Discount;
use App\Classes\Packaging;
use App\Classes\Product as Product;
use App\Classes\Tax as Tax;
use App\Classes\Transport;
use App\Classes\UpcDiscount;

?>

<h2>COMBINING</h2>

<?php
$tax = new Tax(21);
$discount = new Discount(15);
$upcDiscount = new UpcDiscount(7,12345);
$transport = new Transport(2.2);
$packaging = new Packaging(1, 'percentage');
$product = new Product("The Little Prince", 12345, 20.25,
    $tax, $discount, $upcDiscount, $transport, $packaging);
$upcDiscountMultiplicative = new UpcDiscount(7,12345, false, true);
$productWithMultiplicativeUpcDiscount = new Product("The Little Prince", 12345, 20.25, $tax,
    $discount, $upcDiscountMultiplicative, $transport, $packaging);
?>

<h4><?php echo $product->reportCosts() ?></h4>
<h4><?php echo $productWithMultiplicativeUpcDiscount->reportCosts() ?></h4>
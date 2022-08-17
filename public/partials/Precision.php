<?php

use App\Classes\Discount;
use App\Classes\Product as Product;
use App\Classes\Tax as Tax;
use App\Classes\Transport;
use App\Classes\UpcDiscount;

?>
<h2>PRECISION</h2>
<?php
$tax = new Tax(21);
$discount = new Discount(15);
$upcDiscount = new UpcDiscount(7,12345, false, true);
$transport = new Transport(3, 'percentage');
$product = new Product("The Little Prince", 12345, 20.25, $tax, $discount, $upcDiscount, $transport);
echo $product->taxCost() . '-------';
echo $product->universalDiscountAmount() . '-------';
echo $product->upcDiscountAmount() . '-------';
echo $product->allDiscounts() . '-------';
echo $product->getTransportCost() . '-------';
?>
<br>
<h4><?php echo $product->reportCosts() ?></h4>

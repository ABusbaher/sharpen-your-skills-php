<?php

use App\Classes\Discount;
use App\Classes\Product as Product;
use App\Classes\Tax as Tax;
use App\Classes\UpcDiscount;

?>
<h2>PRECEDENCE</h2>
<?php
$tax = new Tax();
$discount = new Discount(15);
$upcDiscount = new UpcDiscount(7,12345, true);
$product = new Product("The Little Prince", 12345, 20.25, $tax, $discount, $upcDiscount);
?>

<h4><?php echo $product->reportCosts() ?></h4>

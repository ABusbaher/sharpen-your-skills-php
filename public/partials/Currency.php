<?php

use App\Classes\Product as Product;
use App\Classes\Tax as Tax;

?>

    <h2>CURRENCY</h2>

<?php
$tax = new Tax();
$productUSD = new Product("The Little Prince", 12345, 20.25, $tax);
$productGBP = new Product("The Little Prince", 12345, 17.76,
    $tax, null, null, null, null, null, 'GBP');
?>

    <h4><?php echo $productUSD->reportCosts() ?></h4>
    <h4><?php echo $productGBP->reportCosts() ?></h4>


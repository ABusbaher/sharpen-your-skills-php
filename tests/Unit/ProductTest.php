<?php

namespace Tests\Unit;

use App\Classes\Discount;
use App\Classes\Tax;
use App\Classes\UpcDiscount;
use PHPUnit\Framework\TestCase;
use App\Classes\Product;

class ProductTest extends TestCase {

    private Product $product;
    private Tax $tax;
    private Product $productWithDiscount;
    private Discount $discount;
    private UpcDiscount $upcDiscount;

    protected function setUp() : void
    {
        $this->tax = new Tax(20);
        $this->product = new Product("The Little Prince", 12345, 20.25, $this->tax);
        $this->discount = new Discount(10);
        $this->upcDiscount = new UpcDiscount(10, 1244);
        $this->productWithDiscount = new Product("Book", 123, 100, $this->tax, $this->discount);
    }

    /** @test */
    public function can_get_product_name(): void
    {
        $this->assertEquals("The Little Prince", $this->product->getName());
    }

    /** @test */
    public function can_get_product_upc(): void
    {
        $this->assertEquals(12345, $this->product->getUpc());
    }

    /** @test */
    public function can_get_product_price(): void
    {
        $this->assertEquals(20.25, $this->product->getPrice());
    }

    /** @test */
    public function can_calculate_tax_price(): void
    {
        $taxCost = round($this->product->getPrice() * $this->tax->getRate() / 100, 2);
        $this->assertEquals($taxCost, $this->product->taxCost());
    }

    /** @test */
    public function price_with_tax_is_changed_when_tax_is_changed(): void
    {
        $taxCost = round($this->product->getPrice() * $this->tax->getRate() / 100, 2);
        $this->assertEquals($taxCost, $this->product->taxCost());
        $this->tax->setRate(21);
        $changedTaxCost = round($this->product->getPrice() * $this->tax->getRate() / 100, 2);
        $this->assertEquals($changedTaxCost, $this->product->taxCost());
    }

    /** @test */
    public function discount_price_is_0_if_discount_is_not_added(): void
    {
        $this->assertEquals(0, $this->product->priceDiscount());
    }

    /** @test */
    public function can_calculate_discount_price(): void
    {
        $priceDiscount = round($this->productWithDiscount->getPrice() *
            $this->discount->getDiscount() / 100, 2);
        $this->assertEquals($priceDiscount, $this->productWithDiscount->priceDiscount());
    }

    /** @test */
    public function price_upc_discount_is_0_if_upc_discount_is_not_added(): void
    {
        $this->assertEquals(0, $this->product->priceUpcDiscount());
    }

    /** @test */
    public function price_upc_discount_is_0_if_upc_discount_is_not_same_as_product_upc(): void
    {
        $productOtherUpc = new Product("Book", 123, 100,
            $this->tax, null, $this->upcDiscount);
        $this->assertEquals(0, $productOtherUpc->priceUpcDiscount());
    }

    /** @test */
    public function can_calculate_upc_discount(): void
    {
        $productWithUpcDiscount = new Product("Book", 1244, 100,
            $this->tax, null, $this->upcDiscount);
        $priceUpcDiscount = round($productWithUpcDiscount->getPrice() *
            $this->upcDiscount->getUpcDiscount()  / 100, 2);
        $this->assertEquals($priceUpcDiscount, $productWithUpcDiscount->priceUpcDiscount());
    }

    /** @test */
    public function can_calculate_all_discounts(): void
    {
        $productWithDiscounts = new Product("Book", 1244, 100, $this->tax,
            $this->discount, $this->upcDiscount);
        $allDiscounts = round($productWithDiscounts->getPrice() * $this->discount->getDiscount() / 100, 2)
            + round($productWithDiscounts->getPrice() * $this->upcDiscount->getUpcDiscount() / 100, 2);
        $this->assertEquals($allDiscounts, $productWithDiscounts->allDiscounts());
    }
}
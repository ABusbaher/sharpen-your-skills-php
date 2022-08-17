<?php

namespace Tests\Feature;

use App\Classes\Cap;
use App\Classes\Discount;
use App\Classes\Packaging;
use App\Classes\Tax;
use App\Classes\Transport;
use App\Classes\UpcDiscount;
use PHPUnit\Framework\TestCase;
use App\Classes\Product;

class ProductTest extends TestCase {

    private Product $product;
    private Tax $tax;
    private Discount $discount;
    private UpcDiscount $upcDiscount;
    private Product $productWithDiscount;
    private Product $productWithTwoDiscounts;

    protected function setUp() : void
    {
        $this->tax = new Tax(20);
        $this->product = new Product("The Little Prince", 12344, 20.25, $this->tax);
        $this->discount = new Discount(10);
        $this->upcDiscount = new UpcDiscount(5, 123456);
        $this->productWithDiscount = new Product("The Little Prince", 12345, 100,
            $this->tax, $this->discount);
        $this->productWithTwoDiscounts = new Product("The Little Prince", 12345, 100,
            $this->tax, $this->discount, $this->upcDiscount);
    }

    /** @test */
    public function can_calculate_price_with_tax(): void
    {
        $priceWithTax = $this->product->taxCost() + $this->product->getPrice();
        $this->assertEquals($priceWithTax, $this->product->getPriceWithTax());
    }

    /** @test */
    public function price_with_tax_is_changed_when_tax_rate_is_changed(): void
    {
        $priceWithTax = $this->product->taxCost() + $this->product->getPrice();
        $this->assertEquals($priceWithTax, $this->product->getPriceWithTax());
        $this->tax->setRate(21);
        $changedPriceWithTax = $this->product->taxCost() + $this->product->getPrice();
        $this->assertEquals($changedPriceWithTax, $this->product->getPriceWithTax());
    }

    /** @test */
    public function can_calculate_price_with_tax_and_discount(): void
    {
        $priceWithTaxAndDiscount =
            $this->productWithDiscount->getPrice() +
            $this->productWithDiscount->taxCost() -
            $this->productWithDiscount->universalDiscountAmount();
        $this->assertEquals($priceWithTaxAndDiscount, $this->productWithDiscount->getPriceWithTaxAndDiscounts());
    }

    /** @test */
    public function can_generate_report_without_discount(): void
    {
        $report = $this->product->reportCosts();
        $this->assertStringContainsString('Cost = ' . round($this->product->getPrice(),2), $report);
        $this->assertStringContainsString('Tax = ' . round($this->product->taxCost(),2), $report);
        $this->assertStringContainsString('TOTAL = ' . round($this->product->getPriceWithTaxAndDiscounts(),2), $report);
        $this->assertStringNotContainsString('Discounts = ' . round($this->product->allDiscounts(),2), $report);
    }

    /** @test */
    public function can_generate_report_with_discount(): void
    {
        $report = $this->productWithDiscount->reportCosts();
        $this->assertStringContainsString('Cost = ' . round($this->productWithDiscount->getPrice(),2), $report);
        $this->assertStringContainsString('Tax = ' . round($this->productWithDiscount->taxCost(),2), $report);
        $this->assertStringContainsString('TOTAL = ' . round($this->productWithDiscount->getPriceWithTaxAndDiscounts(),2), $report);
        $this->assertStringContainsString('Discounts = ' . round($this->productWithDiscount->allDiscounts(),2), $report);
    }

    /** @test */
    public function can_generate_report_with_all_discounts(): void
    {
        $report = $this->productWithTwoDiscounts->reportCosts();
        $this->assertStringContainsString('Cost = ' . round($this->productWithTwoDiscounts->getPrice(),2), $report);
        $this->assertStringContainsString('Tax = ' . round($this->productWithTwoDiscounts->taxCost(),2), $report);
        $this->assertStringContainsString('TOTAL = ' . round($this->productWithTwoDiscounts->getPriceWithTaxAndDiscounts(),2), $report);
        $this->assertStringContainsString('Discounts = ' . round($this->productWithTwoDiscounts->allDiscounts(),2), $report);
    }

    /** @test */
    public function can_generate_report_with_upc_discount_before_tax(): void
    {
        $upcDiscount = new UpcDiscount(5, 12345, true);
        $product = new Product("The Little Prince", 12345, 100,
            $this->tax, null, $upcDiscount);
        $report = $product->reportCosts();
        $this->assertStringContainsString('Cost = ' . round($product->getPrice(),2), $report);
        $this->assertStringContainsString('Tax = ' . round($product->taxCost(),2), $report);
        $this->assertStringContainsString('TOTAL = ' . round($product->getPriceWithTaxAndDiscounts(),2), $report);
        $this->assertStringContainsString('Discounts = ' . round($product->allDiscounts(),2), $report);
    }

    /** @test */
    public function can_generate_report_with_universal_discount_before_tax(): void
    {
        $discount = new Discount(10, true);
        $product = new Product("The Little Prince", 12345, 100,
            $this->tax, $discount);
        $report = $product->reportCosts();
        $this->assertStringContainsString('Cost = ' . round($product->getPrice(),2), $report);
        $this->assertStringContainsString('Tax = ' . round($product->taxCost(),2), $report);
        $this->assertStringContainsString('TOTAL = ' . round($product->getPriceWithTaxAndDiscounts(),2), $report);
        $this->assertStringContainsString('Discounts = ' . round($product->allDiscounts(),2), $report);
    }

    /** @test */
    public function can_generate_report_with_both_discounts_before_tax(): void
    {
        $discount = new Discount(10, true);
        $upcDiscount = new UpcDiscount(5, 12345, true);
        $product = new Product("The Little Prince", 12345, 100,
            $this->tax, $discount, $upcDiscount);
        $report = $product->reportCosts();
        $this->assertStringContainsString('Cost = ' . round($product->getPrice(),2), $report);
        $this->assertStringContainsString('Tax = ' . round($product->taxCost(),2), $report);
        $this->assertStringContainsString('TOTAL = ' . round($product->getPriceWithTaxAndDiscounts(),2), $report);
        $this->assertStringContainsString('Discounts = ' . round($product->allDiscounts(),2), $report);
    }

    /** @test */
    public function can_generate_report_with_transport_and_packaging_costs(): void
    {
        $transport = new Transport(2.2);
        $packaging = new Packaging(1);
        $productWithAdditionalCost = new Product("The Little Prince", 12345, 20.25,
            $this->tax, $this->discount, $this->upcDiscount, $transport, $packaging);
        $report = $productWithAdditionalCost->reportCosts();
        $this->assertStringContainsString('Cost = ' . round($productWithAdditionalCost->getPrice(),2), $report);
        $this->assertStringContainsString('Tax = ' . round($productWithAdditionalCost->taxCost(),2), $report);
        $this->assertStringContainsString('Discounts = ' . round($productWithAdditionalCost->allDiscounts(),2), $report);
        $this->assertStringContainsString('Transport = ' . round($productWithAdditionalCost->getTransportCost(),2), $report);
        $this->assertStringContainsString('Packaging = ' . round($productWithAdditionalCost->getPackagingCost(),2), $report);
        $this->assertStringContainsString('TOTAL = ' . round($productWithAdditionalCost->getPriceWithTaxAndDiscounts(),2), $report);
    }

    /** @test */
    public function can_generate_correct_report_with_multiplicative_upc_discount(): void
    {
        $multiplicativeUpcDiscount = new UpcDiscount(10, 12345, false, true);
        $productWithMultiplicativeUpcDiscount = new Product("The Little Prince", 12345, 20.25,
            $this->tax, $this->discount, $multiplicativeUpcDiscount);
        $report = $productWithMultiplicativeUpcDiscount->reportCosts();
        $this->assertStringContainsString('Cost = ' . round($productWithMultiplicativeUpcDiscount->getPrice(),2), $report);
        $this->assertStringContainsString('Tax = ' . round($productWithMultiplicativeUpcDiscount->taxCost(),2), $report);
        $this->assertStringContainsString('Discounts = ' . round($productWithMultiplicativeUpcDiscount->allDiscounts(),2), $report);
        $this->assertStringContainsString('TOTAL = ' . round($productWithMultiplicativeUpcDiscount->getPriceWithTaxAndDiscounts(),2), $report);
    }

    /** @test */
    public function can_generate_correct_report_with_multiplicative_universal_discount(): void
    {
        $multiplicativeDiscount = new Discount(10, false, true);
        $productWithMultiplicativeUpcDiscount = new Product("The Little Prince", 12345, 20.25,
            $this->tax, $multiplicativeDiscount, $this->upcDiscount);
        $report = $productWithMultiplicativeUpcDiscount->reportCosts();
        $this->assertStringContainsString('Cost = ' . round($productWithMultiplicativeUpcDiscount->getPrice(),2), $report);
        $this->assertStringContainsString('Tax = ' . round($productWithMultiplicativeUpcDiscount->taxCost(),2), $report);
        $this->assertStringContainsString('Discounts = ' . round($productWithMultiplicativeUpcDiscount->allDiscounts(),2), $report);
        $this->assertStringContainsString('TOTAL = ' . round($productWithMultiplicativeUpcDiscount->getPriceWithTaxAndDiscounts(), 2), $report);
    }

    /** @test */
    public function can_generate_correct_report_with_absolute_cap(): void
    {
        $absoluteCap = new Cap(10, 'absolute');
        $productWithAbsoluteCap = new Product("The Little Prince", 12345, 20.25,
            $this->tax, $this->discount, $this->upcDiscount, null, null, $absoluteCap);
        $report = $productWithAbsoluteCap->reportCosts();
        $this->assertStringContainsString('Cost = ' . round($productWithAbsoluteCap->getPrice(),2), $report);
        $this->assertStringContainsString('Tax = ' . round($productWithAbsoluteCap->taxCost(),2), $report);
        $this->assertStringContainsString('Discounts = ' . round($productWithAbsoluteCap->allDiscounts(),2), $report);
        $this->assertStringContainsString('TOTAL = ' . round($productWithAbsoluteCap->getPriceWithTaxAndDiscounts(),2)
            , $report);
    }

    /** @test */
    public function can_generate_correct_report_with_percentage_cap(): void
    {
        $absoluteCap = new Cap(10, 'percentage');
        $productWithPercentageCap = new Product("The Little Prince", 12345, 20.25,
            $this->tax, $this->discount, $this->upcDiscount, null, null, $absoluteCap);
        $report = $productWithPercentageCap->reportCosts();
        $this->assertStringContainsString('Cost = ' . round($productWithPercentageCap->getPrice(),2), $report);
        $this->assertStringContainsString('Tax = ' . round($productWithPercentageCap->taxCost(),2), $report);
        $this->assertStringContainsString('Discounts = ' . round($productWithPercentageCap->allDiscounts(),2), $report);
        $this->assertStringContainsString('TOTAL = ' . round($productWithPercentageCap->getPriceWithTaxAndDiscounts()
                ,2), $report);
    }

    /** @test */
    public function can_generate_report_with_currency(): void
    {
        $this->productWithTwoDiscounts->setCurrency('GBP');
        $report = $this->productWithTwoDiscounts->reportCosts();
        $this->assertStringContainsString('Cost = ' . round($this->productWithTwoDiscounts->getPrice(),2)
            . $this->productWithTwoDiscounts->getCurrency(), $report);
        $this->assertStringContainsString('Tax = ' . round($this->productWithTwoDiscounts->taxCost(),2)
            . $this->productWithTwoDiscounts->getCurrency(), $report);
        $this->assertStringContainsString('Discounts = ' . round($this->productWithTwoDiscounts->allDiscounts(),2)
            . $this->productWithTwoDiscounts->getCurrency(), $report);
        $this->assertStringContainsString('TOTAL = ' . round($this->productWithTwoDiscounts->getPriceWithTaxAndDiscounts(),2)
            . $this->productWithTwoDiscounts->getCurrency(), $report);
    }

    /** @test */
    public function can_generate_report_rounded_by_two_decimals(): void
    {
        $this->productWithTwoDiscounts->setCurrency('GBP');
        $report = $this->productWithTwoDiscounts->reportCosts();
        $this->assertStringContainsString('Cost = ' .
            round($this->productWithTwoDiscounts->getPrice(),2), $report);
        $this->assertStringContainsString('Tax = ' .
            round($this->productWithTwoDiscounts->taxCost(),2), $report);
        $this->assertStringContainsString('Discounts = ' .
            round($this->productWithTwoDiscounts->allDiscounts(), 2), $report);
        $this->assertStringContainsString('TOTAL = ' .
            round($this->productWithTwoDiscounts->getPriceWithTaxAndDiscounts(),2), $report);
    }
}
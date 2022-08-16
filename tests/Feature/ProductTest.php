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
        $this->assertStringContainsString('Cost = $' . $this->product->getPrice(), $report);
        $this->assertStringContainsString('Tax = $' . $this->product->taxCost(), $report);
        $this->assertStringContainsString('TOTAL = $' . $this->product->getPriceWithTaxAndDiscounts(), $report);
        $this->assertStringNotContainsString('Discounts = $' . $this->product->allDiscounts(), $report);
    }

    /** @test */
    public function can_generate_report_with_discount(): void
    {
        $report = $this->productWithDiscount->reportCosts();
        $this->assertStringContainsString('Cost = $' . $this->productWithDiscount->getPrice(), $report);
        $this->assertStringContainsString('Tax = $' . $this->productWithDiscount->taxCost(), $report);
        $this->assertStringContainsString('TOTAL = $' . $this->productWithDiscount->getPriceWithTaxAndDiscounts(), $report);
        $this->assertStringContainsString('Discounts = $' . $this->productWithDiscount->allDiscounts(), $report);
    }

    /** @test */
    public function can_generate_report_with_all_discounts(): void
    {
        $report = $this->productWithTwoDiscounts->reportCosts();
        $this->assertStringContainsString('Cost = $' . $this->productWithTwoDiscounts->getPrice(), $report);
        $this->assertStringContainsString('Tax = $' . $this->productWithTwoDiscounts->taxCost(), $report);
        $this->assertStringContainsString('TOTAL = $' . $this->productWithTwoDiscounts->getPriceWithTaxAndDiscounts(), $report);
        $this->assertStringContainsString('Discounts = $' . $this->productWithTwoDiscounts->allDiscounts(), $report);
    }

    /** @test */
    public function can_generate_report_with_upc_discount_before_tax(): void
    {
        $upcDiscount = new UpcDiscount(5, 12345, true);
        $product = new Product("The Little Prince", 12345, 100,
            $this->tax, null, $upcDiscount);
        $report = $product->reportCosts();
        $this->assertStringContainsString('Cost = $' . $product->getPrice(), $report);
        $this->assertStringContainsString('Tax = $' . $product->taxCost(), $report);
        $this->assertStringContainsString('TOTAL = $' . $product->getPriceWithTaxAndDiscounts(), $report);
        $this->assertStringContainsString('Discounts = $' . $product->allDiscounts(), $report);
    }

    /** @test */
    public function can_generate_report_with_universal_discount_before_tax(): void
    {
        $discount = new Discount(10, true);
        $product = new Product("The Little Prince", 12345, 100,
            $this->tax, $discount);
        $report = $product->reportCosts();
        $this->assertStringContainsString('Cost = $' . $product->getPrice(), $report);
        $this->assertStringContainsString('Tax = $' . $product->taxCost(), $report);
        $this->assertStringContainsString('TOTAL = $' . $product->getPriceWithTaxAndDiscounts(), $report);
        $this->assertStringContainsString('Discounts = $' . $product->allDiscounts(), $report);
    }

    /** @test */
    public function can_generate_report_with_both_discounts_before_tax(): void
    {
        $discount = new Discount(10, true);
        $upcDiscount = new UpcDiscount(5, 12345, true);
        $product = new Product("The Little Prince", 12345, 100,
            $this->tax, $discount, $upcDiscount);
        $report = $product->reportCosts();
        $this->assertStringContainsString('Cost = $' . $product->getPrice(), $report);
        $this->assertStringContainsString('Tax = $' . $product->taxCost(), $report);
        $this->assertStringContainsString('TOTAL = $' . $product->getPriceWithTaxAndDiscounts(), $report);
        $this->assertStringContainsString('Discounts = $' . $product->allDiscounts(), $report);
    }

    /** @test */
    public function can_generate_report_with_transport_and_packaging_costs(): void
    {
        $transport = new Transport(2.2);
        $packaging = new Packaging(1);
        $productWithAdditionalCost = new Product("The Little Prince", 12345, 20.25,
            $this->tax, $this->discount, $this->upcDiscount, $transport, $packaging);
        $report = $productWithAdditionalCost->reportCosts();
        $this->assertStringContainsString('Cost = $' . $productWithAdditionalCost->getPrice(), $report);
        $this->assertStringContainsString('Tax = $' . $productWithAdditionalCost->taxCost(), $report);
        $this->assertStringContainsString('Discounts = $' . $productWithAdditionalCost->allDiscounts(), $report);
        $this->assertStringContainsString('Transport = $' . $productWithAdditionalCost->getTransportCost(), $report);
        $this->assertStringContainsString('Packaging = $' . $productWithAdditionalCost->getPackagingCost(), $report);
        $this->assertStringContainsString('TOTAL = $' . $productWithAdditionalCost->getPriceWithTaxAndDiscounts(), $report);
    }

    /** @test */
    public function can_generate_correct_report_with_multiplicative_upc_discount(): void
    {
        $multiplicativeUpcDiscount = new UpcDiscount(10, 12345, false, true);
        $productWithMultiplicativeUpcDiscount = new Product("The Little Prince", 12345, 20.25,
            $this->tax, $this->discount, $multiplicativeUpcDiscount);
        $report = $productWithMultiplicativeUpcDiscount->reportCosts();
        $this->assertStringContainsString('Cost = $' . $productWithMultiplicativeUpcDiscount->getPrice(), $report);
        $this->assertStringContainsString('Tax = $' . $productWithMultiplicativeUpcDiscount->taxCost(), $report);
        $this->assertStringContainsString('Discounts = $' . $productWithMultiplicativeUpcDiscount->allDiscounts(), $report);
        $this->assertStringContainsString('TOTAL = $' . $productWithMultiplicativeUpcDiscount->getPriceWithTaxAndDiscounts(), $report);
    }

    /** @test */
    public function can_generate_correct_report_with_multiplicative_universal_discount(): void
    {
        $multiplicativeDiscount = new Discount(10, false, true);
        $productWithMultiplicativeUpcDiscount = new Product("The Little Prince", 12345, 20.25,
            $this->tax, $multiplicativeDiscount, $this->upcDiscount);
        $report = $productWithMultiplicativeUpcDiscount->reportCosts();
        $this->assertStringContainsString('Cost = $' . $productWithMultiplicativeUpcDiscount->getPrice(), $report);
        $this->assertStringContainsString('Tax = $' . $productWithMultiplicativeUpcDiscount->taxCost(), $report);
        $this->assertStringContainsString('Discounts = $' . $productWithMultiplicativeUpcDiscount->allDiscounts(), $report);
        $this->assertStringContainsString('TOTAL = $' . $productWithMultiplicativeUpcDiscount->getPriceWithTaxAndDiscounts(), $report);
    }

    /** @test */
    public function can_generate_correct_report_with_absolute_cap(): void
    {
        $absoluteCap = new Cap(10, 'absolute');
        $productWithMultiplicativeUpcDiscount = new Product("The Little Prince", 12345, 20.25,
            $this->tax, $this->discount, $this->upcDiscount, null, null, $absoluteCap);
        $report = $productWithMultiplicativeUpcDiscount->reportCosts();
        $this->assertStringContainsString('Cost = $' . $productWithMultiplicativeUpcDiscount->getPrice(), $report);
        $this->assertStringContainsString('Tax = $' . $productWithMultiplicativeUpcDiscount->taxCost(), $report);
        $this->assertStringContainsString('Discounts = $' . $productWithMultiplicativeUpcDiscount->allDiscounts(), $report);
        $this->assertStringContainsString('TOTAL = $' . $productWithMultiplicativeUpcDiscount->getPriceWithTaxAndDiscounts(), $report);
    }

    /** @test */
    public function can_generate_correct_report_with_percentage_cap(): void
    {
        $absoluteCap = new Cap(10, 'percentage');
        $productWithMultiplicativeUpcDiscount = new Product("The Little Prince", 12345, 20.25,
            $this->tax, $this->discount, $this->upcDiscount, null, null, $absoluteCap);
        $report = $productWithMultiplicativeUpcDiscount->reportCosts();
        $this->assertStringContainsString('Cost = $' . $productWithMultiplicativeUpcDiscount->getPrice(), $report);
        $this->assertStringContainsString('Tax = $' . $productWithMultiplicativeUpcDiscount->taxCost(), $report);
        $this->assertStringContainsString('Discounts = $' . $productWithMultiplicativeUpcDiscount->allDiscounts(), $report);
        $this->assertStringContainsString('TOTAL = $' . $productWithMultiplicativeUpcDiscount->getPriceWithTaxAndDiscounts(), $report);
    }
}
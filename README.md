Ready to sharpen your skills?

## Rules of Conduct
1. **Fork this repo under your personal account** - e.g. https://git.vegaitsourcing.rs/b.miksin/sharpen-your-skills-assignment.
2. **Use a programming language that is assigned to you** - This challenge is not a competition. There are no better or worse solutions per se. The goal is to evaluate expressiveness of languages and usefulness of programming paradigms.
3. **Requirements should be implemented in order** - The sequence of requirements is simulating the evolution of customer's expectations over a prolonged period of time, as customer occasionally gets “bright ideas” and hands them over to developers. Implementing requirements all at once or out of order would look like real time was flowing back and forth.
4. **Application must remain operational at all times** - After implementing each requirement, the application must be correct and fully operational. The newly implemented requirement, as well as all previous requirements must work. If, however, there are bugs or omissions, don’t despair – just apply a fix when you find them and carry on. That is what you would do in a day-to-day development anyway.
5. **Mark each task with the requirement label** - Include requirement label into each commit message, so that the evolution of the product can be observed later. Each requirement begins with a label in capital letters.
6. **Pretend you don’t know the requirements in advance** - Even though you can read all the requirements right away, try thinking of those beyond the one you’re currently solving as yet unknown. Don’t over-engineer the current solution because you have anticipated a future requirement.

## Requirements
**TAX**

There is a product, defined by: Name (string), UPC (can be a plain number) and a price.  
Company is only doing business in US dollars. Money is calculated at two decimals precision (e.g. $12.34).  
There is a flat-rate tax (currently 20%) added to all products’ prices. Tax is mandatory and equal for all products.  
Customer wants to be able to specify tax percentage.  
Write code which displays base price and price with tax for a product.  

Definition of done:  
Sample product: Book with name = “The Little Prince”, UPC=12345, price=$20.25.
>>>
Product price reported as $20.25 before tax and $24.30 after 20% tax.  
Product price reported as $20.25 before tax and $24.50 after 21% tax.
>>>

**DISCOUNT**

Customer chooses to apply a relative discount to all products.  
Discount is specified as a percentage relative to price. Tax is always applied to a price before it was deduced, i.e. discount doesn’t reduce tax amount (see example below).  
Customer requires discount percentage to be configurable.  
Enhance code so that it can apply discount and tax to the product’s price.

Definition of done:  
Sample product: Book with name = “The Little Prince”, UPC=12345, price=$20.25.
>>>
Tax=20%, discount=15%  
Tax amount = $4.05; Discount amount = $3.04  
Price before = $20.25, price after = $21.26
>>>

**REPORT**

When a discount is applied, print out (or display by any convenient means) a message which reports the discounted amount.

Definition of done:  
Sample product: Title = “The Little Prince”, UPC=12345, price=$20.25.

Case #1:  
Tax = 20%, discount = 15%

Program prints:
>>>
Cost = $20.25  
Tax = $4.05  
Discounts = $3.04  
TOTAL = $21.26
>>>

Case #2:  
Tax = 20%, no discount

Program prints:
>>>
Cost = $20.25  
Tax = $4.05  
TOTAL = $24.30
>>>

Program doesn’t show any discounted amount.

**SELECTIVE**

There is a special discount assigned to a product with specified (configurable) UPC.  
This discount only applies to a product with UPC value equal to the value defined by the discount.  
If both universal and UPC-based discounts are applicable, they both apply to original product price and then sum up.  
When two discounts are applied, only the total discounted amount is printed (requirement REPORT-DISCOUNT).

Definition of done:  
Sample product: Title = “The Little Prince”, UPC=12345, price=$20.25.

Case #1:  
Tax = 20%, universal discount = 15%, UPC-discount = 7% for UPC=12345  
Tax amount = $20.25 * 20% = $4.05, discount = $20.25 * 15% = $3.04, UPC discount = $1.42

Program prints:
>>>
Cost = $20.25  
Tax = $4.05  
Discounts = $4.46  
TOTAL = $19.84
>>>

Case #2:  
Tax = 21%, universal discount = 15%, UPC-discount = 7 for UPC = 789  
Tax amount = $20.25 * 21% = $4.25, discount = $20.25 * 15% = $3.04

Program prints:
>>>
Cost = $20.25  
Tax = $4.25  
Discounts = $3.04  
TOTAL = $21.46
>>>

**PRECEDENCE**

By this point, tax had precedence over any discounts. That means that tax was always applied to full price of the product, not to the discounted price.  
Customer is happy to announce that some discounts can legally be applied before tax. That has the consequence that the tax amount would be lower.  
Extend the solution so that discounts can either be applied before tax calculation, or after tax calculation.

Definition of done:  
Sample product: Title = “The Little Prince”, UPC=12345, price=$20.25.  
Tax = 20%, universal discount (after tax) = 15%, UPC-discount (before tax) = 7% for UPC=12345

UPC discount amount = $1.42, remaining price = $20.25 - $1.42 = $18.83  
Tax amount = $18.83 * 20% = $3.77, universal discount = $18.83 * 15% = $2.82  
Final price = $20.25 - $1.42 + $3.77 - $2.82 = $19.78

Program prints:
>>>
Cost = $20.25  
Tax = $3.77  
Discounts = $4.24  
TOTAL = $19.78
>>>

**EXPENSES**

Customer wants to introduce packaging and transport costs, administrative costs, etc., which are neither subject to tax, nor to discounts.  
There can be more than one cost added, each defined by a description and an amount. Amount can either be a percentage of price or an absolute value.  
Program should separately report product price, tax, discounts, each cost and total.

Definition of done:  
Sample product: Title = “The Little Prince”, UPC=12345, price=$20.25.

Case #1:  
Tax = 21%, discount = 15%, UPC discount = 7% for UPC=12345  
Packaging cost = 1% of price  
Transport cost = $2.2  
Tax amount = $20.25 * 21% = $4.25, discounts = $20.25 * 15% + $20.25 * 7% = $3.04 + $1.42 = $4.46  
Packaging = $20.25 * 1% = $0.20, transport = $2.2

Program prints:
>>>
Cost = $20.25  
Tax = $4.25  
Discounts = $4.46  
Packaging = $0.20  
Transport = $2.2  
TOTAL = $22.44
>>>

Case #2:  
Tax 21%, no discounts and no additional costs.  

Program prints:
>>>
Cost = $20.25  
Tax = $4.25  
TOTAL = $24.50  
>>>

**COMBINING**

Customer is not satisfied with the way in which discounts are combined (simple sum).  
New request is to allow the customer to select between two methods of combining discounts: (1) additive - discounts are all calculated from the original price and summed up, or (2) multiplicative - each discount is calculated from the price after applying the previous one.

Definition of done:  
Sample product: Title = “The Little Prince”, UPC=12345, price=$20.25.

Case #1:  
Tax = 21%, discount = 15%, UPC discount = 7% for UPC=12345, additive discounts  
Packaging cost = 1% of price  
Transport cost = $2.2  
Tax amount = $20.25 * 21% = $4.25, discounts = $20.25 * 15% + $20.25 * 7% = $3.04 + $1.42 = $4.46  
Packaging = $20.25 * 1% = $0.20, transport = $2.2

Program prints:
>>>
Cost = $20.25  
Tax = $4.25  
Discounts = $4.46  
Packaging = $0.20  
Transport = $2.2  
TOTAL = $22.44
>>>

Case #2:  
Tax = 21%, discount = 15%, UPC discount = 7% for UPC=12345, multiplicative discounts  
Packaging cost = 1% of price  
Transport cost = $2.2  
Tax amount = $20.25 * 21% = $4.25, discount #1 = $20.25 * 15% = $3.04; discount #2 = ($20.25 - $3.04) * 7% = $1.20  
Packaging = $20.25 * 1% = $0.20, transport = $2.2

Program prints:
>>>
Cost = $20.25  
Tax = $4.25  
Discounts = $4.24  
Packaging = $0.20  
Transport = $2.2  
TOTAL = $22.66
>>>

**CAP**

Customer is not satisfied with total discounted amount and wants to put a cap on it.  
Cap is either a percentage of original price or an absolute amount. Either way, the discounted amount must not be larger than indicated by the cap.

Definition of done:  
Sample product: Title = “The Little Prince”, UPC=12345, price=$20.25.

Case #1:  
Tax = 21%, discount = 15%, UPC discount = 7% for UPC=12345, additive discounts, cap = 20%  
Tax amount = $20.25 * 21% = $4.25, discounts = $20.25 * 15% + $20.25 * 7% = $3.04 + $1.42 = $4.46, cap = $20.25 * 20% = $4.05

Program prints:
>>>
Cost = $20.25  
Tax = $4.25  
Discounts = $4.05  
TOTAL = $20.45
>>>

Case #2:  
Tax = 21%, discount = 15%, UPC discount = 7% for UPC=12345, additive discounts, cap = $4  
Tax amount = $20.25 * 21% = $4.25, discounts = $20.25 * 15% + $20.25 * 7% = $3.04 + $1.42 = $4.46, cap = $4

Program prints:
>>>
Cost = $20.25  
Tax = $4.25  
Discounts = $4.00  
TOTAL = $20.50
>>>

Case #3:  
Tax = 21%, discount = 15%, UPC discount = 7% for UPC=12345, additive discounts, cap = 30%  
Tax amount = $20.25 * 21% = $4.25, discounts = $20.25 * 15% + $20.25 * 7% = $3.04 + $1.42 = $4.46, cap = $20.25 * 30% = $6.08

Program prints:
>>>
Cost = $20.25  
Tax = $4.25  
Discounts = $4.46  
TOTAL = $20.04
>>>

**CURRENCY**

Customer is happy to announce expansion to other markets.  
New request is to support currencies other than US dollar.  
Currencies should be indicated using ISO-3 codes (e.g. USD, GBP, JPY, etc.).  

Definition of done:

Case #1:
Sample product: Title = “The Little Prince”, UPC=12345, price=20.25 USD.
Tax = 20%, no discounts

Program prints:
>>>
Cost = 20.25 USD  
Tax = 4.05 USD  
TOTAL = 24.30 USD
>>>

Case #2:  
Sample product: Title = “The Little Prince”, UPC=12345, price=17.76 GBP.  
Tax = 20%, no discounts  

Program prints:
>>>
Cost = 17.76 GBP  
Tax = 3.55 GBP  
TOTAL = 21.31 GBP
>>>

**PRECISION**

New request comes from the accounting department. All money-related calculations must be performed with four decimal digits precision and then rounded to two decimal digits before becoming final.  
This means, for example, that all discounts, taxes and expenses must be calculated and combined with higher precision and then each final line rounded to lower precision (see example below).

Definition of done:  
Sample product: Title = “The Little Prince”, UPC=12345, price=20.25 USD.  
Tax = 21%, universal discount = 15%, UPC discount = 7% for UPC=12345, discounts are multiplicative and after tax  
Transport cost = 3% of base price

Tax amount = 20.25 USD * 21% = 4.2525 USD  
Universal discount = 20.25 USD * 15% = 3.0375 USD  
UPC discount = (20.25 USD – 3.0375 USD) * 7% = 1.2049 USD  
Total discount = 3.0375 USD + 1.2049 USD = 4.2424 USD  
Transport cost = 20.25 USD * 3% = 0.6075 USD  

Program prints:
>>>
Cost = 20.25 USD  
Tax = 4.25 USD  
Discounts = 4.24 USD  
Transport = 0.61 USD  
TOTAL = 20.87 USD
>>>

**CONFIGURATOR (bonus level)**

Customer wants to be able to configure all elements of the price calculator: tax, discounts, combination method, cap, expenses.  
You are free to choose the configuration method – plain text file, XML file, JSON file, database, etc.

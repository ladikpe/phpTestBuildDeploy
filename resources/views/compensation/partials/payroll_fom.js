const gross_pay = 0;
const taxable_income =
  gross_pay -
  (0.01 * gross_pay > 200000 / 12
    ? 0.01 * gross_pay + 0.2 * gross_pay
    : 200000 / 12 + 0.2 * gross_pay) -
  pension_fund;
const tax =
  taxable_income <= 300000 / 12
    ? 0.07 * taxable_income
    : taxable_income > 300000 / 12 && taxable_income <= 600000 / 12
    ? 21000 / 12 + (taxable_income - 300000 / 12) * 0.11
    : taxable_income > 600000 / 12 && taxable_income <= 1100000 / 12
    ? 54000 / 12 + (taxable_income - 600000 / 12) * 0.15
    : taxable_income > 1100000 / 12 && taxable_income <= 1600000 / 12
    ? 129000 / 12 + (taxable_income - 1100000 / 12) * 0.19
    : taxable_income > 1600000 / 12 && taxable_income <= 3200000 / 12
    ? 224000 / 12 + (taxable_income - 1600000 / 12) * 0.21
    : 560000 / 12 + (taxable_income - 3200000 / 12) * 0.24;

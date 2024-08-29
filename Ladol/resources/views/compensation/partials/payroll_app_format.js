const tax =
  gross_pay -
    (0.01 * gross_pay > 200000 / 12
      ? 0.01 * gross_pay + 0.2 * gross_pay
      : 200000 / 12 + 0.2 * gross_pay) -
    pension_fund <=
  300000 / 12
    ? 0.07 *
      (gross_pay -
        (0.01 * gross_pay > 200000 / 12
          ? 0.01 * gross_pay + 0.2 * gross_pay
          : 200000 / 12 + 0.2 * gross_pay) -
        pension_fund)
    : gross_pay -
        (0.01 * gross_pay > 200000 / 12
          ? 0.01 * gross_pay + 0.2 * gross_pay
          : 200000 / 12 + 0.2 * gross_pay) -
        pension_fund >
        300000 / 12 &&
      gross_pay -
        (0.01 * gross_pay > 200000 / 12
          ? 0.01 * gross_pay + 0.2 * gross_pay
          : 200000 / 12 + 0.2 * gross_pay) -
        pension_fund <=
        600000 / 12
    ? 21000 / 12 +
      (gross_pay -
        (0.01 * gross_pay > 200000 / 12
          ? 0.01 * gross_pay + 0.2 * gross_pay
          : 200000 / 12 + 0.2 * gross_pay) -
        pension_fund -
        300000 / 12) *
        0.11
    : gross_pay -
        (0.01 * gross_pay > 200000 / 12
          ? 0.01 * gross_pay + 0.2 * gross_pay
          : 200000 / 12 + 0.2 * gross_pay) -
        pension_fund >
        600000 / 12 &&
      gross_pay -
        (0.01 * gross_pay > 200000 / 12
          ? 0.01 * gross_pay + 0.2 * gross_pay
          : 200000 / 12 + 0.2 * gross_pay) -
        pension_fund <=
        1100000 / 12
    ? 54000 / 12 +
      (gross_pay -
        (0.01 * gross_pay > 200000 / 12
          ? 0.01 * gross_pay + 0.2 * gross_pay
          : 200000 / 12 + 0.2 * gross_pay) -
        pension_fund -
        600000 / 12) *
        0.15
    : gross_pay -
        (0.01 * gross_pay > 200000 / 12
          ? 0.01 * gross_pay + 0.2 * gross_pay
          : 200000 / 12 + 0.2 * gross_pay) -
        pension_fund >
        1100000 / 12 &&
      gross_pay -
        (0.01 * gross_pay > 200000 / 12
          ? 0.01 * gross_pay + 0.2 * gross_pay
          : 200000 / 12 + 0.2 * gross_pay) -
        pension_fund <=
        1600000 / 12
    ? 129000 / 12 +
      (gross_pay -
        (0.01 * gross_pay > 200000 / 12
          ? 0.01 * gross_pay + 0.2 * gross_pay
          : 200000 / 12 + 0.2 * gross_pay) -
        pension_fund -
        1100000 / 12) *
        0.19
    : gross_pay -
        (0.01 * gross_pay > 200000 / 12
          ? 0.01 * gross_pay + 0.2 * gross_pay
          : 200000 / 12 + 0.2 * gross_pay) -
        pension_fund >
        1600000 / 12 &&
      gross_pay -
        (0.01 * gross_pay > 200000 / 12
          ? 0.01 * gross_pay + 0.2 * gross_pay
          : 200000 / 12 + 0.2 * gross_pay) -
        pension_fund <=
        3200000 / 12
    ? 224000 / 12 +
      (gross_pay -
        (0.01 * gross_pay > 200000 / 12
          ? 0.01 * gross_pay + 0.2 * gross_pay
          : 200000 / 12 + 0.2 * gross_pay) -
        pension_fund -
        1600000 / 12) *
        0.21
    : 560000 / 12 +
      (gross_pay -
        (0.01 * gross_pay > 200000 / 12
          ? 0.01 * gross_pay + 0.2 * gross_pay
          : 200000 / 12 + 0.2 * gross_pay) -
        pension_fund -
        3200000 / 12) *
        0.24;

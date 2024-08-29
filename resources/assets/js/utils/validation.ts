import { Rule } from "antd/lib/form";

export const reqValidationRules: Rule[] = [{ required: true }];
export const generalValidationRules: Rule[] = [
  { required: true },

  { message: "Field is required!" },
];
export const generalValidationRulesOp: Rule[] = [
  { required: false },

  { message: "Field is required!" },
];

export const textInputValidationRulesOp: Rule[] = [
  ...generalValidationRulesOp,

  { whitespace: true },
];
// min
export const textInputValidationRules: Rule[] = [
  ...generalValidationRules,

  { whitespace: true },
];

export const emailValidationRules: Rule[] = [
  {
    message: "Field is required",
  },
  { required: true },

  {
    type: "email",

    message: "Invalid Email Address",
  },
];
export const emailValidationRulesOp: Rule[] = [
  {
    message: "Field is required",
  },
  { required: false },

  {
    type: "email",

    message: "Invalid Email Address",
  },
];

export const passwordValidationRules: Rule[] = [
  {
    required: true,
  },
  { message: "Field is required" },

  {
    validator: async (rule, value) => {
      let paswd = /^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]/;

      if (!value.match(paswd))
        throw new Error(
          "Password should contain at least one digit and special character and a letter in uppercase, and least 8 characters"
        );
      // if (false) throw new Error("Something wrong!");
      return true;
    },
  },
];

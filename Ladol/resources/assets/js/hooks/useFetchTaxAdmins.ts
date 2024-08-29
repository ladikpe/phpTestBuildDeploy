import axios from "axios";
import { useState, useEffect } from "react";
import { API_TOKEN } from "../constants";

export type TTaxAdmin = {
  id: number;
  name: string;
  users: User[];
};

interface User {
  id: number;
  emp_num: string;
  name: string;
  nin_no: string;
  bvn?: string;
  tax_id?: string;
  tax_authority: string;
  pension_id: string;
  pension_administrator: string;
  pension_type: string;
  medical_code?: any;
  alt_email?: string;
  email?: string;
  sex: string;
  dob: string;
  phone: string;
  alt_phone?: string;
  marital_status: string;
  image: string;
  address: string;
  hiredate: string;
  job_id: number;
  role_id: number;
  branch_id: number;
  company_id: number;
  superadmin: number;
  status: number;
  session_id?: string;
  country_id: number;
  state_id: number;
  lga_id: number;
  bank_id: number;
  bank_account_no: string;
  employment_status: string;
  staff_category_id?: any;
  department_id: number;
  grade_id: number;
  line_manager_id: number;
  payroll_type: string;
  project_salary_category_id?: number;
  last_login_at?: string;
  last_login_ip?: string;
  union_id: number;
  section_id: number;
  expat: number;
  non_payroll_provision_employee: number;
  confirmation_date: string;
  image_id?: any;
  last_promoted?: any;
  first_name: string;
  middle_name?: any;
  last_name: string;
  active: number;
  EDLEVEL?: any;
  account_num?: any;
  age?: any;
  bank_code?: any;
  bank_name?: any;
  confirmed?: any;
  dribble?: any;
  facebook?: any;
  grade?: any;
  instagram?: any;
  job_app_id?: any;
  job_reg_status?: any;
  kin_address?: any;
  kin_phonenum?: any;
  kin_relationship?: any;
  last_grade_change_date?: any;
  lga?: any;
  linemanager_id?: any;
  locale?: any;
  locked?: any;
  next_of_kin?: any;
  phone_num?: any;
  prev_grade?: any;
  sort_code?: any;
  state_origin_id?: any;
  twitter?: any;
  workdept_id?: any;
  religion?: any;
  probation_period?: string;
  created_at: string;
  updated_at: string;
  performance_category_id: number;
  bc_sync?: any;
  pali365_sync?: any;
  uses_pc: number;
  direct_salary_id?: any;
  years_of_service: number;
  months_of_service: number;
  user_image: string;
  my_status: string;
  company?: Company;
}

interface Company {
  id: number;
  name: string;
  email: string;
  address: string;
  user_id: number;
  logo: string;
  is_parent: number;
  color: string;
  biometric_serial?: any;
  created_at: string;
  updated_at: string;
  bc_id?: any;
  probationpolicy: Probationpolicy;
}

interface Probationpolicy {
  id: number;
  probation_period: string;
  probation_reminder: string;
  automatic_probation: string;
  notify_roles: string;
  created_by: number;
  company_id: number;
  created_at: string;
  updated_at: string;
}

const getTaxAdmins = async (): Promise<{
  data: TTaxAdmin[];
  total: number;
}> => {
  const url = `/api/tax-admins`;

  const config = {
    headers: {
      Authorization: `Bearer `,
    },
    params: {
      token: API_TOKEN,
    },
  };

  const res = await axios.get(url, config);
  const fetchedData = res.data;
  const result = fetchedData.data;

  const data: TTaxAdmin[] = result.map(
    (item: TTaxAdmin): TTaxAdmin => ({
      ...item, //adheres to backend
    })
  );

  const ans = {
    data,
    total: fetchedData.total,
  };

  return ans;
};

type TState = "loading" | "success" | "error";
export const useFetchTaxAdmins = () => {
  const [state, setState] = useState<TState>("loading");
  const [total, setTotal] = useState<number>(0);
  const [data, setData] = useState<TTaxAdmin[]>([]);
  useEffect(() => {
    setState("loading");

    getTaxAdmins()
      .then((res) => {
        setData(() => res.data);
        setState(() => "success");
        setTotal(() => res.total);
      })
      .catch(() => {
        setState(() => "error");
      });
  }, []);

  return {
    data,
    setData,
    state,
    total,
  };
};

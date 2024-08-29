import axios from "axios";
import { API_TOKEN } from "../constants";
import Cookies from "js-cookie";

type TDelProps = {
  id: number;
};
interface TResponse {
  message: string;
  data?: any;
  success: boolean;
}
const csrfToken = Cookies.get("XSRF-TOKEN");
export const deleteTaxAdmin = async (props: {
  data: TDelProps;
}): Promise<TResponse> => {
  const url = `/api/delete-tax-admin/${props.data.id}`;
  const config = {
    headers: {
      "X-CSRF-TOKEN": csrfToken,
    },
  };

  const response = await axios.delete(url, config);
  const ans: TResponse = response?.data;
  return ans;
};

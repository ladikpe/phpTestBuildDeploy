import axios from "axios";
import { API_TOKEN } from "../constants";
import Cookies from "js-cookie";

type TCreateProps = {
  name: string;
  id?: number;
};
interface TResponse {
  message: string;
  data?: any;
  success: boolean;
}
const csrfToken = Cookies.get("XSRF-TOKEN");
export const savePensionFundAdmin = async (props: {
  data: TCreateProps;
}): Promise<TResponse> => {
  const url = `/api/save-pension-fund-admin`;
  const config = {
    headers: {
      "X-CSRF-TOKEN": csrfToken,
    },
  };

  const data: TCreateProps = {
    name: props.data.name,
    id: props.data.id,
  };

  const response = await axios.post(url, data, config);
  const ans: TResponse = response?.data;
  return ans;
};

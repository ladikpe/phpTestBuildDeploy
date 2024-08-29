import axios from "axios";
import { API_TOKEN } from "../constants";
import Cookies from "js-cookie";
import { TPayscale } from "../hooks/useFetchPayscales";

type TCreateProps = {
  level_code: string;
  min_val: number;
  max_val: number;
  id?: number;
};
interface TResponse {
  message: string;
  data?: TPayscale;
  success: boolean;
}
const csrfToken = Cookies.get("XSRF-TOKEN");
export const savePayscale = async (props: {
  data: TCreateProps;
}): Promise<TResponse> => {
  const url = `/api/save-payscale`;
  const config = {
    headers: {
      "X-CSRF-TOKEN": csrfToken,
    },
  };

  const data: TCreateProps = {
    level_code: props.data.level_code,
    min_val: props.data.min_val,
    max_val: props.data.max_val,
    id: props.data.id,
  };

  const response = await axios.post(url, data, config);
  const ans: TResponse = response?.data;
  return ans;
};

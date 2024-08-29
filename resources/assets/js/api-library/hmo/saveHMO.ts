import axios from "axios";
import { API_TOKEN } from "../../constants";
import Cookies from "js-cookie";
import { THMO } from "../../types/hmo";

type TCreateProps = {
  hmo: string;
  description: string;

  id?: number;
};
interface TResponse {
  message: string;
  data?: THMO;
  success: boolean;
}
const csrfToken = Cookies.get("XSRF-TOKEN");
export const saveHMO = async (props: {
  data: TCreateProps;
}): Promise<TResponse> => {
  const url = `/api/hmo/save-hmo`;
  const config = {
    headers: {
      "X-CSRF-TOKEN": csrfToken,
    },
  };

  const data: TCreateProps = {
    hmo: props.data.hmo,
    description: props.data.description,
    id: props.data.id,
  };

  const response = await axios.post(url, data, config);
  const ans: TResponse = response?.data;
  return ans;
};

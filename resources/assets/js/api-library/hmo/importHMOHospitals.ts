import axios from "axios";
import { API_TOKEN } from "../../constants";
import Cookies from "js-cookie";
import { THMOHospital } from "../../types/hmo";

type TCreateProps = {
  hospitals: any;
};
interface TResponse {
  message: string;
  data?: THMOHospital[];
  success: boolean;
}
const csrfToken = Cookies.get("XSRF-TOKEN");
export const importHMOHospitals = async (props: {
  data: TCreateProps;
}): Promise<TResponse> => {
  const url = `/api/hmo/import-hmo-hospitals`;
  const config = {
    headers: {
      "X-CSRF-TOKEN": csrfToken,
    },
  };

  let formData = new FormData();
  formData.append("hospitals", props.data.hospitals);

  const response = await axios.post(url, formData, config);
  const ans: TResponse = response?.data;
  return ans;
};

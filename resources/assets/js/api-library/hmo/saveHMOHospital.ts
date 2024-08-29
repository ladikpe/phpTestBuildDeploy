import axios from "axios";
import { API_TOKEN } from "../../constants";
import Cookies from "js-cookie";
import { THMOHospital } from "../../types/hmo";

type TCreateProps = {
  hospital: string;
  category: string;
  band: string;
  address: string;
  contact: string;
  hmo: number[];

  id?: number;
};
interface TResponse {
  message: string;
  data?: THMOHospital;
  success: boolean;
}
const csrfToken = Cookies.get("XSRF-TOKEN");
export const saveHMOHospital = async (props: {
  data: TCreateProps;
}): Promise<TResponse> => {
  const url = `/api/hmo/save-hmo-hospital`;
  const config = {
    headers: {
      "X-CSRF-TOKEN": csrfToken,
    },
  };

  const data: TCreateProps = {
    hmo: props.data.hmo,
    hospital: props.data.hospital,
    category: props.data.category,
    band: props.data.band,
    contact: props.data.contact,
    address: props.data.address,
    id: props.data.id,
  };

  const response = await axios.post(url, data, config);
  const ans: TResponse = response?.data;
  return ans;
};

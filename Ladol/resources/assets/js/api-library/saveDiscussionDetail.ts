import axios from "axios";
import { API_TOKEN } from "../constants";
import Cookies from "js-cookie";
import { TPerformanceDiscussion } from "../types/performance-discussion";

type TCreateProps = {
  action_update: string;
  challenges: string;
  comment: string;

  detailId: number;
  type: string;
};
interface TResponse {
  message: string;
  data?: TPerformanceDiscussion;
  success: boolean;
}
const csrfToken = Cookies.get("XSRF-TOKEN");
export const saveDiscussionDetail = async (props: {
  data: TCreateProps;
}): Promise<TResponse> => {
  const url = `/api/bsceval-store`;
  const config = {
    headers: {
      "X-CSRF-TOKEN": csrfToken,
    },
  };

  const data: TCreateProps = {
    detailId: props.data.detailId,
    action_update: props.data.action_update,
    challenges: props.data.challenges,
    comment: props.data.comment,

    type: props.data.type,
  };

  const response = await axios.post(url, data, config);
  const ans: TResponse = response?.data;
  return ans;
};

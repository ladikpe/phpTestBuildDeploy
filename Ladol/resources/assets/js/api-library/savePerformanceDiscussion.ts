import axios from "axios";
import { API_TOKEN } from "../constants";
import Cookies from "js-cookie";
import { TPerformanceDiscussion } from "../types/performance-discussion";

type TCreateProps = {
  title: string;
  discussion?: string;
  participantId?: number;
  evaluationId: number;
  type: string;
};
interface TResponse {
  message: string;
  data?: TPerformanceDiscussion;
  success: boolean;
}
const csrfToken = Cookies.get("XSRF-TOKEN");
export const savePerformanceDiscussion = async (props: {
  data: TCreateProps;
}): Promise<TResponse> => {
  const url = `/api/bsceval-store`;
  const config = {
    headers: {
      "X-CSRF-TOKEN": csrfToken,
    },
  };

  const data: TCreateProps = {
    evaluationId: props.data.evaluationId,
    participantId: props.data.participantId,
    title: props.data.title,
    discussion: props.data.discussion,

    type: props.data.type,
  };

  const response = await axios.post(url, data, config);
  const ans: TResponse = response?.data;
  return ans;
};

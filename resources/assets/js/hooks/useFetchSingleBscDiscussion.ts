import axios from "axios";
import { API_TOKEN } from "../constants";
import { useEffect, useState } from "react";
import Cookies from "js-cookie";
import { TPerformanceDiscussion } from "../types/performance-discussion";

const csrfToken = Cookies.get("XSRF-TOKEN");

const getData = async (
  participantId: number,
  evaluationId: number
): Promise<{
  data: TPerformanceDiscussion;
}> => {
  const url = `/api/get-bsc-discussion`;

  const config = {
    headers: {
      "X-CSRF-TOKEN": csrfToken,
    },
    params: {
      token: API_TOKEN,
      evaluationId,
      participantId,
      type: "getSingleDiscussionAPI",
    },
  };

  const res = await axios.get(url, config);
  console.log(res, "discuss");

  const data: TPerformanceDiscussion = res.data.data;

  return { data };
};

type TState = "loading" | "success" | "error";
export const useFetchSingleBscDiscussion = (
  participantId: number,
  evaluationId: number
) => {
  const [state, setState] = useState<TState>("loading");
  const [data, setData] = useState<TPerformanceDiscussion | null>(null);
  const [refresh, setRefresh] = useState(false);
  useEffect(() => {
    setState("loading");

    getData(participantId, evaluationId)
      .then((res) => {
        setData(() => res.data);
        setState(() => "success");
      })
      .catch(() => {
        setState(() => "error");
      });
  }, [refresh]);

  return {
    data,
    setData,
    state,
    setRefresh,
  };
};

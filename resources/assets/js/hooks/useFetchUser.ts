import axios from "axios";
import { API_TOKEN } from "../constants";
import { useEffect, useState } from "react";
import Cookies from "js-cookie";
import { TUser } from "../types/user";

const csrfToken = Cookies.get("XSRF-TOKEN");

const getUser = async (
  id: number
): Promise<{
  data: TUser;
}> => {
  const url = `/api/user-by-id`;

  const config = {
    headers: {
      "X-CSRF-TOKEN": csrfToken,
    },
    params: {
      token: API_TOKEN,
    },
  };

  const res = await axios.get(url, config);
  console.log("reeerers", res.data, res.data.data);

  const data: TUser = res.data.data;

  return { data };
};

type TState = "loading" | "success" | "error";
export const useFetchUser = (id: number) => {
  const [state, setState] = useState<TState>("loading");
  const [data, setData] = useState<TUser | null>(null);
  useEffect(() => {
    setState("loading");

    getUser(id)
      .then((res) => {
        setData(() => res.data);
        setState(() => "success");
      })
      .catch(() => {
        setState(() => "error");
      });
  }, []);

  return {
    data,
    setData,
    state,
  };
};

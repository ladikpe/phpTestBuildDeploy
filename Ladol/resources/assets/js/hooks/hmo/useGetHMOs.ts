import axios from "axios";
import { useState, useEffect } from "react";
import { API_TOKEN } from "../../constants";
import { THMO } from "../../types/hmo";




const getData = async (): Promise<{
  data: THMO[];
  total: number;
}> => {
  const url = `/api/hmo/index`;

  const config = {
    headers: {
      Authorization: `Bearer `,
    },
    params: {
      token: API_TOKEN,
    },
  };

  const res = await axios.get(url, config);
  const fetchedData = res.data;
  const result = fetchedData.data;
  console.log(res, 'NEW')

  const data: THMO[] = result.map(
    (item: THMO): THMO => ({
      ...item, //adheres to backend
    })
  );

  const ans = {
    data,
    total: fetchedData.total,
  };

  return ans;
};

type TState = "loading" | "success" | "error";
export const useGetHMOs = () => {
  const [state, setState] = useState<TState>("loading");
  const [total, setTotal] = useState<number>(0);
  const [data, setData] = useState<THMO[]>([]);
  useEffect(() => {
    setState("loading");

    getData()
      .then((res) => {
        setData(() => res.data);
        setState(() => "success");
        setTotal(() => res.total);
      })
      .catch(() => {
        setState(() => "error");
      });
  }, []);

  return {
    data,
    setData,
    state,
    total,
  };
};

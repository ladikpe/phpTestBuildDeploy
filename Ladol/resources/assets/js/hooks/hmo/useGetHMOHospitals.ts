import axios from "axios";
import { useState, useEffect } from "react";
import { API_TOKEN } from "../../constants";
import { THMOHospital } from "../../types/hmo";




const getData = async (): Promise<{
  data: THMOHospital[];
  total: number;
}> => {
  const url = `/api/hmo/hospitals`;

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
  console.log(res, 'Hospitals')

  const data: THMOHospital[] = result.map(
    (item: THMOHospital): THMOHospital => ({
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
export const useGetHMOHospitals = () => {
  const [state, setState] = useState<TState>("loading");
  const [total, setTotal] = useState<number>(0);
  const [data, setData] = useState<THMOHospital[]>([]);
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

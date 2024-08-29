import { Tabs } from "antd";
import React from "react";
import ReactDOM from "react-dom";
import { HMOHospitals } from "./HMOHospitals";
import { HMOs } from "./HMOs";


export default function HMOSetupContainer() {
  return (
    <>
      <Tabs
        items={[
            { label: "Hospitals", key: "Hospitals", children: <HMOHospitals /> }, 
        { label: "HMO", key: "HMO", children: <HMOs /> }]}
      />
    </>
  );
}

if (document.getElementById("hmo-setup-container")) {
  ReactDOM.render(
    <HMOSetupContainer />,
    document.getElementById("hmo-setup-container")
  );
}

import ReactDOM from "react-dom";

import { Button } from "antd";
import React, { useState } from "react";
import { PerformanceDiscussionDrawer } from "./PerformanceDiscussionDrawer";

export const PerformanceDiscussion = () => {
  const [showD, setShowD] = useState(false);

  return (
    <>
      <button className="btn btn-default" onClick={() => setShowD(true)}>
        Performance Discussion
      </button>

      <PerformanceDiscussionDrawer
        open={showD}
        onClose={() => setShowD(false)}
      />
    </>
  );
};

if (document.getElementById("performance-btn")) {
  ReactDOM.render(
    <PerformanceDiscussion />,
    document.getElementById("performance-btn")
  );
}

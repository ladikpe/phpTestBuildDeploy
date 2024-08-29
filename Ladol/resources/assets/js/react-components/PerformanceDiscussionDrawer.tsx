import { Drawer, Form } from "antd";
import React from "react";
import { TEvaluation } from "../types/evaluation";
import { TUser } from "../types/user";
import { ParticipantDiscussion } from "./ParticipantDiscussion";

declare global {
  interface Window {
    evaluationData: TEvaluation;
    userData: TUser;
  }
}

export const PerformanceDiscussionDrawer: React.FC<{
  open: boolean;
  onClose: () => void;
}> = ({ open, onClose }) => {
  // Accessing the data passed from Laravel
  const evaluationData = window.evaluationData;
  const userData = window.userData;
  console.log("auth", evaluationData, userData);
  return (
    <>
      {evaluationData && userData ? (
        <div>
          <Drawer
            open={open}
            onClose={onClose}
            width={"65%"}
            zIndex={1000000}
            title="Performance Discussion"
          >
            <div className="flex flex-col gap-4">
              <ParticipantDiscussion
                title="Employee"
                evaluation={evaluationData}
                participantId={evaluationData?.user_id}
                disabled={evaluationData?.user_id !== userData?.id}
              />
              <ParticipantDiscussion
                title="Manager"
                evaluation={evaluationData}
                participantId={evaluationData?.user.line_manager_id}
                disabled={evaluationData?.user.line_manager_id !== userData?.id}
              />
              <ParticipantDiscussion
                title="Head of Hr"
                evaluation={evaluationData}
                participantId={
                  evaluationData?.measurement_period?.head_of_hr_id
                }
                disabled={
                  evaluationData?.measurement_period?.head_of_hr_id !==
                  userData?.id
                }
              />
            </div>
          </Drawer>
        </div>
      ) : null}
    </>
  );
};

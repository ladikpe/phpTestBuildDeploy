import { Form, Collapse, Input, Button } from "antd";
import React, { useEffect, useState } from "react";
import { openNotification } from "../utils/notifcations";
import { TEvaluation } from "../types/evaluation";
import { DiscussionDetailsTable } from "./DiscussionDetailsTable";
import { useFetchSingleBscDiscussion } from "../hooks/useFetchSingleBscDiscussion";
import { savePerformanceDiscussion } from "../api-library/savePerformanceDiscussion";

export const ParticipantDiscussion: React.FC<{
  disabled: boolean;
  evaluation: TEvaluation;
  participantId: number;
  title: string;
}> = ({ disabled, evaluation, participantId, title }) => {
  const [form] = Form.useForm();
  const [loading, setLoading] = useState(false);
  const { data, setData, setRefresh } = useFetchSingleBscDiscussion(
    participantId,
    evaluation.id
  );
  useEffect(() => {
    if (data) {
      form.setFieldsValue({
        title: data.title,
        discussion: data.discussion,
      });
    }
  }, [data, form]);
  const handleSubmit = (data: any) => {
    setLoading(true);
    savePerformanceDiscussion({
      data: {
        participantId,
        evaluationId: evaluation.id,
        title: data.title,
        discussion: data.discussion,
        type: "saveDiscussionAPI",
      },
    })
      .then((res) => {
        openNotification({
          state: res.success ? "success" : "error",
          title: res.success ? "Success" : "Error",
          description: res.message,
        });
        console.log(res, "suss");
        setData(data.data);
        setLoading(false);
      })
      .catch((err) => {
        openNotification({
          state: "error",
          title: "Error",
          description: JSON.stringify(err),
        });
        console.log(err, "err");
        setLoading(false);
      });
  };
  return (
    <div className="mb-4" style={{ marginBottom: "20px" }}>
      <Collapse>
        <Collapse.Panel header={title} key={title}>
          <Form
            form={form}
            layout="vertical"
            disabled={disabled}
            onFinish={handleSubmit}
          >
            <Form.Item name={"title"} label="Title">
              <Input />
            </Form.Item>
            <Form.Item name={"discussion"} label="Discussion">
              <Input.TextArea rows={4} />
            </Form.Item>
            <div className="flex justify-end">
              <Button loading={loading} htmlType="submit">
                Save
              </Button>
            </div>
          </Form>

          <div style={{ marginTop: "20px" }}>
            <DiscussionDetailsTable
              data={data?.discussion_details}
              disableEdit={disabled}
              setRefresh={() => setRefresh((val) => !val)}
            />
          </div>
        </Collapse.Panel>
      </Collapse>
    </div>
  );
};

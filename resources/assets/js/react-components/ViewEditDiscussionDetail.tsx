import React, { useEffect, useState } from "react";
import { TDiscussiondetail } from "../types/performance-discussion";
import { Button, Form, Input, Modal } from "antd";
import { saveDiscussionDetail } from "../api-library/saveDiscussionDetail";
import { openNotification } from "../utils/notifcations";

export const ViewEditDiscussionDetail: React.FC<{
  data: TDiscussiondetail;
  open: boolean;
  onClose: () => void;
  disableEdit: boolean;
  setRefresh: () => void;
}> = ({ data, disableEdit, onClose, open, setRefresh }) => {
  const [form] = Form.useForm();
  useEffect(() => {
    form.setFieldsValue({
      focus: data.evaluation_detail.focus,
      action_update: data.action_update,
      comment: data.comment,
      challenges: data.challenges,
    });
  }, [form, data]);
  const [loading, setLoading] = useState(false);

  const handleSubmit = (values: any) => {
    setLoading(true);
    saveDiscussionDetail({
      data: {
        detailId: data.id,
        action_update: values.action_update,
        challenges: values.challenges,
        comment: values.comment,
        type: "saveDiscussionDetailAPI",
      },
    })
      .then((res) => {
        openNotification({
          state: res.success ? "success" : "error",
          title: res.success ? "Success" : "Error",
          description: res.message,
        });
        console.log(res, "suss");
        setLoading(false);
        setRefresh();
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
    <Modal
      title="View/Edit Detail"
      footer={null}
      open={open}
      onCancel={() => onClose()}
      style={{ top: 10 }}
      zIndex={20000000}
    >
      <Form
        form={form}
        layout="vertical"
        disabled={disableEdit}
        size="small"
        onFinish={handleSubmit}
      >
        <Form.Item label="Focus" name="focus">
          <Input disabled />
        </Form.Item>
        <Form.Item label="Action/Update" name="action_update">
          <Input placeholder="Action/Update" />
        </Form.Item>
        <Form.Item label="Challenges" name="challenges">
          <Input.TextArea rows={3} placeholder="Challenges" />
        </Form.Item>
        <Form.Item label="Comment" name="comment">
          <Input.TextArea rows={3} placeholder="Comment" />
        </Form.Item>

        <div>
          <Button htmlType="submit" loading={loading}>
            Save
          </Button>
        </div>
      </Form>
    </Modal>
  );
};

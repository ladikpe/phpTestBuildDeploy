import React, { useState } from "react";
import { TDiscussiondetail } from "../types/performance-discussion";
import Table, { ColumnsType } from "antd/lib/table";
import { Button } from "antd";
import { EditOutlined } from "@ant-design/icons";
import { ViewEditDiscussionDetail } from "./ViewEditDiscussionDetail";

export const DiscussionDetailsTable: React.FC<{
  data?: TDiscussiondetail[];
  disableEdit: boolean;
  setRefresh: () => void;
}> = ({ data = [], disableEdit, setRefresh }) => {
  const [selEntity, setSelEntity] = useState<TDiscussiondetail>();
  let columns: ColumnsType<TDiscussiondetail> = [
    {
      title: "Focus",
      dataIndex: "name",
      key: "name",
      render: (_, item) => item.evaluation_detail.focus,
    },
    {
      title: "Action/Update",
      dataIndex: "act_up",
      key: "act_up",
      render: (_, item) => item.action_update,
    },
    {
      title: "Challenges",
      dataIndex: "chall",
      key: "chall",
      render: (_, item) => item.challenges,
    },
    {
      title: "Comments",
      dataIndex: "comments",
      key: "comments",
      render: (_, item) => item.comment,
    },

    {
      title: "",
      dataIndex: "action",
      render: (_, item) => (
        <Button
          icon={<EditOutlined />}
          size="small"
          type="text"
          onClick={() => setSelEntity(item)}
        />
      ),
    },
  ];

  return (
    <>
      {selEntity && (
        <ViewEditDiscussionDetail
          data={selEntity}
          open={!!selEntity}
          onClose={() => setSelEntity(undefined)}
          disableEdit={disableEdit}
          setRefresh={setRefresh}
        />
      )}
      <Table
        columns={columns}
        size="small"
        dataSource={data}
        pagination={{ total: data.length, pageSize: 4 }}
      />
    </>
  );
};

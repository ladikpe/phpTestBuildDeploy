import {
  Table,
  Typography,
  Button,
  Modal,
  Form,
  Input,
  InputNumber,
} from "antd";
import React, { useState } from "react";
import ReactDOM from "react-dom";

import { ColumnsType } from "antd/lib/table";
import {
  generalValidationRules,
  textInputValidationRules,
} from "../utils/validation";
import { DeleteFilled, EditFilled } from "@ant-design/icons";

import { openNotification } from "../utils/notifcations";
import { TPayscale, useFetchPayscales } from "../hooks/useFetchPayscales";
import { savePayscale } from "../api-library/savePayscale";
import { deletePayScale } from "../api-library/deletePayScale";

export default function PayscalesContainer() {
  const [form] = Form.useForm();
  const { data, state, total, setData } = useFetchPayscales();
  const [addPFA, setPFA] = useState(false);
  const [id, setId] = useState<number>();
  const [isDeleting, setIsDeleting] = useState(false);
  const [isSaving, setIsSaving] = useState(false);
  const handleSubmit = (data: any) => {
    setIsSaving(true);

    savePayscale({
      data: {
        max_val: data?.max_val,
        level_code: data?.level_code,
        min_val: data?.min_val,
        id,
      },
    })
      .then((res) => {
        openNotification({
          state: res.success ? "success" : "error",
          title: res.success ? "Success" : "Error",
          description: res.message,
        });
        // if id update the table
        if (id) {
          if (res.data) {
            setData((prev) =>
              prev.map((item) =>
                item.id === id
                  ? {
                      ...item,
                      max_val: res.data?.max_val ?? 0,
                      level_code: res.data?.level_code ?? "",
                      min_val: res.data?.min_val ?? 0,
                      created_at: res.data?.created_at ?? "",
                      updated_at: res.data?.updated_at ?? "",
                    }
                  : item
              )
            );
          }
        } else {
          if (res.data) {
            setData((prev) => [
              ...prev,
              {
                id: res?.data?.id ?? 0,
                max_val: res.data?.max_val ?? 0,
                level_code: res.data?.level_code ?? "",
                min_val: res.data?.min_val ?? 0,
                created_at: res.data?.created_at ?? "",
                updated_at: res.data?.updated_at ?? "",
                grades: res.data?.grades ?? [],
              },
            ]);
          }
        }

        setPFA(false);
        setId(undefined);
        form.resetFields();
        setIsSaving(false);
      })
      .catch((err) => {
        openNotification({
          state: "error",
          title: "Error",
          description: JSON.stringify(err),
        });
        setPFA(false);
        setId(undefined);
        form.resetFields();
        setIsSaving(false);
      });
  };
  const handleEdit = (data: TPayscale) => {
    setId(data.id);
    form.setFieldsValue({
      max_val: data?.max_val,
      level_code: data?.level_code,
      min_val: data?.min_val,
    });
    setPFA(true);
  };
  const handleDelete = (id: number) => {
    setId(id);
    setIsDeleting(true);
    deletePayScale({ data: { id } })
      .then((res) => {
        if (res.success) {
          setData((prev) => prev.filter((item) => item.id !== id));
        }
        openNotification({
          state: res.success ? "success" : "error",
          title: res.success ? "Success" : "Error",
          description: res.message,
        });
        setId(undefined);
        setIsDeleting(false);
      })
      .catch((err) => {
        openNotification({
          state: "error",
          title: "Error",
          description: JSON.stringify(err),
        });
        setId(undefined);
        setIsDeleting(false);
      });
  };
  const columns: ColumnsType<TPayscale> = [
    {
      title: "Level Code",
      dataIndex: "name",
      key: "name",
      render: (_, item) => `${item.level_code} `,
    },
    {
      title: "Min",
      dataIndex: "min",
      key: "min",
      render: (_, item) => `₦ ${item.min_val} `,
    },
    {
      title: "Max",
      dataIndex: "max",
      key: "max",
      render: (_, item) => `₦ ${item.max_val} `,
    },
    {
      title: "Grade Count",
      dataIndex: "gradeCount",
      key: "gradeCount",
      render: (_, item) => `${item.grades.length} `,
    },
    {
      title: "User Count",
      dataIndex: "userCount",
      key: "userCount",
      render: (_, item) =>
        ` ${item.grades.reduce((a, b) => a + b.users.length, 0)}`,
    },
    {
      title: "",
      key: "action",
      width: 100,
      render: (_, item) => (
        <div className="flex gap-2">
          <Button
            type="text"
            onClick={() => handleEdit(item)}
            icon={<EditFilled />}
          />
          <Button
            type="text"
            loading={isDeleting && item.id === id}
            onClick={() => handleDelete(item.id)}
            icon={<DeleteFilled />}
          />
        </div>
      ),
    },
  ];
  return (
    <>
      <Modal
        title={`${id ? "Edit" : "Add"} Pay Range`}
        open={addPFA}
        onCancel={() => setPFA(false)}
        footer={null}
        zIndex={11400}
      >
        <Form
          onFinish={handleSubmit}
          size="small"
          form={form}
          style={{ marginTop: "22px" }}
          requiredMark={false}
        >
          <Form.Item
            name="level_code"
            label="Level Code"
            rules={textInputValidationRules}
          >
            <Input placeholder="Enter name" />
          </Form.Item>
          <Form.Item
            name="min_val"
            label="Minimum"
            rules={[{ required: true }]}
          >
            <InputNumber placeholder="Enter min" min={0} className="w-full" />
          </Form.Item>
          <Form.Item
            name="max_val"
            label="Maximum"
            rules={[{ required: true }]}
          >
            <InputNumber placeholder="Enter max" className="w-full" />
          </Form.Item>
          <Button type="primary" htmlType="submit" loading={isSaving}>
            {" "}
            Submit
          </Button>
        </Form>
      </Modal>
      <div className="flex flex-col gap-4">
        <div className="flex justify-between" style={{ marginBottom: "8px" }}>
          <Typography.Title level={4}>Pay Ranges</Typography.Title>
          <Button type="dashed" onClick={() => setPFA(true)} className="mb-4">
            Add Pay Range
          </Button>
        </div>
        <Table
          columns={columns}
          dataSource={data?.map((item) => ({ key: item.id, ...item }))}
          loading={state === "loading"}
          pagination={{ total: data?.length, pageSize: 4 }}
          // size="small"
        />
      </div>
    </>
  );
}

if (document.getElementById("payscale-container")) {
  ReactDOM.render(
    <PayscalesContainer />,
    document.getElementById("payscale-container")
  );
}

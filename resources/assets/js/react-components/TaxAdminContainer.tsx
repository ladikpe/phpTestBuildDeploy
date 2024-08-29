import { Table, Typography, Button, Modal, Form, Input } from "antd";
import { DeleteFilled, EditFilled } from "@ant-design/icons";
import React, { useState } from "react";
import ReactDOM from "react-dom";

import { ColumnsType } from "antd/lib/table";
import { textInputValidationRules } from "../utils/validation";

import { openNotification } from "../utils/notifcations";
import { TTaxAdmin, useFetchTaxAdmins } from "../hooks/useFetchTaxAdmins";
import { saveTaxAdmin } from "../api-library/saveTaxAdmin";
import { deleteTaxAdmin } from "../api-library/deleteTaxAdmin";

export default function TaxAdminContainer() {
  const [form] = Form.useForm();
  const { data, state, total, setData } = useFetchTaxAdmins();
  const [addPFA, setPFA] = useState(false);
  const [id, setId] = useState<number>();
  const [isDeleting, setIsDeleting] = useState(false);
  const [isSaving, setIsSaving] = useState(false);
  const handleSubmit = (data: any) => {
    setIsSaving(true);
    saveTaxAdmin({ data: { name: data.name, id } })
      .then((res) => {
        if (res.success) {
          // if id update the table
          if (id) {
            setData((prev) =>
              prev.map((item) =>
                item.id === id
                  ? {
                      ...item,
                      name: res?.data?.name ?? item.name,
                    }
                  : item
              )
            );
          } else {
            // if not add to table
            setData((prev) => [
              ...prev,
              { name: res?.data?.name, id: res?.data?.id, users: [] },
            ]);
          }
        }
        openNotification({
          state: res.success ? "success" : "error",
          title: res.success ? "Success" : "Error",
          description: res.message,
        });

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
  const handleDelete = (id: number) => {
    setId(id);
    setIsDeleting(true);
    deleteTaxAdmin({ data: { id } })
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
  const handleEdit = (data: TTaxAdmin) => {
    setId(data.id);
    form.setFieldValue("name", data.name);
    setPFA(true);
  };
  const columns: ColumnsType<TTaxAdmin> = [
    {
      title: "Name",
      dataIndex: "name",
      key: "name",
      render: (val) => `${val} `,
    },
    {
      title: "Employees Using",
      dataIndex: "using",
      key: "using",
      render: (_, item) => `${item.users.length} `,
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
        title={`${id ? "Edit" : "Add"} Tax Authority`}
        open={addPFA}
        onCancel={() => setPFA(false)}
        footer={null}
        zIndex={11400}
      >
        <Form
          onFinish={handleSubmit}
          form={form}
          style={{ marginTop: "22px" }}
          requiredMark={false}
        >
          <Form.Item name="name" label="Name" rules={textInputValidationRules}>
            <Input placeholder="Enter name" />
          </Form.Item>
          <Button type="primary" htmlType="submit" loading={isSaving}>
            {" "}
            Submit
          </Button>
        </Form>
      </Modal>
      <div className="flex flex-col gap-4">
        <div
          className="flex justify-between mb-4"
          style={{ marginBottom: "8px" }}
        >
          <Typography.Title level={4}>Tax Authorities</Typography.Title>
          <Button type="dashed" onClick={() => setPFA(true)} className="mb-4">
            Add Tax Authority
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

if (document.getElementById("tax-authority-container")) {
  ReactDOM.render(
    <TaxAdminContainer />,
    document.getElementById("tax-authority-container")
  );
}

import React, { useState } from "react";
import { THMO } from "../../types/hmo";
import Table, { ColumnsType } from "antd/lib/table";
import { Button, Modal, Form, Input } from "antd";
import { EditFilled, DeleteFilled } from "@ant-design/icons";
import { useGetHMOs } from "../../hooks/hmo/useGetHMOs";
import { openNotification } from "../../utils/notifcations";
import { saveHMO } from "../../api-library/hmo/saveHMO";
import { deleteHMO } from "../../api-library/hmo/deleteHMO";
import {
  textInputValidationRules,
  textInputValidationRulesOp,
} from "../../utils/validation";

export const HMOs = () => {
  const { data, state, total, setData } = useGetHMOs();
  const [form] = Form.useForm();
  const [addPFA, setPFA] = useState(false);
  const [id, setId] = useState<number>();
  const [isDeleting, setIsDeleting] = useState(false);
  const [isSaving, setIsSaving] = useState(false);
  const handleSubmit = (data: any) => {
    setIsSaving(true);

    saveHMO({
      data: {
        hmo: data.hmo,
        description: data.description,
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
                      hmo: res.data?.hmo ?? "",
                      description: res.data?.description ?? "",
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
                hmo: res.data?.hmo ?? "",
                description: res.data?.description ?? "",
                created_at: res.data?.created_at ?? "",
                updated_at: res.data?.updated_at ?? "",
                hmohospitals: res.data?.hmohospitals ?? [],
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
  const handleEdit = (data: THMO) => {
    setId(data.id);
    form.setFieldsValue({
      hmo: data?.hmo,
      description: data?.description,
    });
    setPFA(true);
  };
  const handleDelete = (id: number) => {
    setId(id);
    setIsDeleting(true);
    deleteHMO({ data: { id } })
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
  let columns: ColumnsType<THMO> = [
    {
      title: "Name",
      dataIndex: "name",
      key: "name",
      render: (_, item) => item.hmo,
    },
    {
      title: "Hospital Count",
      dataIndex: "hospitals",
      key: "hospitals",
      render: (_, item) => item?.hmohospitals?.length,
    },

    {
      title: "Description",
      dataIndex: "comments",
      key: "comments",
      ellipsis: true,
      render: (_, item) => item.description,
    },

    {
      title: "",
      dataIndex: "action",
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
  const onCancel = () => {
    setPFA(false);
    form.resetFields();
    setId(undefined);
  };

  return (
    <>
      <Modal
        title={`${id ? "Edit" : "Add"} HMO`}
        open={addPFA}
        onCancel={() => onCancel()}
        footer={null}
        style={{ top: 10 }}
        zIndex={11400}
      >
        <Form
          onFinish={handleSubmit}
          size="small"
          form={form}
          style={{ marginTop: "22px" }}
          requiredMark={false}
        >
          <Form.Item name="hmo" label="Name" rules={textInputValidationRules}>
            <Input placeholder="Enter name" />
          </Form.Item>
          <Form.Item
            name="description"
            label="Description"
            rules={textInputValidationRulesOp}
          >
            <Input.TextArea placeholder="Enter Description" />
          </Form.Item>

          <Button type="primary" htmlType="submit" loading={isSaving}>
            {" "}
            Submit
          </Button>
        </Form>
      </Modal>
      <div style={{ display: "flex", flexDirection: "column", gap: "20px" }}>
        <div style={{ display: "flex", justifyContent: "flex-end" }}>
          <Button type="primary" onClick={() => setPFA(true)}>
            Add HMO
          </Button>
        </div>
        <Table
          columns={columns}
          dataSource={data.map((item) => ({ ...item, key: item.id }))}
          pagination={{ total: data.length, defaultPageSize: 4 }}
        />
      </div>
    </>
  );
};

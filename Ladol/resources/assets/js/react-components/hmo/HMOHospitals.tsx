import React, { useState } from "react";
import { THMO, THMOHospital } from "../../types/hmo";
import Table, { ColumnsType } from "antd/lib/table";
import { Button, Modal, Form, Input, Tag, Select, Checkbox } from "antd";
import { EditFilled, DeleteFilled } from "@ant-design/icons";
import { useGetHMOHospitals } from "../../hooks/hmo/useGetHMOHospitals";
import { openNotification } from "../../utils/notifcations";

import { textInputValidationRules } from "../../utils/validation";
import { saveHMOHospital } from "../../api-library/hmo/saveHMOHospital";
import { deleteHMOHospital } from "../../api-library/hmo/deleteHMOHospital";
import { useGetHMOs } from "../../hooks/hmo/useGetHMOs";
import { importHMOHospitals } from "../../api-library/hmo/importHMOHospitals";

export const HMOHospitals = () => {
  const { data, state, total, setData } = useGetHMOHospitals();
  const [form] = Form.useForm();
  const [addPFA, setPFA] = useState(false);
  const [addBulk, setBulk] = useState(false);
  const [id, setId] = useState<number>();
  const [isDeleting, setIsDeleting] = useState(false);
  const [isSaving, setIsSaving] = useState(false);
  const [selectedFile, setSelectedFile] = useState(null);
  const handleFileChange = (e: any) => {
    setSelectedFile(e.target.files[0]);
  };
  const handleImport = (e: any) => {
    if (!selectedFile) return;
    setIsSaving(true);
    e.preventDefault();

    importHMOHospitals({
      data: {
        hospitals: selectedFile,
      },
    })
      .then((res) => {
        openNotification({
          state: res.success ? "success" : "error",
          title: res.success ? "Success" : "Error",
          description: res.message,
        });

        if (res.data) {
          setData((prev) => [
            ...prev,
            // TODO: Spread over the reponse
            ...(res?.data
              ? res?.data?.map((item) => ({
                  id: item?.id ?? 0,
                  hmo: item?.hmo ?? 0,
                  hospital: item?.hospital ?? "",
                  category: item?.category ?? "",
                  band: item?.band ?? "",
                  contact: item?.contact ?? "",
                  address: item?.address ?? "",
                  hmos: item?.hmos ?? [],
                  user_count: item?.user_count ?? 0,
                  created_at: item?.created_at ?? "",
                  updated_at: item?.updated_at ?? "",
                }))
              : []),
          ]);
        }

        setBulk(false);
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
        setBulk(false);
        setId(undefined);
        form.resetFields();
        setIsSaving(false);
      });
  };
  const handleSubmit = (data: any) => {
    setIsSaving(true);

    saveHMOHospital({
      data: {
        hmo: data?.hmo,
        hospital: data?.hospital,
        category: data?.category,
        band: data?.band,
        contact: data?.contact,
        address: data?.address,
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
                      hmo: res.data?.hmo ?? 0,
                      hospital: res.data?.hospital ?? "",
                      category: res.data?.category ?? "",
                      band: res.data?.band ?? "",
                      contact: res.data?.contact ?? "",
                      address: res.data?.address ?? "",
                      hmos: res.data?.hmos ?? [],
                      user_count: res.data?.user_count ?? 0,
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
                hmo: res.data?.hmo ?? 0,
                hospital: res.data?.hospital ?? "",
                category: res.data?.category ?? "",
                band: res.data?.band ?? "",
                contact: res.data?.contact ?? "",
                address: res.data?.address ?? "",
                hmos: res.data?.hmos ?? [],
                user_count: res.data?.user_count ?? 0,
                created_at: res.data?.created_at ?? "",
                updated_at: res.data?.updated_at ?? "",
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
  const handleEdit = (data: THMOHospital) => {
    setId(data.id);
    form.setFieldsValue({
      hmo: data?.hmos?.map((item) => item.hmo_id),
      hospital: data?.hospital,
      category: data?.category,
      band: data?.band,
      contact: data?.contact,
      address: data?.address,
    });
    setPFA(true);
  };
  const handleDelete = (id: number) => {
    setId(id);
    setIsDeleting(true);
    deleteHMOHospital({ data: { id } })
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

  let columns: ColumnsType<THMOHospital> = [
    {
      title: "Name",
      dataIndex: "name",
      key: "name",
      render: (_, item) => item.hospital,
    },
    {
      title: "HMOs Applicable",
      dataIndex: "hmo_apps",
      key: "hmo_apps",
      render: (_, item) => (
        <>
          {item?.hmos
            ?.map((hmo) => hmo.hmo.hmo)
            .map((item, i) => (
              <Tag key={i}>{item}</Tag>
            ))}
        </>
      ),
    },
    {
      title: "Category",
      dataIndex: "act_up",
      key: "act_up",
      render: (_, item) => item.category,
    },
    {
      title: "Contact",
      dataIndex: "chall",
      key: "chall",
      render: (_, item) => item.contact,
    },
    {
      title: "Address",
      dataIndex: "comments",
      key: "comments",
      ellipsis: true,
      render: (_, item) => item.address,
    },
    {
      title: "User Count",
      dataIndex: "user_count",
      key: "user_count",
      render: (_, item) => item?.user_count,
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
  const { data: hmos, state: hmosState } = useGetHMOs();
  const onCancel = () => {
    setPFA(false);
    setBulk(false);
    form.resetFields();
    setId(undefined);
  };
  return (
    <>
      <Modal
        key={"1"}
        title={`${id ? "Edit" : "Add"} HMO Hospital`}
        open={addPFA}
        onCancel={() => onCancel()}
        style={{ top: 10 }}
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
            name="hospital"
            label="Name"
            rules={textInputValidationRules}
          >
            <Input placeholder="Enter name" />
          </Form.Item>
          <Form.Item
            name="hmo"
            label="Select HMO(s)"
            rules={[{ required: true }]}
          >
            <Checkbox.Group
              options={hmos?.map((item) => ({
                label: item.hmo,
                value: item.id,
              }))}
            />
          </Form.Item>
          <Form.Item
            name="category"
            label="Category"
            rules={textInputValidationRules}
          >
            <Input placeholder="Enter category" />
          </Form.Item>
          <Form.Item name="band" label="Band" rules={textInputValidationRules}>
            <Input placeholder="Enter band" />
          </Form.Item>
          <Form.Item
            name="contact"
            label="Contact"
            rules={textInputValidationRules}
          >
            <Input placeholder="Enter contact" />
          </Form.Item>
          <Form.Item
            name="address"
            label="Address"
            rules={textInputValidationRules}
          >
            <Input.TextArea placeholder="Enter address" />
          </Form.Item>

          <Button type="primary" htmlType="submit" loading={isSaving}>
            {" "}
            Submit
          </Button>
        </Form>
      </Modal>
      <Modal
        key={"2"}
        title={`Import Hospitals`}
        open={addBulk}
        onCancel={() => onCancel()}
        style={{ top: 10 }}
        footer={null}
        zIndex={11400}
      >
        <div>
          <form
            style={{
              display: "flex",
              paddingTop: "20px",
              flexDirection: "column",
              gap: "40px",
            }}
            onSubmit={handleImport}
          >
            <div>
              <a href="api/hmo/download-hmo-hospitals-template">
                Download Template
              </a>
              <br />

              <input type="file" name="file" onChange={handleFileChange} />
            </div>
            <Button type="primary" htmlType="submit" loading={isSaving}>
              Upload
            </Button>
          </form>
        </div>
      </Modal>
      <div style={{ display: "flex", flexDirection: "column", gap: "20px" }}>
        <div
          style={{ display: "flex", gap: "20px", justifyContent: "flex-end" }}
        >
          <Button type="dashed" onClick={() => setBulk(true)}>
            Import Hospitals
          </Button>
          <Button type="primary" onClick={() => setPFA(true)}>
            Add Hospital
          </Button>
        </div>
        <Table
          columns={columns}
          dataSource={data.map((item) => ({ ...item, key: item.id }))}
          pagination={{
            total: data.length,
            defaultPageSize: 4,
          }}
        />
      </div>
    </>
  );
};

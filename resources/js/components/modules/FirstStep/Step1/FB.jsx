import React from 'react'
import { useContext, useCallback } from "react";

import { Select } from "@shopify/polaris";
import { MultiStepFormContext } from "../../../../utils/multistep_context";

export default function FB() {

  const { setFb, fb } = useContext(MultiStepFormContext);
  const handleFBChange = useCallback((value) => setFb(value), []);

  const fb_options = [
    { label: "Select F&B", value: "" },
    { label: "Yes", value: "Y" },
    { label: "No", value: "N" },
  ];

  return (
    <Select
      label="Are you in F&B"
      options={fb_options}
      onChange={handleFBChange}
      value={fb}
    />
  );
}

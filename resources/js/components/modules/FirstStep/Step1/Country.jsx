import React from 'react'
import { useContext, useCallback } from "react";

import { Select } from "@shopify/polaris";
import { MultiStepFormContext } from "../../../../utils/multistep_context";

import axios from "axios";

export default function Country() {
  const { setCountry, country, mall, countryOptions, setMallOptions } = useContext(MultiStepFormContext);

  const fetchMalls = async (country) => {
    const mallOptions = await axios.get("/get_malls?country_id=" + country);

    let options = [...[{ "label": "Select Mall", value: "" }], ...mallOptions.data]
    setMallOptions(options);

  };
  const handleCountryChange = useCallback(value => {

    if (value) {
      fetchMalls(value);
    }

    setCountry(value)

  }, [])

  return (
    <Select
      label="Select Country"
      options={countryOptions}
      onChange={handleCountryChange}
      value={country}
    />
  );
}

import React, { useEffect } from 'react'
import { useContext, useCallback } from "react";

import { Select } from "@shopify/polaris";
import { MultiStepFormContext } from "../../../../utils/multistep_context";

import axios from "axios";

export default function Mall() {
    const { setMall, mall, setTemplateId, mallOptions, setTemplateFields } = useContext(MultiStepFormContext);

    const fetchTemplate = async (mall) => {
        const getTemplate = await axios.get("/get_template_by_mall?id=" + mall);
        setTemplateId(getTemplate.data.id);
        setTemplateFields(getTemplate.data);
    };

    const handleMallChange = useCallback(value => {
        if (value) {
            setMall(Number(value))
            fetchTemplate(value);
        }
    }, [])

    return (
        <Select
            label="Select Mall"
            options={ mallOptions }
            onChange={ handleMallChange }
            value={ mall }
        />
    );
}

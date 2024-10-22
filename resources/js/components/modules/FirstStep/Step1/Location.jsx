import React from 'react'
import { useContext, useCallback } from "react";

import { Select } from "@shopify/polaris";
import axios from "axios";
import { useSearchParams } from "react-router-dom";
import { MultiStepFormContext } from "../../../../utils/multistep_context";

export default function Location({ setIsPremium }) {
    const { setLocation, posLocation, locationOptions, setLocationName } = useContext(MultiStepFormContext);
    const [searchParams, setSearchParams] = useSearchParams();

    const handleLocationChange = useCallback(async (value) => {
        let shop = searchParams.get("shop");

        setLocation(Number(value))
        const getPrremiumInfo = await axios.get(
            "/get_location_check?shop=" +
            shop +
            "&location_id=" +
            value
        );

        if (getPrremiumInfo && getPrremiumInfo.data && getPrremiumInfo.data.premium) {

            setIsPremium(true);
        }

        else
            setIsPremium(false);

    }, [])

    if (posLocation && setLocationName) {
        const flattenedOptions = locationOptions.flat();
        const matchingOption = flattenedOptions.find(option => option.value === posLocation);
        setLocationName(matchingOption ? matchingOption.label : '');
    }

    return (
        <Select
            label="Select Location"
            options={ locationOptions }
            onChange={ handleLocationChange }
            value={ posLocation }
        />
    );
}

import React from 'react'
import { FormLayout, TextField } from "@shopify/polaris";
import { useContext, useCallback } from "react";
import { MultiStepFormContext } from "../../../../utils/multistep_context";

import Country from './Country';
import Location from "./Location";
import Mall from "./Mall";
import FB from "./FB";

export default function Step1({ setIsPremium }) {
    return (
        <div style={ { padding: 20 } }>
            <FormLayout style={ { padding: 20 } }>
                <FormLayout.Group condensed>
                    <Country />
                    <Mall />
                </FormLayout.Group>
                <FormLayout.Group condensed>
                    <Location setIsPremium={ setIsPremium } />
                    <FB />
                </FormLayout.Group>
            </FormLayout>
        </div>
    );
}

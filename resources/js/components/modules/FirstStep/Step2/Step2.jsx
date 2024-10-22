import React, { useContext, useCallback } from "react";
import { MultiStepFormContext } from "../../../../utils/multistep_context";
import { TextField } from '@shopify/polaris';

export default function Step2() {
    const { setStep2Fields, step2Fields, templateFields, fetchVariables, mall, posLocation } = useContext(MultiStepFormContext);
    console.log(step2Fields);
    fetchVariables(mall, posLocation);
    var fields =  JSON.parse(templateFields.fields);
    const handleStep2Fields = useCallback((name, value) => {
        setStep2Fields((prevData) => ({
            ...prevData,
            [name]: value,
        }));
    }, []);

    return (
        <div style={ { padding: 20 } }>
            <br />
            <br />
            { fields.map((field, index) => {
                if (field.type === "number") {
                    return (
                        <TextField
                            key={ index } // Make sure to add a unique key prop for each rendered element
                            type={ field.type }
                            maxLength={ field.max_value }
                            value={ step2Fields[field.name]}
                            onChange={ (event) => handleStep2Fields(field.name, event) }
                            label={ field.label }
                            default={ field.default }
                            name={ field.name }
                            requiredIndicator={true}
                        />
                    );
                }
                return null; // Return null for non-'number' fields to avoid rendering anything
            }) }
            <br />
        </div>
    );
}

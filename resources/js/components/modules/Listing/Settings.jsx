import { Page, Grid, FormLayout, Divider, LegacyCard, Text, Select, TextField, Button } from '@shopify/polaris';
import React, { useState, useCallback, useEffect } from 'react';
import { useSearchParams } from "react-router-dom";
import "@shopify/polaris/build/esm/styles.css";
import axios from "axios";

export default function Settings() {
    const [searchParams, setSearchParams] = useSearchParams();


    useEffect(() => {

        const fetchSettings = async () => {
            const getNumber = await axios.get("/get_settings?shop=" + searchParams.get("shop"));
            let number = getNumber.data.setting_number;
            setSettings(number);
        };
        fetchSettings();

    }, []);

    const [Settings, setSettings] = useState();
    const handleSettingsChange = useCallback(
        (newSettings) => setSettings(newSettings),
        [],
    );

    const handleSubmit = () => {
        axios.post('/api/save_settings', { 'setting_number': Settings, 'shop': searchParams.get("shop") })
            .then(response => {
                console.log(response.data);
                if (response.data == 'Success'){

                    location.reload();
                }
            })
            .catch(error => {
                console.log(error);
            });
    }
    return (

        <Page
            fullWidth
            title="Settings"
            primaryAction={ {
                content: 'Save',
                onClick: () => handleSubmit(),
            } }
        >
            <Grid>
                <Grid.Cell columnSpan={ { xs: 6, sm: 6, md: 6, lg: 6, xl: 6 } }>
                    <LegacyCard sectioned>
                        <Grid>
                            <Grid.Cell columnSpan={ { xs: 12, sm: 12, md: 12, lg: 12, xl: 12 } }>
                                <p style={ { marginTop: 20 } }>Number</p>
                                <TextField
                                    type="number"
                                    value={ Settings }
                                    onChange={ handleSettingsChange }
                                />
                            </Grid.Cell>
                        </Grid>
                    </LegacyCard>
                </Grid.Cell>
            </Grid>
        </Page>
    );
}

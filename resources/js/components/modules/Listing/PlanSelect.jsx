import { Text, Grid, Button, ChoiceList, LegacyStack } from '@shopify/polaris';
import React, { useContext, useCallback, useEffect, useState } from 'react';
import { MultiStepFormContext } from '../../../utils/multistep_context';
import axios from "axios";
import { useSearchParams } from 'react-router-dom';
import "@shopify/polaris/build/esm/styles.css";

export default function PlanSelect({ subscriptions, location, setActive, setUpgrade, setDowngrade, handleSubscriptionChange }) {

    // work for

    const [selected, setSelected] = useState("free");

    const upgradeNow = (locationid) => {
        setActive(true)
        setUpgrade(true);
        setDowngrade(false);
        handleSubscriptionChange(locationid, "upgrade");
    }
    const downgradeNow = (locationid) => {
        setActive(true)
        setUpgrade(false);
        setDowngrade(true);
        handleSubscriptionChange(locationid, "downgrade");
    }
    const handleChange = (value) => {

        setSelected(value);
        handleSubscriptionChange(location, value);
    }

    useEffect(() => {

        if (subscriptions && subscriptions.subscribe) {

            setSelected(subscriptions.subscribe);
        }

    }, subscriptions);

    return (
        <>
            <Grid>
                <Grid.Cell columnSpan={ { xs: 6, sm: 6, md: 6, lg: 6, xl: 6 } }>
                    {selected == "free" && (
                        <button className="Polaris-Button">
                            <span className="Polaris-Button__Content">
                                <span className="Polaris-Button__Text">Currently Active</span>
                            </span>
                        </button>
                    )}
                    { selected == "premium" && (
                        <button className="Polaris-Button" onClick={ () => downgradeNow(location) } style={ { background: "#e51c00", color: "white" } }><span className="Polaris-Button__Content"><span className="Polaris-Button__Text">Downgrade Now</span></span></button>
                    )}
                </Grid.Cell>
                <Grid.Cell columnSpan={ { xs: 6, sm: 6, md: 6, lg: 6, xl: 6 } }>

                    { selected == "premium" && (
                        <button className="Polaris-Button">
                            <span className="Polaris-Button__Content">
                                <span className="Polaris-Button__Text">Currently Active</span>
                            </span>
                        </button>
                    ) }
                    { selected == "free" && (
                        <button className="Polaris-Button Polaris-Button--primary " onClick={ () => upgradeNow(location) } ><span className="Polaris-Button__Content"><span className="Polaris-Button__Text">Upgrade Now</span></span></button>
                    ) }

                </Grid.Cell>
            </Grid>
        </>
    );

}

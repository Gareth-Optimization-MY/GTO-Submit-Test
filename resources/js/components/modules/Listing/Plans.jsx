import { CalloutCard, Grid, Heading, TextField } from '@shopify/polaris';
import React, { useContext, useCallback , useEffect, useState } from 'react';
import { MultiStepFormContext } from '../../../utils/multistep_context';
import axios from "axios";
import { useSearchParams } from 'react-router-dom';

export default function Plans() {
    const [plans, setPlans] = useState([]);
    const [planID, setPlanID] = useState(0);
    const [searchParams, setSearchParams] = useSearchParams();


    const [shopUrlParam, setShopUrlParam] = useState(null);

    useEffect(() => {
        setShopUrlParam("?shop=" + searchParams.get("shop"));

        const fetchPlans = async () => {
            const response = await axios.get("/get_plans?shop=" + searchParams.get("shop"));
            setPlans(response.data.plans);
            setPlanID(Number(response.data.plan_id));
            setPlanNumber(Number(response.data.locations));
            setNewPrice(planNumber * 20);
        };
        fetchPlans();
    }, []);

    const createCharge = async (planId) => {
        try {
            const response = await fetch('/create_charge/' + planId + "?shop=" + searchParams.get("shop") + "&locations=" + planNumber);
            const data = await response.json();
            window.top.location.href = data.response.recurring_application_charge.confirmation_url;
        } catch (error) {
            console.log('errr');
        }
    };

    const cancelPlan = async (planId) => {
        try {

            var cancelPlan = {
                "shop": searchParams.get("shop")
            }
            const response = await axios.post("/cancel_charge", cancelPlan);
            var data = response.data;
            window.top.location.href = data;
        } catch (error) {
            console.log(error);
        }
    };


    const [planNumber, setPlanNumber] = useState(1);
    const [newPrice, setNewPrice] = useState(20);

    const handlePlanNumberChange = useCallback(

        (newPlanNumber) => {
            if (newPlanNumber < 1) {
                setPlanNumber(1);
                setNewPrice(1 * 20);
            } else {
                setPlanNumber(newPlanNumber);
                setNewPrice(newPlanNumber * 20);
            }
        },
        []
    );

    return (
        <Grid>

            <>
                {plans.map((plan) => (
                    <Grid.Cell
                        key={plan.id}
                        columnSpan={{ xs: 6, sm: 6, md: 3, lg: 6, xl: 6 }}
                    >
                        <CalloutCard
                            title={plan.name}
                            primaryAction={{
                                content: planID === plan.id ? 'Currently Active' : 'Select Plan',
                                onClick: () =>  createCharge(plan.id),
                            }}
                            secondaryAction={{
                                content: planID === plan.id ? 'Cancel Plan' : '',
                                onAction: () => planID === plan.id ? cancelPlan(plan.id) :  '',
                            }}
                        >
                            {plan.id === 1 ? (
                                <>
                                    Plan Price <Heading element="h1">${plan.price}</Heading>
                                    <br />
                                    <p>ALL FEATURES EXCEPT SCHEDULING REPORTS AND BULK REPORTS.</p>
                                </>
                            ) : (
                                <>
                                        <Heading element="h1">Plan Price  ${plan.price}</Heading>
                                    <br />
                                    {/* <p>ALL FEATURES INCLUDING SCHEDULING REPORTS AND BULK REPORTS.</p> */}
                                    <div>

                                            <TextField
                                                label="Per location $20 Per Month"
                                                type="number"
                                                value={ planNumber }
                                                onChange={ handlePlanNumberChange }
                                                helpText={ newPrice }
                                            />
                                        </div>
                                </>
                            )}
                        </CalloutCard>
                    </Grid.Cell>
                ))}
            </>
        </Grid>

    );
}

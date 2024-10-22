import { Text, Box , Grid, Divider, LegacyStack, Modal, TextContainer } from '@shopify/polaris';
import React, { useContext, useCallback, useEffect, useState } from 'react';
import PlanSelect from './PlanSelect';
import { MultiStepFormContext } from '../../../utils/multistep_context';
import axios from "axios";
import { useSearchParams } from 'react-router-dom';

export default function NewPlan() {
    const [locations, setLocations] = useState([]);
    const [active, setActive] = useState(false);
    const [upgrade, setUpgrade] = useState(false);
    const [downgrade, setDowngrade] = useState(false);
    const [changeSubscription, setChangeSubscription] = useState([]);
    const [subscriptions, setSubscriptions] = useState([]);
    const [searchParams, setSearchParams] = useSearchParams();
    const [shopUrlParam, setShopUrlParam] = useState(null);


    useEffect(() => {
        setShopUrlParam("?shop=" + searchParams.get("shop"));

        const fetchLocations = async () => {
            const response = await axios.get("/get_locations_for_plan?shop=" + searchParams.get("shop"));
            setLocations(response.data);
        };
        fetchLocations();
    }, []);

    useEffect(() => {
        setShopUrlParam("?shop=" + searchParams.get("shop"));

        const fetchSubscriptions = async () => {
            const response = await axios.get("/get_subscriptions_for_plan?shop=" + searchParams.get("shop"));

            setSubscriptions(response.data);
        };
        fetchSubscriptions();
    }, locations);

    const crossCloseModal = async () => {
        setActive(false);
    }

    const closeModal = async () => {

        // const response = await axios.post("/update_subscription_location?shop=" + searchParams.get("shop"), { "subscriptions": changeSubscription, "new_free": sumFreeSubscriptions, "new_premium": sumPremiumSubscriptions});
        // console.log(response.data);
        const create_charge = await fetch("/create_charge/2?shop=" + searchParams.get("shop") + "&free=" + downgrade + "&premium=" + upgrade + "&subscriptions=" + JSON.stringify(changeSubscription));
        const data = await create_charge.json();
        window.top.location.href = data.response;
        setActive(false);
        // location.reload()
    }
    const updatedSubscriptionsApi = async () => {

        if (!changeSubscription.length)
            console.log("No Change Found");
        else {
            // const response = await axios.post("/update_subscription_location?shop=" + searchParams.get("shop"),{"subscriptions": changeSubscription});
            setActive(true);
            // console.log("response...",response);
        }
    }

    const handleSubscriptionChange = (location, subscribe) => {
        console.log(location, subscribe);
        const existingIndex = changeSubscription.findIndex(sub => sub.location === location);

        if (existingIndex !== -1) {
            // Location already exists, remove it
            const updatedSubscriptions = [...changeSubscription];
            updatedSubscriptions.splice(existingIndex, 1);
            setChangeSubscription(updatedSubscriptions);
        } else {
            // Location doesn't exist, add it
            const newSubscription = { location, subscribe };
            setChangeSubscription([...changeSubscription, newSubscription]);
        }

    }
    // work for
    const [selectedPro, setSelectedPro] = useState(['free']);
    const [selected, setSelected] = useState(['free']);

    // Calculate the sum for free and premium subscriptions
    const sumFreeSubscriptions = changeSubscription
        .filter(sub => sub.subscribe[0] === 'free')
        .reduce((total, sub) => total + 1, 0); // Assuming "subscribe" values for "free" are counted as 1

    const sumPremiumSubscriptions = changeSubscription
        .filter(sub => sub.subscribe[0] === 'premium')
        .reduce((total, sub) => total + 1, 0); // Assuming "subscribe" values for "premium" are counted as 1


    return (
        <>
            <Grid>
                <Grid.Cell columnSpan={ { xs: 8, sm: 8, md: 8, lg: 8, xl: 8 } }>
                    <LegacyStack vertical></LegacyStack>
                </Grid.Cell>
                <Grid.Cell columnSpan={ { xs: 4, sm: 4, md: 4, lg: 4, xl: 4 } }>
                    <Grid>
                        <Grid.Cell columnSpan={ { xs: 6, sm: 6, md: 6, lg: 6, xl: 6 } }><strong>$0 USD/month</strong></Grid.Cell>
                        <Grid.Cell columnSpan={ { xs: 6, sm: 6, md: 6, lg: 6, xl: 6 } }><strong>$20 USD/month (With Schedule & Bulk Report Types)</strong></Grid.Cell>
                    </Grid>
                </Grid.Cell>
            </Grid>
            { locations.map((location) => (
                <Box padding="500">
                    <br />
                    <Grid style={ { margin: 75 }} key={ location.id }>

                        <Grid.Cell style={ { gap: 20, borderColor: "#00000", borderBottom: 1 } } columnSpan={ { xs: 8, sm: 8, md: 8, lg: 8, xl: 8 } }>
                            <Text style={ { margin: 10 } } variant="headingMd" as="h3">{ location.name }</Text>
                            <Text style={ { margin: 10 } } as="p">{ location.province }</Text>
                            <Text style={ { margin: 10 } } as="p">{ location.country_name }</Text>
                        </Grid.Cell>
                        <Grid.Cell columnSpan={ { xs: 4, sm: 4, md: 4, lg: 4, xl: 4 } }>
                            <PlanSelect
                                subscriptions={ subscriptions.find(item => item.location === location.id) }
                                setActive={ setActive }
                                setUpgrade={ setUpgrade }
                                setDowngrade={ setDowngrade }
                                handleSubscriptionChange={ handleSubscriptionChange }
                                location={ location.id }
                            />
                        </Grid.Cell>
                    </Grid>
                    <br />
                    <Divider />
                </Box>
            )) }
{/*
            <button className="Polaris-Button Polaris-Button--primary" aria-disabled="true" type="button"
                onClick={ updatedSubscriptionsApi }
            >
                <span className="Polaris-Button__Content">
                    <span className="Polaris-Button__Text">{ changeSubscription.length ? 'Next' : 'Save' }</span>
                </span>
            </button> */}

            <Modal

                open={ active }
                onClose={ crossCloseModal }
                title="Upgrade or Downgrade Location(s)"
                primaryAction={ {
                    content: 'Continue',
                    onAction: closeModal,
                } }

            >
                <Modal.Section>
                    <TextContainer>
                        <p>
                            <strong>
                                Downgrade { downgrade ? 1 : 0 } Location(s) to Free
                            </strong>


                        </p>

                        <p>
                            <strong>
                                Upgrade { upgrade ? 1 : 0 } Location(s) to Premium
                            </strong>
                        </p>
                    </TextContainer>
                </Modal.Section>
            </Modal>

        </>


    );
}

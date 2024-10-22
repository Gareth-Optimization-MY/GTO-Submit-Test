import React from 'react';
import { AppProvider, LegacyCard, LegacyTabs } from '@shopify/polaris';
import { useState, useCallback } from 'react';
import { useSearchParams } from "react-router-dom";

import "@shopify/polaris/build/esm/styles.css";

import Variables from './Variables';
import AllSubmissions from './AllSubmissions';
import Plans from './Plans';
import NewPlan from './NewPlans';
import HowToUse from './HowToUse';

export default function Listing() {
    const [selected, setSelected] = useState(0);
    const [searchParams, setSearchParams] = useSearchParams();

    const handleTabChange = useCallback(
        (selectedTabIndex) => setSelected(selectedTabIndex),[],
    );
    const tabs = [
        {
            id: 'reports',
            content: 'Reports',
        },
        {
            id: 'variables',
            content: 'Variables',
        },
        {
            id: 'plans',
            content: 'Plans',
        },
        {
            id: 'how_to_use',
            content: 'How to use',
        },
    ];
    const reportTabs = [
        {
            id: 'reports_list',
            content: 'Reports List',
        },
        {
            id: 'scheduled_reports',
            content: 'Scheduled Reports',
        }
    ];

    return (
        <AppProvider>
            <LegacyCard>
                <LegacyTabs tabs={tabs} selected={selected} onSelect={handleTabChange}>
                <LegacyCard.Section>
                        { tabs[selected].content == 'Reports' ? <AllSubmissions /> : tabs[selected].content == 'Variables' ? <Variables /> : tabs[selected].content == 'Plans' ? <NewPlan /> : tabs[selected].content == 'How to use' ? <HowToUse /> : 'no one else' }
                </LegacyCard.Section>
                </LegacyTabs>
            </LegacyCard>
        </AppProvider>
    );
}

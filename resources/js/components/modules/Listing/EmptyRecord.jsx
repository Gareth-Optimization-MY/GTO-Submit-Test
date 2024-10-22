import React from 'react'
import {LegacyCard, EmptyState} from '@shopify/polaris';
import { useSearchParams } from "react-router-dom";
import "@shopify/polaris/build/esm/styles.css";

export default function EmptyRecord() {
    const [searchParams, setSearchParams] = useSearchParams();

    return (
        <LegacyCard sectioned>
            <EmptyState
                heading="This is where you’ll manage your GTO reports "
                secondaryAction={{
                    content: 'Learn more',
                    url: '#',
                }}
                action={{
                    content: 'Create New',
                    onAction: () => window.location = 'create?shop=' + searchParams.get("shop")
                }}
                image="https://cdn.shopify.com/s/files/1/0262/4071/2726/files/emptystate-files.png"
            >
                <p>You can create a new GTO report.</p>
            </EmptyState>
        </LegacyCard>
    );
}

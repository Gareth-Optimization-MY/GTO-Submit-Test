import React from 'react';

import { AppProvider, Page, PageActions } from '@shopify/polaris';
import { useSearchParams } from "react-router-dom";
import Step2 from './Step2';

export default function SecondStep() {
  const [searchParams, setSearchParams] = useSearchParams();

  return (
    <AppProvider>
      <Page
        breadcrumbs={[{
          content: 'Orders',
          onAction: () => window.location = 'create?shop=' + searchParams.get("shop")
        }]}
        title="Create Report"
      >
        <Step2 />
        <PageActions
          primaryAction={{
            content: 'Next',
            onAction: () => window.location = 'step_3?shop=' + searchParams.get("shop")
          }}
          secondaryActions={[{
            content: 'Prev',
            onAction: () => window.location = 'create?shop=' + searchParams.get("shop")
          }]}
        />
      </Page>
    </AppProvider>
  );
}

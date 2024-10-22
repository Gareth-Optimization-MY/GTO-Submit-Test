import React from 'react';
import { AppProvider, Page, PageActions } from '@shopify/polaris';
import { useSearchParams } from "react-router-dom";
import Step3 from './Step3'

export default function ThirdStep() {
  const [searchParams, setSearchParams] = useSearchParams();

  return (
    <AppProvider>
      <Page
        breadcrumbs={[{
          content: 'Orders',
          onAction: () => window.location = 'app_view?shop=' + searchParams.get("shop")
        }]}
        title="Create Report"
      >
        <Step3 />
        <PageActions
          primaryAction={{
            content: 'Submit',
            // onAction: () => window.location = 'step_3?shop='+searchParams.get("shop")
          }}
          secondaryActions={[{
            content: 'Prev',
            onAction: () => window.location = 'step_2?shop=' + searchParams.get("shop")
          }]}
        />
      </Page>
    </AppProvider>
  );
}

import React, { useContext, useCallback } from "react";
import { AppProvider, DatePicker } from "@shopify/polaris";
import { MultiStepFormContext } from "../../../../utils/multistep_context";
import translations from '@shopify/polaris/locales/en.json';
import ReportDate from "./ReportDate";

export default function TransactionDateMultiple() {
    const { setDate, setSelectedDates, selectedDates, month, year } = useContext(MultiStepFormContext);
    
    const handleMonthChange = useCallback(
        (month, year) => setDate({ month, year }),
        []
    );
    return (
        <AppProvider i18n={translations}>
        <DatePicker
            month={month}
            year={year}
            onChange={setSelectedDates}
            onMonthChange={handleMonthChange}
            selected={selectedDates}
            label="Transation Date"
            allowRange
        />
        </AppProvider>
    );
}

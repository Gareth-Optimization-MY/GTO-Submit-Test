import React, { useContext, useCallback } from "react";
import { DatePicker } from "@shopify/polaris";
import { MultiStepFormContext } from "../utils/multistep_context";

export default function TransactionDate() {
    const { setDate, setSelectedDates, selectedDates, month, year } = useContext(MultiStepFormContext);
    const handleMonthChange = useCallback(
        (month, year) => setDate({ month, year }),
        []
    );
    return (
        <DatePicker
            month={month}
            year={year}
            onChange={setSelectedDates}
            onMonthChange={handleMonthChange}
            selected={selectedDates}
            label="Transation Date"
        />
    );
}

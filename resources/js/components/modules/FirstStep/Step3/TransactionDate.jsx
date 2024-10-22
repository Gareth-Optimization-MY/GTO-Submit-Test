import React, { useContext, useCallback, useState } from "react";
import { AppProvider, DatePicker } from "@shopify/polaris";
import { MultiStepFormContext } from "../../../../utils/multistep_context";
import translations from '@shopify/polaris/locales/en.json';
import ReportDate from "./ReportDate";

export default function TransactionDate({ disableDatesBefore }) {
    const { setSelectedDates, selectedDates, report, currentMonth, currentYear, currentDate } = useContext(MultiStepFormContext);
    const [date, setDate] = useState({ month: new Date().getMonth(), year: new Date().getFullYear() });

    //const [{ scheduledMonth, scheduledYear }, setDate] = useState({ scheduledMonth: (currentMonth ? currentMonth : new Date().getMonth()), scheduledYear: (currentYear ? currentYear : new Date().getFullYear() ) });
    const handleMonthChange = useCallback((month, year) => {
        setDate({ month, year });
    }, [setDate]);

    let customTranslations = translations;
    customTranslations["Polaris"]["DatePicker"]["previousMonth"] = 'Previous Month';

    customTranslations["Polaris"]["DatePicker"]["nextMonth"] = 'Next Month';

    customTranslations["Polaris"]["DatePicker"]["previousYear"] = 'Previous Month';
    customTranslations["Polaris"]["DatePicker"]["nextYear"] = 'Next Year';

    console.log("trean", customTranslations);
    return (
        <AppProvider i18n={ customTranslations }>
            <DatePicker
                month={ date.month }
                year={ date.year }
                onChange={ setSelectedDates }
                onMonthChange={ handleMonthChange }
                selected={ selectedDates }
                disableDatesBefore={ disableDatesBefore && !currentDate ? new Date(new Date().setDate(new Date().getDate() - 1)) : null }
            />
        </AppProvider>
    );
}

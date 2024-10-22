import React, { useContext, useCallback, useEffect, useState } from "react";
import { MultiStepFormContext } from "../../../../utils/multistep_context";

import { useSearchParams } from "react-router-dom";
import { Select } from "@shopify/polaris";
import TransactionDate from "./TransactionDate";
import TransactionDateMultiple from "./TransactionDateMultiple";
import axios from "axios";

export default function ReportDate({ isPremium }) {
    const { setReportType, reportType, disabledLocations,setSchedule, schedule, posLocation,setSelectedDates, setShouldStep4Exist, templateId } = useContext(MultiStepFormContext);
    const handleReportTypeChange = useCallback(

        (value) => {
            setReportType(value);
            if (value === 'single_day_only' || value === 'schedule') {
                // Reset the selectedDates to an empty array or to a default single date
                setSelectedDates(''); // or setSelectedDates([defaultSingleDate])
            }
            if (templateId == 5) {
                setShouldStep4Exist(true);
            } else if(value == 'schedule' && templateId != 5) {
                setShouldStep4Exist(true);
            } else {
                setShouldStep4Exist(true);
            }
        },
        []
    );
    const [planId, setPlanId] = useState(1);
    const [searchParams, setSearchParams] = useSearchParams();

    useEffect(() => {
        const fetchPlans = async () => {
            const response = await axios.get("/get_plans?shop=" + searchParams.get("shop"));
            setPlanId(response.data.plan_id);
        };
        fetchPlans();
    }, []);

    const handleScheduleChange = useCallback(
        (value) => setSchedule(value),
        []
    );

    const report_type_options = [
        { label: "Select Report Type", value: "" },
        { label: "Single Report", value: "single_day_only" },
        { label: "Scheduled Report", value: "schedule", disabled: (!isPremium) ? true : false },
        { label: "Bulk Report", value: "date_range", disabled: (!isPremium) ? true : false },
    ];
    const schedule_options = [
        { label: "Select Schedule", value: "" },
        { label: "Every Five Minute", value: "every_five_minute" },
        { label: "Every Hour", value: "every_hour" },
        { label: "Every Day", value: "every_day" },
        { label: "Every Month", value: "every_month" },
        { label: "Every Year", value: "every_year" },
    ];
    return (
        <div>
            <Select
                label="Report Type"
                options={ report_type_options }
                onChange={ handleReportTypeChange }
                value={ reportType }
            />
            <br />
            { reportType == 'date_range' && isPremium ? (
                <TransactionDateMultiple />
            ) : reportType == 'schedule' && isPremium ? (
                <div>
                    <Select
                        label="Schedule"
                        options={ schedule_options }
                        onChange={ handleScheduleChange }
                        value={ schedule }
                    />

                    <TransactionDate disableDatesBefore={ true } />
                </div>
            ) : reportType == 'single_day_only' ? (
                <TransactionDate />
            ) : (
                <div></div>
            ) }
        </div>
    );
}

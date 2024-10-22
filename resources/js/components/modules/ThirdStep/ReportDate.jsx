import React from 'react'
import { useState, useCallback } from 'react';
import { Select } from '@shopify/polaris';
import TransactionDate from './TransactionDate'

export default function ReportDate() {
    const [report_date_selected = 'single_day_only', set_report_date_selected] = useState();

    const handleReportDateChange = useCallback(
        (value) => set_report_date_selected(value),
        [],
    );


    const report_date_options = [
        { label: 'Single Day Only', value: 'single_day_only' },
        { label: 'Schedule', value: 'schedule' },
        { label: 'Date Range', value: 'date_range' },
    ];
    console.log(report_date_selected)
    return (
        <div>

            <Select
                label="Report Date(s)"
                options={report_date_options}
                onChange={handleReportDateChange}
                value={report_date_selected}
            />
            <br />
            <TransactionDate multiple={report_date_selected == 'date_range' ? true : false} />
        </div>

    );
}

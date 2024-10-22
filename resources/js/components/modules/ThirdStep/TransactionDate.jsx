import React from 'react';
import { DatePicker } from '@shopify/polaris';
import { useState, useCallback } from 'react';

export default function TransactionDate({ multiple }) {

    const [{ month, year }, setDate] = useState({ month: 1, year: 2018 });
    const [selectedDates, setSelectedDates] = useState({
        start: new Date('Wed Feb 07 2018 00:00:00 GMT-0500 (EST)'),
        end: new Date('Mon Mar 12 2018 00:00:00 GMT-0500 (EST)'),
    });
    if (multiple) {
        console.log('test');
    }
    else {
        console.log('test2');
    }
    const handleMonthChange = useCallback(
        (month, year) => setDate({ month, year }),
        [],
    );
    if (multiple) {
        return (
            <DatePicker
                month={month}
                year={year}
                onChange={setSelectedDates}
                selected={selectedDates}
            />
        );
    }
    else {

        return (
            <DatePicker
                month={month}
                year={year}
                onChange={setSelectedDates}
                onMonthChange={handleMonthChange}
                selected={selectedDates}
                multiMonth
                allowRange
            />
        );
    }
}

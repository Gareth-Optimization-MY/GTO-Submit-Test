import React from 'react';
import { useState, useCallback } from 'react';

import { TextField } from '@shopify/polaris';
import TransactionDate from './TransactionDate'

export default function Step2() {
    const [machineID, setMachineID] = useState('');

    const handleMachineIDChange = useCallback(
        (newMachineID) => setMachineID(newMachineID),
        [],
    );
    const [salesAmount, setSalesAmount] = useState('');

    const handleSalesAmountChange = useCallback(
        (newSalesAmount) => setSalesAmount(newSalesAmount),
        [],
    );

    return (
        <div style={{ padding: 20 }}>
            <br /><br />
            <TextField
                type="number"
                maxLength={2}
                value={machineID}
                onChange={handleMachineIDChange}
                label="Machine ID"
            />
            <TransactionDate />
            <TextField
                type="number"
                maxLength={11}
                value={salesAmount}
                onChange={handleSalesAmountChange}
                label="Sales Amount"
            />
            <br />
        </div>
    );
}

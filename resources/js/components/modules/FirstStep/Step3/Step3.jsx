import React from 'react'
import ReportDate from './ReportDate';

export default function Step3({ isPremium }) {
    return (
        <div style={{ padding: 20 }}>
            <ReportDate isPremium={isPremium} />
        </div>
    );
}

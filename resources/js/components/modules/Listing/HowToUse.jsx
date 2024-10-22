import React from 'react';
import { AppProvider, LegacyCard, LegacyTabs } from '@shopify/polaris';
import { useState, useCallback } from 'react';
import { useSearchParams } from "react-router-dom";

import "@shopify/polaris/build/esm/styles.css";

export default function HowToUse() {
    return (
        <div>
            <ol>
                <li>Please make sure all your POS Locations (Underline POS Locations, Hyperlink to Settings -> Locations Page) have been added and are also Active</li>
                <li>Depending on which Mall your store is located, certain payment types are required to be tracked accurately and properly sent to the mall for record.
                    <ol>
                        <li>To check what are the required Payment Types, please head over to Variables (Underline Variables, Hyperlink to the Variables Tab)</li>
                        <li>Select the country as also the Mall Name. The required Payment Types are shown. </li>
                        <li>If you do not see the different Payment Types, that means the mall does not need to collect different types, but just the Gross Sales.</li>
                        <li>Once you have decided on your Payment Types, add them in to the required fields. If you have multiple Payment Types that fits in the same field, use a comma to combine them.</li>
                        <li>For tracking of Credit & Debit Cards (If you wish to separate them, please add them both into the Visa & MasterCard field.Visa (Visa Debit,Visa Credit)MasterCard (MasterCard Debit,MasterCard Credit)</li>
                        <li>he names that you add MUST be exactly the same as what you have set in Payment Types (Even Case, and any space)</li>
                    </ol>
                </li>
                <li>Add as many Payment Types as you need. However, please take note of the following limitations on Payment Types:
                    <ol>
                        <li>Added Payment Types applies to all POS Locations. Shopify currently does not have the functionality to limit Payment Types to certain POS Locations.</li>
                        <li>Payment Types are sorted Alphabetically. Depending on your business, Proritizing certain Payment Types may help with the checkout speed (Reduce staff time scrolling through the long list)</li>
                        <li>Typo when you first setting up Payment Types cannot be edited. You will have to Delete it and add a new one again.</li>
                        <li>Some malls have their own Mall vouchers. Please also add them in to be tracked.</li>
                        <li>Please refer to this guide for more information about Custom Payment Types (Underline, Hyperlink to this: https://help.shopify.com/en/manual/sell-in-person/getting-started/setup-payment-method/enable-payments)</li>
                    </ol>
                </li>
           </ol>
        </div>
        );
}

import React, { useContext } from "react";
import { MultiStepFormContext } from "../../../../utils/multistep_context";

import FTP from './FTP';
import API from './API';

export default function Step4() {
    const { templateId } = useContext(MultiStepFormContext);
    return (
        <div style={{ padding: 20 }}>
            {(
                templateId == 5 ? <API /> :  <FTP />
            )}

        </div>
    );

}

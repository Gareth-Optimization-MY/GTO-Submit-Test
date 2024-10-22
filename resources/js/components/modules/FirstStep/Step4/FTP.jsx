import React, { useContext, useCallback } from "react";
import { Grid, TextField, Select } from "@shopify/polaris";
import { MultiStepFormContext } from "../../../../utils/multistep_context";

export default function FTP() {

    const { ftpHost, setFtpHost, ftpProtocol, setFtpProtocol, ftpPort, setFtpPort, ftpUser, setFtpUser, ftpPass, setFtpPass, ftpPath, setFtpPath } = useContext(MultiStepFormContext);

    const handleFtpHostChange = useCallback(
        (newFtpHost) => setFtpHost(newFtpHost),
        []
    );
    const handleFtpProtocolChange = useCallback(
        (newFtpProtocol) => setFtpProtocol(newFtpProtocol),
        []
    );
    const handleFtpPortChange = useCallback(
        (newFtpPort) => setFtpPort(newFtpPort),
        []
    );
    const handleFtpUserChange = useCallback(
        (newFtpUser) => setFtpUser(newFtpUser),
        []
    );
    const handleFtpPassChange = useCallback(
        (newFtpPass) => setFtpPass(newFtpPass),
        []
    );
    const handleFtpPathChange = useCallback(
        (newFtpPath) => setFtpPath(newFtpPath),
        []
    );
    const protocol_options = [
        { label: "Select FTP Protocol", value: "" },
        { label: "Normal", value: "normal" },
        { label: "SFTP", value: "sftp" },
    ];

    return (
        <Grid>
            <Grid.Cell columnSpan={ { xs: 6, sm: 6, md: 3, lg: 6, xl: 6 } }>
                <TextField
                    type="text"
                    value={ ftpHost }
                    onChange={ handleFtpHostChange }
                    label="FTP Hostname"
                />
            </Grid.Cell>
            <Grid.Cell columnSpan={ { xs: 6, sm: 6, md: 3, lg: 6, xl: 6 } }>

                <Select
                    label="FTP Protocol"
                    options={ protocol_options }
                    onChange={ handleFtpProtocolChange }
                    value={ ftpProtocol }
                />
            </Grid.Cell>
            <Grid.Cell columnSpan={ { xs: 6, sm: 6, md: 3, lg: 6, xl: 6 } }>
                <TextField
                    type="text"
                    value={ ftpPort }
                    onChange={ handleFtpPortChange }
                    label="FTP Port"
                />
            </Grid.Cell>
            <Grid.Cell columnSpan={ { xs: 6, sm: 6, md: 3, lg: 6, xl: 6 } }>

                <TextField
                    type="text"
                    value={ ftpUser }
                    onChange={ handleFtpUserChange }
                    label="FTP Username"
                />
            </Grid.Cell>
            <Grid.Cell columnSpan={ { xs: 6, sm: 6, md: 3, lg: 6, xl: 6 } }>
                <TextField
                    type="text"
                    value={ ftpPass }
                    onChange={ handleFtpPassChange }
                    label="FTP Password"
                />
            </Grid.Cell>
            <Grid.Cell columnSpan={ { xs: 6, sm: 6, md: 3, lg: 6, xl: 6 } }>
                <TextField
                    type="text"
                    value={ ftpPath }
                    onChange={ handleFtpPathChange }
                    label="FTP Path"
                />
            </Grid.Cell>
        </Grid>
    );

}

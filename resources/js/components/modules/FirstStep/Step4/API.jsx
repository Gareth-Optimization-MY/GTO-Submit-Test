import React, { useContext, useCallback } from "react";
import { Grid, TextField } from "@shopify/polaris";
import { MultiStepFormContext } from "../../../../utils/multistep_context";

export default function API() {

    const { baseUrl, setBaseUrl, tokenUrl, setTokenUrl, username, setUsername, password, setPassword, apiUrl, setApiUrl } = useContext(MultiStepFormContext);

    const handleBaseUrlChange = useCallback(
        (newBaseUrl) => setBaseUrl(newBaseUrl),
        []
    );
    const handleTokenUrlChange = useCallback(
        (newTokenUrl) => setTokenUrl(newTokenUrl),
        []
    );
    const handleUsernameChange = useCallback(
        (newUsername) => setUsername(newUsername),
        []
    );
    const handlePasswordChange = useCallback(
        (newPassword) => setPassword(newPassword),
        []
    );
    const handleApiUrlChange = useCallback(
        (newApiUrl) => setApiUrl(newApiUrl),
        []
    );
    return (
        <Grid>
            <Grid.Cell columnSpan={ { xs: 6, sm: 6, md: 3, lg: 6, xl: 6 } }>
                <TextField
                    type="text"
                    value={ baseUrl }
                    onChange={ handleBaseUrlChange }
                    label="Base URL"
                />
            </Grid.Cell>
            <Grid.Cell columnSpan={ { xs: 6, sm: 6, md: 3, lg: 6, xl: 6 } }>
                <TextField
                    type="text"
                    value={ tokenUrl }
                    onChange={ handleTokenUrlChange }
                    label="Token URL"
                />
            </Grid.Cell>
            <Grid.Cell columnSpan={ { xs: 6, sm: 6, md: 3, lg: 6, xl: 6 } }>

                <TextField
                    type="text"
                    value={ username }
                    onChange={ handleUsernameChange }
                    label="Username"
                />
            </Grid.Cell>
            <Grid.Cell columnSpan={ { xs: 6, sm: 6, md: 3, lg: 6, xl: 6 } }>
                <TextField
                    type="text"
                    value={ password }
                    onChange={ handlePasswordChange }
                    label="Password"
                />
            </Grid.Cell>
            <Grid.Cell columnSpan={ { xs: 6, sm: 6, md: 3, lg: 6, xl: 6 } }>
                <TextField
                    type="text"
                    value={ apiUrl }
                    onChange={ handleApiUrlChange }
                    label="API URL"
                />
            </Grid.Cell>
        </Grid>
    );

}

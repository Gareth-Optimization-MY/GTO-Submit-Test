import {
    Page,
    Grid,
    FormLayout,
    Divider,
    LegacyCard,
    Text,
    Select,
    TextField,
    Button,
} from "@shopify/polaris";
import React, { useState, useCallback, useEffect } from "react";
import { useSearchParams } from "react-router-dom";
import "@shopify/polaris/build/esm/styles.css";
import axios from "axios";

function Variables() {
    const [searchParams, setSearchParams] = useSearchParams();

    useEffect(() => {
        const fetchCountries = async () => {
            const countryOptions = await axios.get("/get_countries");
            let options = [
                ...[{ label: "Select Country", value: "" }],
                ...countryOptions.data,
            ];
            setCountryOptions(options);
        };
        fetchCountries();
    }, []);

    const fetchMalls = async (country) => {
        setMallOptions("");
        const mallOptions = await axios.get("/get_malls?country_id=" + country);

        let options = [
            ...[{ label: "Select Mall", value: "" }],
            ...mallOptions.data,
        ];
        setMallOptions(options);
    };

    const handleCountryChange = useCallback((value) => {
        if (value) {
            fetchMalls(value);
            setCountry(value);

            setIsSSTRegistered("");
            setSstNumber("");
            setIsGSTRegistered("");
            setGstNumber("");
        }
    }, []);

    const fetchVariables = async (mallID) => {
        const variable = await axios.get(
            "/get_variables?shop=" +
                searchParams.get("shop") +
                "&mallId=" +
                mallID
        );
        setTemplateID(variable.data.is_template);
        setStartingNumber(variable.data.starting_number);
        setCash(variable.data.cash == null ? "" : variable.data.cash);
        setTNG(variable.data.tng == null ? "" : variable.data.tng);
        setVisa(variable.data.visa == null ? "" : variable.data.visa);
        setMasterCard(
            variable.data.master_card == null ? "" : variable.data.master_card
        );
        setAmex(variable.data.amex == null ? "" : variable.data.amex);
        setVoucher(
            variable.data.vouchers == null ? "" : variable.data.vouchers
        );
        setOthers(variable.data.others == null ? "" : variable.data.others);
    };

    const handleMallChange = useCallback((value) => {
        if (value) {
            setMall(Number(value));
            fetchVariables(value);
        } else {
            setMall("");
        }
    }, []);

    const resetStates = () => {
        setCash("");
        setCountry("");
        setMall("");
        setTNG("");
        setVisa("");
        setMasterCard("");
        setAmex("");
        setVoucher("");
        setOthers("");
        setTemplateID("");
    };

    const [StartingNumber, setStartingNumber] = useState();
    const handleStartingNumberChange = useCallback(
        (newStartingNumber) => setStartingNumber(newStartingNumber),
        []
    );
    const [Cash, setCash] = useState();
    const [country, setCountry] = useState("");
    const [mall, setMall] = useState("");
    const [templateID, setTemplateID] = useState("");
    const [countryOptions, setCountryOptions] = useState();
    const [mallOptions, setMallOptions] = useState();
    const handleCashChange = useCallback((newCash) => setCash(newCash), []);
    const [TNG, setTNG] = useState();
    const handleTNGChange = useCallback((newTNG) => setTNG(newTNG), []);
    const [Visa, setVisa] = useState();
    const handleVisaChange = useCallback((newVisa) => setVisa(newVisa), []);
    const [MasterCard, setMasterCard] = useState();
    const handleMasterCardChange = useCallback(
        (newMasterCard) => setMasterCard(newMasterCard),
        []
    );
    const [Amex, setAmex] = useState();
    const handleAmexChange = useCallback((newAmex) => setAmex(newAmex), []);
    const [Voucher, setVoucher] = useState();
    const handleVoucherChange = useCallback(
        (newVoucher) => setVoucher(newVoucher),
        []
    );
    const [Others, setOthers] = useState();
    const handleOthersChange = useCallback(
        (newOthers) => setOthers(newOthers),
        []
    );

    const [isSSTRegistered, setIsSSTRegistered] = useState("");
    const [sstNumber, setSstNumber] = useState("");
    const [isGSTRegistered, setIsGSTRegistered] = useState("");
    const [gstNumber, setGstNumber] = useState("");

    console.log(isSSTRegistered);
    const handleSubmit = () => {
        axios
            .post("/api/save_form", {
                StartingNumber: StartingNumber,
                Cash: Cash,
                TNG: TNG,
                Visa: Visa,
                MasterCard: MasterCard,
                Amex: Amex,
                Voucher: Voucher,
                Others: Others,
                shop: searchParams.get("shop"),
                mall_id: mall,
            })
            .then((response) => {
                resetStates();
                console.log(response.data);
            })
            .catch((error) => {
                console.log(error);
            });
    };
    return (
        <Page
            fullWidth
            title="Variables"
            primaryAction={{
                content: "Save",
                onClick: () => handleSubmit(),
                disabled:
                    country !== "" &&
                    mall !== "" &&
                    templateID != 2 &&
                    templateID != 3 &&
                    templateID != ""
                        ? false
                        : true,
            }}
        >
            <Grid>
                <Grid.Cell columnSpan={{ xs: 8, sm: 8, md: 8, lg: 8, xl: 8 }}>
                    <LegacyCard sectioned>
                        <Grid>
                            <Grid.Cell
                                columnSpan={{
                                    xs: 12,
                                    sm: 12,
                                    md: 12,
                                    lg: 12,
                                    xl: 12,
                                }}
                            >
                                <Select
                                    label="Select Country"
                                    options={countryOptions}
                                    onChange={handleCountryChange}
                                    value={country}
                                />
                                <div style={{ marginTop: 20 }}>
                                    <Select
                                        label="Select Mall"
                                        options={mallOptions}
                                        onChange={handleMallChange}
                                        value={mall}
                                    />
                                </div>
                                <br />
                                <hr />
                                <br />
                                {country === "MY" && (
                                    <>
                                        <label>
                                            Are you SST Registered:
                                            <Select
                                                options={[
                                                    {
                                                        value: "",
                                                        label: "Select",
                                                    },
                                                    {
                                                        value: "yes",
                                                        label: "Yes",
                                                    },
                                                    {
                                                        value: "no",
                                                        label: "No",
                                                    },
                                                ]}
                                                onChange={(e) =>
                                                    setIsSSTRegistered(e)
                                                }
                                                value={isSSTRegistered}
                                            />
                                        </label>
                                        <br />
                                        {isSSTRegistered === "yes" && (

                                            <TextField
                                            type="text"
                                            value={sstNumber}
                                            placeholder="Enter your SST Number"
                                            onChange={(e) =>
                                                setSstNumber(e)
                                            }
                                        />

                                        )}
                                    </>
                                )}

                                {country === "SG" && (
                                    <>
                                        <label>
                                            Are you GST Registered:
                                            <Select
                                                options={[
                                                    {
                                                        value: "",
                                                        label: "Select",
                                                    },
                                                    {
                                                        value: "yes",
                                                        label: "Yes",
                                                    },
                                                    {
                                                        value: "no",
                                                        label: "No",
                                                    },
                                                ]}
                                                onChange={(e) =>
                                                    setIsGSTRegistered(e)
                                                }
                                                value={isGSTRegistered}
                                            />
                                        </label>
                                        <br />
                                        {isGSTRegistered === "yes" && (
                                             <TextField
                                             type="text"
                                             value={gstNumber}
                                             placeholder="Enter your GST Number"
                                             onChange={(e) =>
                                                 setSstNumber(e)
                                             }
                                         />


                                        )}
                                    </>
                                )}
                                {country !== "" &&
                                mall !== "" &&
                                templateID == 1 ? (
                                    <div>
                                        <p style={{ marginTop: 20 }}>
                                            Starting Number
                                        </p>
                                        <TextField
                                            type="number"
                                            value={StartingNumber}
                                            onChange={
                                                handleStartingNumberChange
                                            }
                                        />
                                    </div>
                                ) : (
                                    <></>
                                )}
                                {country !== "" &&
                                mall !== "" &&
                                templateID != 1 &&
                                templateID != 2 &&
                                templateID != 3 &&
                                templateID != "" ? (
                                    <div>
                                        <p style={{ marginTop: 20 }}>Cash</p>
                                        <TextField
                                            type="text"
                                            value={Cash}
                                            onChange={handleCashChange}
                                        />
                                        <p style={{ marginTop: 20 }}>TNG</p>
                                        <TextField
                                            type="text"
                                            value={TNG}
                                            onChange={handleTNGChange}
                                        />
                                        <p style={{ marginTop: 20 }}>Visa</p>
                                        <TextField
                                            type="text"
                                            value={Visa}
                                            onChange={handleVisaChange}
                                        />
                                        <p style={{ marginTop: 20 }}>
                                            MasterCard
                                        </p>
                                        <TextField
                                            type="text"
                                            value={MasterCard}
                                            onChange={handleMasterCardChange}
                                        />
                                        <p style={{ marginTop: 20 }}>Amex</p>
                                        <TextField
                                            type="text"
                                            value={Amex}
                                            onChange={handleAmexChange}
                                        />
                                        <p style={{ marginTop: 20 }}>Voucher</p>
                                        <TextField
                                            type="text"
                                            value={Voucher}
                                            onChange={handleVoucherChange}
                                        />
                                        <p style={{ marginTop: 20 }}>Others</p>
                                        <TextField
                                            type="text"
                                            value={Others}
                                            onChange={handleOthersChange}
                                        />
                                    </div>
                                ) : country !== "" &&
                                  mall !== "" &&
                                  (templateID == 2 || templateID == 3) &&
                                  templateID != "" ? (
                                    <p style={{ marginTop: 20, color: "red" }}>
                                        There are no variables to be set.
                                    </p>
                                ) : (
                                    <></>
                                )}
                            </Grid.Cell>
                        </Grid>
                    </LegacyCard>
                </Grid.Cell>
                <Grid.Cell columnSpan={{ xs: 4, sm: 4, md: 4, lg: 4, xl: 4 }}>
                    <LegacyCard sectioned>
                        <strong>
                            Please copy and paste exactly from the Shopify POS
                            Payment Types
                        </strong>
                        <br />
                        <br />
                        <span>
                            If there are new or removed payment types, please
                            remember to update as well.
                        </span>
                    </LegacyCard>
                </Grid.Cell>
            </Grid>
        </Page>
    );
}
// function Variables() {
//     return (
//         <EmptyRecord />
//     );
// }
export default Variables;

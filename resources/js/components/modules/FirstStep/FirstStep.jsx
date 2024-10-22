import React, { useState, useEffect } from "react";

import { useSearchParams } from "react-router-dom";
import { MultiStepFormContext } from "../../../utils/multistep_context";

import Swal from "sweetalert2";
import axios from "axios";

import { AppProvider, Page, PageActions } from "@shopify/polaris";
import { Step1 } from "./Step1";
import { Step2 } from "./Step2";
import { Step3 } from "./Step3";
import { Step4 } from "./Step4";

export default function FirstStep() {
    const [templateFields, setTemplateFields] = useState([]);
    const getTemplate = async () => {};

    const [mallOptions, setMallOptions] = useState([]);
    const getMallOptions = async () => {};

    const [countryOptions, setCountryOptions] = useState([]);
    const getCountryOptions = async () => {};

    const [locationOptions, setLocationOptions] = useState([]);
    const [disabledLocations, setDisabledLocations] = useState([]);
    const getLocationOptions = async () => {};

    const fetchVariables = async (mall, location_id) => {
        const getVariables = await axios.get(
            "/get_location_by_mall?mall_id=" +
                mall +
                "&location_id=" +
                location_id
        );
        if (getVariables.data) {
            if (
                getVariables.data.ftp_details &&
                getVariables.data.ftp_details !== ""
            ) {
                const ftpDetail = JSON.parse(getVariables.data.ftp_details);
                setFtpHost(ftpDetail.ftp_host);
                setFtpPort(ftpDetail.ftp_port);
                setFtpUser(ftpDetail.ftp_user);
                setFtpPass(ftpDetail.ftp_pass);
                setFtpPath(ftpDetail.ftp_path);
            }
            if (getVariables.data.fields && getVariables.data.fields !== "") {
                const fields = JSON.parse(getVariables.data.fields);
                // console.log(fields);

                setStep2Fields(fields);
            }
        }
    };

    const fetchCountries = async () => {
        const countryOptions = await axios.get("/get_countries");
        let options = [
            ...[{ label: "Select Country", value: "" }],
            ...countryOptions.data,
        ];

        setCountryOptions(options);
    };

    const fetchLocations = async () => {
        const response = await axios.get(
            "/get_locations?shop=" + searchParams.get("shop")
        );
        let options = [
            ...[{ label: "Select Location", value: "" }],
            ...response.data[0],
        ];
        setLocationOptions(options);
        console.log(response);
        setDisabledLocations(response.data[1]);
    };
    useEffect(() => {
        fetchCountries();
        fetchLocations();
    }, []);

    const currentDate = new Date();
    const currentMonth = currentDate.getMonth();
    const currentYear = currentDate.getFullYear();
    const [stepNo, setStepNo] = useState(1);
    const [shouldStep4Exist, setShouldStep4Exist] = useState(false);
    const [{ month, year }, setDate] = useState({
        month: currentMonth,
        year: currentYear,
    });
    const [selectedDates, setSelectedDates] = useState();
    const [searchParams, setSearchParams] = useSearchParams();
    const [step2Fields, setStep2Fields] = useState("");
    const [reportName, setReportName] = useState("");
    const [baseUrl, setBaseUrl] = useState("https://pos.pavilion-kl.com/");
    const [tokenUrl, setTokenUrl] = useState();
    const [apiUrl, setApiUrl] = useState();
    const [username, setUsername] = useState();
    const [password, setPassword] = useState();
    const [ftpHost, setFtpHost] = useState();
    const [ftpProtocol, setFtpProtocol] = useState();
    const [ftpPort, setFtpPort] = useState();
    const [ftpUser, setFtpUser] = useState();
    const [ftpPass, setFtpPass] = useState();
    const [ftpPath, setFtpPath] = useState();
    const [reportType, setReportType] = useState("");
    const [schedule, setSchedule] = useState("");
    const [reportDate, setReportDate] = useState();
    const [country, setCountry] = useState("");
    const [mall, setMall] = useState("");
    const [templateId, setTemplateId] = useState("");
    const [fileType, setFileType] = useState("");
    const [fb, setFb] = useState("");
    const [posLocation, setLocation] = useState("");
    const [isPremium, setIsPremium] = useState(false);

    const submitForm = async () => {
        const ftpDetail = {
            ftpHost: ftpHost,
            ftpProtocol: ftpProtocol,
            ftpPort: ftpPort,
            ftpUser: ftpUser,
            ftpPass: ftpPass,
        };
        if (reportType == "schedule" && templateId != 5) {
            const ftpConnection = await axios.post(
                "/check-ftp-connection",
                ftpDetail
            );
            var ftpCheck = ftpConnection.data.success;
        } else {
            var ftpCheck = true;
        }
        let payload = {};
        if (ftpCheck) {
            if (templateId != 5) {
                payload = {
                    shop: searchParams.get("shop"),
                    schedule: schedule,
                    mall_id: mall,
                    report_from_date:
                        reportType == "single_day_only"
                            ? selectedDates.start
                            : reportType == "date_range"
                            ? selectedDates.start
                            : selectedDates.start,
                    report_to_date:
                        reportType == "date_range"
                            ? selectedDates.end
                            : selectedDates.start,
                    report_type: reportType,
                    pos_location: posLocation,
                    template_id: templateId,
                    step2Fields: step2Fields,
                    ftp_protocol: ftpProtocol,
                    ftp_host: ftpHost,
                    ftp_port: ftpPort,
                    ftp_user: ftpUser,
                    ftp_path: ftpPath,
                    ftp_pass: ftpPass,
                };
            } else {
                payload = {
                    shop: searchParams.get("shop"),
                    schedule: schedule,
                    mall_id: mall,
                    report_from_date:
                        reportType == "single_day_only"
                            ? selectedDates.start
                            : reportType == "date_range"
                            ? selectedDates.start
                            : selectedDates.start,
                    report_to_date:
                        reportType == "date_range"
                            ? selectedDates.end
                            : selectedDates.start,
                    report_type: reportType,
                    pos_location: posLocation,
                    template_id: templateId,
                    step2Fields: step2Fields,
                    base_url: baseUrl,
                    token_url: tokenUrl,
                    username: username,
                    password: password,
                    api_url: apiUrl,
                };
            }
            const generateReport = await axios.post(
                "/generate-report-decide",
                payload
            );

            if (generateReport.data == "success") {
                window.location = "app_view?shop=" + searchParams.get("shop");
            } else {
                Swal.fire(
                    "Report Submission Error",
                    generateReport.data,
                    "error"
                );
            }
        } else {
            Swal.fire("Invalid FTP Connection", ftpConnection.data, "error");
        }
    };

    return (
        <AppProvider>
            <MultiStepFormContext.Provider
                value={{
                    stepNo,
                    setStepNo,
                    fetchVariables,
                    setShouldStep4Exist,
                    shouldStep4Exist,
                    templateFields,
                    setTemplateFields,
                    step2Fields,
                    setStep2Fields,
                    month,
                    year,
                    setDate,
                    selectedDates,
                    setSelectedDates,
                    reportName,
                    setReportName,
                    baseUrl,
                    setBaseUrl,
                    tokenUrl,
                    setTokenUrl,
                    apiUrl,
                    setApiUrl,
                    username,
                    setUsername,
                    password,
                    setPassword,
                    ftpProtocol,
                    setFtpProtocol,
                    ftpHost,
                    setFtpHost,
                    ftpPort,
                    setFtpPort,
                    ftpUser,
                    setFtpUser,
                    ftpPass,
                    setFtpPass,
                    ftpPath,
                    setFtpPath,
                    reportType,
                    setReportType,
                    schedule,
                    setSchedule,
                    reportDate,
                    setReportDate,
                    country,
                    setCountry,
                    mall,
                    setMall,
                    templateId,
                    setTemplateId,
                    fileType,
                    setFileType,
                    fb,
                    setFb,
                    mallOptions,
                    setMallOptions,
                    countryOptions,
                    setCountryOptions,
                    locationOptions,
                    setLocationOptions,
                    posLocation,
                    setLocation,
                    disabledLocations,
                    setDisabledLocations,
                    isPremium,
                    setIsPremium,
                }}
            >
                <Page
                    backAction={{
                        content: "Orders",
                        onAction: () =>
                            (window.location =
                                "app_view?shop=" + searchParams.get("shop")),
                    }}
                    title="Create Report"
                >
                    {stepNo == 1 ? (
                        <div>
                            <Step1 setIsPremium={ setIsPremium } />
                            <PageActions
                                primaryAction={{
                                    content: "Next",
                                    onAction: () => setStepNo(2),
                                    disabled:
                                        "" === country ||
                                        "" === mall ||
                                        "" === posLocation ||
                                        "" === fb,
                                }}
                            />
                        </div>
                    ) : stepNo == 2 ? (
                        <div>
                            <Step2 />
                            <PageActions
                                primaryAction={{
                                    content: "Next",
                                    disabled: "" === step2Fields,
                                    onAction: () => setStepNo(3),
                                }}
                                secondaryActions={[
                                    {
                                        content: "Prev",
                                        onAction: () => setStepNo(1),
                                    },
                                ]}
                            />
                        </div>
                    ) : stepNo == 3 ? (
                        <div>
                            <Step3 isPremium={isPremium} />
                            <PageActions
                                primaryAction={{
                                    content: shouldStep4Exist
                                        ? "Next"
                                        : "Submit",
                                    onAction: shouldStep4Exist
                                        ? () => setStepNo(4)
                                        : () => submitForm(),
                                    disabled: "" === reportType,
                                }}
                                secondaryActions={[
                                    {
                                        content: "Prev",
                                        onAction: () => setStepNo(2),
                                    },
                                ]}
                            />
                        </div>
                    ) : (
                        <div>
                            <Step4 />
                            <PageActions
                                primaryAction={{
                                    content: "Submit",
                                    onAction: () => submitForm(),
                                    disabled:
                                        (("" === ftpHost ||
                                            "" === ftpProtocol ||
                                            "" === ftpPort ||
                                            "" === ftpUser ||
                                            "" === ftpPass) &&
                                            templateId != 5) ||
                                        (("" === baseUrl ||
                                            "" === tokenUrl ||
                                            "" === username ||
                                            "" === password ||
                                            "" === apiUrl) &&
                                            templateId == 5),
                                }}
                                secondaryActions={[
                                    {
                                        content: "Prev",
                                        onAction: () => setStepNo(3),
                                    },
                                ]}
                            />
                        </div>
                    )}
                </Page>
            </MultiStepFormContext.Provider>
        </AppProvider>
    );
}

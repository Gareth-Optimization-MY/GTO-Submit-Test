import React, { useState, useEffect } from "react";

import { useSearchParams, useParams } from "react-router-dom";
import { MultiStepFormContext } from "../../../utils/multistep_context";

import Swal from "sweetalert2";
import axios from "axios";

import { AppProvider, Page, PageActions } from "@shopify/polaris";
import { Step1 } from "../FirstStep/Step1";
import { Step2 } from "../FirstStep/Step2";
import { Step3 } from "../FirstStep/Step3";
import { Step4 } from "../FirstStep/Step4";

export default function EditReport() {
    const params = useParams();

    const [reportId, setReportId] = useState(params.id);
    const [locationName, setLocationName] = useState('');
    const [dataLoaded, setDataLoaded] = useState(false);
    const [report, setReport] = useState([]);
    const [currentDate, setCurrentDate] = useState();
    const [currentMonth, setCurrentMonth] = useState();
    const [currentYear, setCurrentYear] = useState();
    const [{ month, year }, setDate] = useState({
        month: currentMonth,
        year: currentYear,
    });
    const [selectedDates, setSelectedDates] = useState();
    const [searchParams, setSearchParams] = useSearchParams();
    const [step2Fields, setStep2Fields] = useState([]);
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

    const [templateFields, setTemplateFields] = useState([]);
    const getTemplate = async () => {};

    const [mallOptions, setMallOptions] = useState([]);
    const getMallOptions = async () => {};

    const [countryOptions, setCountryOptions] = useState([]);
    const getCountryOptions = async () => {};

    const [locationOptions, setLocationOptions] = useState([]);
    const [disabledLocations, setDisabledLocations] = useState([]);
    const getLocationOptions = async () => {};

    const fetchCountries = async () => {
        const countryOptions = await axios.get("/get_countries");
        let options = [
            ...[{ label: "Select Country", value: "" }],
            ...countryOptions.data,
        ];

        setCountryOptions(options);
    };

    const fetchCountryByMall = async (id) => {
        const response = await axios.get("/get_mall/" + id);
        setCountry(response.data.country);

        await fetchMalls(response.data.country);
    };

    const fetchMalls = async (country) => {
        const mallOptions = await axios.get("/get_malls?country_id=" + country);

        let options = [
            ...[{ label: "Select Mall", value: "" }],
            ...mallOptions.data,
        ];
        setMallOptions(options);
    };

    const fetchTemplate = async (mall) => {
        const getTemplate = await axios.get("/get_template_by_mall?id=" + mall);
        console.log(getTemplate);
        setTemplateFields(getTemplate.data);
        setDataLoaded(true);
    };

    const fetchReportData = async () => {
        const response = await axios.get(
            "/edit-report/" + reportId + "?shop=" + searchParams.get("shop")
        );

        const reportDate = new Date(response.data.report_date);
        const fields = JSON.parse(response.data.input_fields);
        const apiDetails = JSON.parse(response.data.ftp_details);

        // Initialize the currentDate, currentMonth, and currentYear using the reportDate
        const currentDate = reportDate;
        const currentMonth = reportDate.getMonth(); // Note: getMonth() returns 0-11 for Jan-Dec
        const currentYear = reportDate.getFullYear();

        setReport(response.data);

        setTemplateId(response.data.template_use);
        setLocation(Number(response.data.location));


        setMall(response.data.mall_id);
        await fetchCountryByMall(response.data.mall_id);
        await fetchTemplate(response.data.mall_id);
        setReportDate(response.data.report_date);
        setSelectedDates({
            start: reportDate,
            end: reportDate,
        });
        setReportType(response.data.report_type);
        setSchedule(response.data.schedule_cron);
        setCurrentDate(reportDate);
        setCurrentMonth(currentMonth);
        setCurrentYear(currentYear);
        setStep2Fields(fields);
        setBaseUrl(apiDetails.base_url);
        setApiUrl(apiDetails.api_url);
        setTokenUrl(apiDetails.token_url);
        setUsername(apiDetails.username);
        setPassword(apiDetails.password);
    };

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

    const fetchLocations = async () => {
        const response = await axios.get(
            "/get_locations?shop=" + searchParams.get("shop")
        );
        let options = [
            ...[{ label: "Select Location", value: "" }],
            ...response.data[0],
        ];
        setLocationOptions(options);
        setDisabledLocations(response.data[1]);
    };
    useEffect(() => {
        fetchCountries();
        fetchLocations();
        fetchReportData();

    }, []);

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
                    report_id: reportId,
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
                    reportId: reportId,
                };
            }
            const generateReport = await axios.post("/edit-report", payload);

            if (generateReport.data == "success") {
                window.location = "/app_view?shop=" + searchParams.get("shop");
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
                    report,
                    setLocationName,
                    fetchVariables,
                    templateFields,
                    setTemplateFields,
                    step2Fields,
                    setStep2Fields,
                    currentMonth,
                    currentYear,
                    currentDate,
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
                }}
            >
                <Page
                    backAction={{
                        content: "Orders",
                        onAction: () =>
                            (window.location =
                                "/app_view?shop=" + searchParams.get("shop")),
                    }}
                    title={locationName}
                >
                    {dataLoaded && (
                        <div>
                            <Step1 />
                            <Step2 />
                            <Step3 />
                            <Step4 />
                        </div>
                    )}

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
                    />
                </Page>
            </MultiStepFormContext.Provider>
        </AppProvider>
    );
}

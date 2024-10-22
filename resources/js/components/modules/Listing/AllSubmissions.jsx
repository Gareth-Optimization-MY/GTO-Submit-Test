import React from "react";
import { useState, useEffect, useCallback } from "react";
import { Navigate, useNavigate, useSearchParams } from "react-router-dom";
import {
    IndexTable,
    LegacyCard,
    LegacyTabs,
    PageActions,
    Link,
    Pagination,
    useIndexResourceState,
    Button,
} from "@shopify/polaris";

import "@shopify/polaris/build/esm/styles.css";
import axios from "axios";
import Swal from "sweetalert2";
import EmptyRecord from "./EmptyRecord";

export default React.memo(function AllSubmissions() {
    const [reports, setReports] = useState([]);
    const [searchParams, setSearchParams] = useSearchParams();
    const [totalPages, setTotalPages] = useState(1);

    const [loading, setLoading] = useState(true);

    const navigate = useNavigate();


    useEffect(() => {
        fetchReports(selected);
    }, []);

    const fetchReports = async (selected, page = 1, limit = 10) => {
        const reportsOptions = await axios.get(
            `/get_reports?shop=${searchParams.get(
                "shop"
            )}&report_type=${selected}&page=${page}&limit=${limit}`
        );
        setReports(reportsOptions.data.data); // get only the data part
        setCurrentPage(reportsOptions.data.current_page); // set the current page
        setTotalPages(reportsOptions.data.last_page); // set the total number of pages
        setLoading(false); // Data has been fetched, set loading to false
    };
    const deleteReport = async (value) => {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes",
        }).then(async (result) => {
            if (result.isConfirmed) {
                const deletedReportData = await axios.get(
                    "/delete_report?id=" + value
                );
                if (deletedReportData.data == "success") {
                    Swal.fire({
                        title: "Successfully Deleted",
                        text: "Your Report Deleted successfully!",
                        icon: "success",
                    });
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                } else {
                    Swal.fire(
                        "Report Submission Error",
                        deletedReportData.data,
                        "error"
                    );
                }
            }
        });
    };

    const bulkDeleteReports = async (selectedIds) => {
        const confirmation = await Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes",
        });

        if (confirmation.isConfirmed) {
            for (const id of selectedIds) {
                const deletedReportData = await axios.get(
                    "/delete_report?id=" + id
                );
                if (deletedReportData.data !== "success") {
                    Swal.fire(
                        "Report Deletion Error",
                        `Error deleting report with ID: ${id}`,
                        "error"
                    );
                    return; // Stop the deletion process if any deletion fails
                }
            }

            Swal.fire({
                title: "Successfully Deleted",
                text: "Selected reports deleted successfully!",
                icon: "success",
            });

            // Reload the page or update the state to reflect the changes
            setTimeout(() => {
                location.reload();
            }, 2000);
        }
    };

    const downloadReport = async (filenames) => {
        for (const filename of filenames) {
            const fileUrl = "reports/" + filename;
            const response = await fetch(fileUrl);
            const blob = await response.blob();

            const url = window.URL.createObjectURL(blob);
            const link = document.createElement("a");
            link.href = url;
            link.setAttribute("download", filename); // Here specify the name without extension
            document.body.appendChild(link);
            link.click();

            // Clean up
            document.body.removeChild(link);
            // Allow some time for the download
            await new Promise((resolve) => setTimeout(resolve, 100));
        }

        Swal.fire({
            title: "Successfully Downloaded!",
            text: "Your Reports Downloaded successfully!",
            icon: "success",
        });
    };

    const editReport = async (id) => {
        navigate(`/edit/${id}?shop=${searchParams.get(
            "shop"
        )}`);
    };

    const bulkDownloadReports = async (selectedIds) => {
        // Retrieve filenames for each selected report
        // This is a placeholder and needs to be implemented based on your application's logic
        const filenamesForEachReport = await getFilenamesForReports(
            selectedIds
        );

        for (const filenames of filenamesForEachReport) {
            for (const filename of filenames) {
                const fileUrl = "reports/" + filename;
                const response = await fetch(fileUrl);
                const blob = await response.blob();

                const url = window.URL.createObjectURL(blob);
                const link = document.createElement("a");
                link.href = url;
                link.setAttribute("download", filename); // Specify the name without extension
                document.body.appendChild(link);
                link.click();

                // Clean up
                document.body.removeChild(link);
                await new Promise((resolve) => setTimeout(resolve, 100));
            }
        }

        Swal.fire({
            title: "Successfully Downloaded!",
            text: "Selected reports downloaded successfully!",
            icon: "success",
        });
    };

    const getFilenamesForReports = (selectedIds) => {
        return selectedIds.map((id) => {
            // Find the report with the matching ID
            const report = reports.find((report) => report.id === id);

            if (!report) {
                console.error(`Report with ID ${id} not found.`);
                return [];
            }
            // Parse the filenames JSON string into an array
            try {
                return JSON.parse(report.filenames);
            } catch (error) {
                console.error(
                    `Error parsing filenames for report with ID ${id}:`,
                    error
                );
                return [];
            }
        });
    };

    const viewReport = (url) => {
        url = "reports/" + url;
        window.open(url);
    };
    const [currentPage, setCurrentPage] = useState(0);
    const recordsPerPage = 5;

    // Update the handlePageChange method
    const handlePageChange = (newPage) => {
        setLoading(true);
        setCurrentPage(newPage);
        fetchReports(selected, newPage);
    };

    const [selected, setSelected] = useState(0);


    const handleTabChange = useCallback((selectedTabIndex) => {
        setLoading(true);
        // console.log(selectedTabIndex);
        setSelected(selectedTabIndex);
        fetchReports(selectedTabIndex);
    }, []);

    const { selectedResources, allResourcesSelected, handleSelectionChange } =
        useIndexResourceState(reports);

    // return;

    const paginationItems = [];
    for (let i = 1; i <= totalPages; i++) {
        paginationItems.push(
            <button
                key={i}
                style={{
                    cursor: "pointer",
                    padding: "0.5rem",
                    margin: "0 0.25rem",
                    backgroundColor: i === currentPage ? "#5c6ac4" : "#f4f6f8",
                    color: i === currentPage ? "white" : "black",
                    borderRadius: "4px",
                    border: "1px solid #c4cdd5",
                }}
                onClick={() => handlePageChange(i)}
            >
                {i}
            </button>
        );
    }

    const reportTabs = [
        {
            id: "reports_list",
            content: "Reports List",
        },
        {
            id: "scheduled_reports",
            content: "Scheduled Reports",
        },
    ];

    const renderCustomActionBar = () => {
        if (selectedResources.length > 0) {
            return (
                <div
                    style={{
                        display: "flex",
                        justifyContent: "center",
                        alignItems: "center",
                        padding: "10px",
                    }}
                >
                    <button
                        onClick={() => bulkDeleteReports(selectedResources)}
                        disabled={selectedResources.length === 0}
                        style={{
                            backgroundColor: "red",
                            color: "white",
                            padding: "10px 20px",
                            fontSize: "16px",
                            fontWeight: "bold",
                            border: "none",
                            borderRadius: "4px",
                            cursor: "pointer",
                            outline: "none",
                            boxShadow: "0 2px 4px rgba(0,0,0,0.2)",
                            margin: "5px",
                            transition: "background-color 0.3s ease",
                        }}
                        onMouseOver={(e) =>
                            (e.currentTarget.style.backgroundColor = "darkred")
                        }
                        onMouseOut={(e) =>
                            (e.currentTarget.style.backgroundColor = "red")
                        }
                    >
                        Delete Selected
                    </button>
                    {selected == 0 && (
                         <button
                            onClick={() => bulkDownloadReports(selectedResources)}
                            disabled={selectedResources.length === 0}
                            style={{
                                backgroundColor: "black",
                                color: "white",
                                padding: "10px 20px",
                                fontSize: "16px",
                                fontWeight: "bold",
                                border: "none",
                                borderRadius: "4px",
                                cursor: "pointer",
                                outline: "none",
                                boxShadow: "0 2px 4px rgba(0,0,0,0.2)",
                                margin: "5px",
                                transition: "background-color 0.3s ease",
                            }}
                            onMouseOver={(e) =>
                                (e.currentTarget.style.backgroundColor =
                                    "darkslategray")
                            }
                            onMouseOut={(e) =>
                                (e.currentTarget.style.backgroundColor = "black")
                            }
                        >
                            Download Selected
                        </button>
                    )}

                </div>
            );
        }
        return null;
    };

    return (
        <div>
            <PageActions
                primaryAction={{
                    content: "Create New",
                    onAction: () =>
                        (window.location =
                            "create?shop=" + searchParams.get("shop")),
                }}
            />

            <LegacyCard>
                <LegacyTabs
                    tabs={reportTabs}
                    selected={selected}
                    onSelect={handleTabChange}
                >
                    <LegacyCard.Section>
                        {loading ? (
                            <div>Loading...</div> // Replace this with your preferred loading indicator
                        ) : reports.length > 0 ? (
                            <LegacyCard>
                                <IndexTable
                                    itemCount={reports.length}
                                    selectedItemsCount={
                                        allResourcesSelected
                                            ? "All"
                                            : selectedResources.length
                                    }
                                    onSelectionChange={handleSelectionChange}
                                    headings={[
                                        { title: "Mall Name" },
                                        { title: "Location" },
                                        ...(selected !== 1 ? [
                                            { title: "Report Type" },
                                        ] : []),
                                        { title: "Report Date" },
                                        ...(selected !== 1 ? [
                                            { title: "Filename" }
                                        ] : []),
                                        { title: "Action" },
                                    ]}
                                >
                                    {reports.map(
                                        (
                                            {
                                                id,
                                                mall_name,
                                                location,
                                                template_id,
                                                report_type,
                                                report_date,
                                                filenames,
                                                action,
                                            },
                                            index
                                        ) => {
                                            const parsedFilenames =
                                                JSON.parse(filenames);

                                            return (
                                                <IndexTable.Row
                                                    id={id}
                                                    key={id}
                                                    position={index}
                                                    selected={selectedResources.includes(
                                                        id
                                                    )}
                                                >
                                                    <IndexTable.Cell>
                                                        {mall_name}
                                                    </IndexTable.Cell>
                                                    <IndexTable.Cell>
                                                        {location}
                                                    </IndexTable.Cell>
                                                    {selected !== 1 && (
                                                        <IndexTable.Cell>
                                                            {report_type}
                                                        </IndexTable.Cell>
                                                    )}
                                                    <IndexTable.Cell>
                                                        {report_date}
                                                    </IndexTable.Cell>
                                                    {selected !== 1 && (
                                                        <IndexTable.Cell>
                                                            {parsedFilenames.length >
                                                            0
                                                                ? parsedFilenames.map(
                                                                    (
                                                                        filename,
                                                                        fileIndex
                                                                    ) => (
                                                                        <div
                                                                            key={
                                                                                fileIndex
                                                                            }
                                                                        >
                                                                            <Link
                                                                                onClick={() =>
                                                                                    viewReport(
                                                                                        filename
                                                                                    )
                                                                                }
                                                                            >
                                                                                {template_id !=
                                                                                5
                                                                                    ? filename
                                                                                    : "Sent Via API"}
                                                                            </Link>
                                                                        </div>
                                                                    )
                                                                )
                                                                : ""}
                                                        </IndexTable.Cell>
                                                    )}
                                                    <IndexTable.Cell>
                                                        <div>
                                                            <Link
                                                                onClick={() =>
                                                                    deleteReport(
                                                                        id
                                                                    )
                                                                }
                                                            >
                                                                Delete
                                                            </Link>{" "}
                                                            |{" "}
                                                            {selected == 0 ? (
                                                            <Link
                                                                onClick={() =>
                                                                    downloadReport(
                                                                        parsedFilenames
                                                                    )
                                                                }
                                                            >
                                                                Download
                                                            </Link>
                                                            ) :(<Link
                                                                onClick={() =>
                                                                    editReport(
                                                                        id
                                                                    )
                                                                }
                                                            >
                                                                Edit
                                                            </Link>

                                                            )}

                                                        </div>
                                                    </IndexTable.Cell>
                                                </IndexTable.Row>
                                            );
                                        }
                                    )}
                                </IndexTable>
                                {renderCustomActionBar()}
                                <div
                                    style={{
                                        display: "flex",
                                        justifyContent: "center",
                                        marginTop: "1rem",
                                    }}
                                >
                                    <div
                                        style={{
                                            display: "flex",
                                            alignItems: "center",
                                        }}
                                    >
                                        <button
                                            style={{
                                                cursor: "pointer",
                                                padding: "0.5rem",
                                                marginRight: "1rem",
                                                backgroundColor: "#f4f6f8",
                                                borderRadius: "4px",
                                                border: "1px solid #c4cdd5",
                                            }}
                                            onClick={() =>
                                                handlePageChange(
                                                    currentPage - 1
                                                )
                                            }
                                            disabled={currentPage === 1}
                                        >
                                            {"<"}
                                        </button>
                                        {paginationItems}
                                        <button
                                            style={{
                                                cursor: "pointer",
                                                padding: "0.5rem",
                                                marginLeft: "1rem",
                                                backgroundColor: "#f4f6f8",
                                                borderRadius: "4px",
                                                border: "1px solid #c4cdd5",
                                            }}
                                            onClick={() =>
                                                handlePageChange(
                                                    currentPage + 1
                                                )
                                            }
                                            disabled={
                                                currentPage === totalPages
                                            }
                                        >
                                            {">"}
                                        </button>
                                    </div>
                                </div>
                                <br />
                            </LegacyCard>
                        ) : (
                            <EmptyRecord />
                        )}
                    </LegacyCard.Section>
                </LegacyTabs>
            </LegacyCard>
        </div>
    );
});

import React from 'react';
import { createRoot } from 'react-dom/client'
import { BrowserRouter, Routes, Route } from "react-router-dom";
import { FirstStep, SecondStep, ThirdStep, Listing, EditReport } from './components/modules'

export default function App() {
    return (
        <BrowserRouter>
            <Routes>
                <Route path="/" element={<Listing />} />
                <Route path="app_view" element={<Listing />} />
                <Route path="create" element={<FirstStep />} />
                <Route path="edit/:id" element={<EditReport />} />
                <Route path="step_2" element={<SecondStep />} />
                <Route path="step_3" element={<ThirdStep />} />
            </Routes>
        </BrowserRouter>
    );
}

const container = document.getElementById("root");
const root = createRoot(container);
root.render(
    <App />
);

// assets/react/App.js
import React from 'react';
import { BrowserRouter, Routes, Route } from 'react-router-dom';
import MatrixPage from './components/MatrixPage';
import AddItemPage from './components/AddItemPage';
import EditItemPage from './components/EditItemPage';
function App() {
    return (
        <BrowserRouter basename="/cpanel">
            <Routes>
                <Route path="/editor/show/:mapId" element={<MatrixPage />} />
                <Route path="/editor/show/create/:mapId" element={<AddItemPage />} />
                <Route path="/editor/item/edit/:id" element={<EditItemPage />} />
                <Route path="/" element={<div>Matrix Overview?</div>} />
            </Routes>
        </BrowserRouter>
    );
}
export default App;
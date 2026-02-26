import React, { useState, useEffect } from 'react';
import { useParams, useNavigate } from 'react-router-dom';

const AddItemPage = () => {
    const { mapId } = useParams();
    const navigate = useNavigate();
    const [mapName, setMapName] = useState('');
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [title, setTitle] = useState('');
    const [description, setDescription] = useState('');
    const [file, setFile] = useState(null);

    useEffect(() => {
        fetch(`/api/matrix/add/${mapId}`)
            .then(response => {
                if (!response.ok) throw new Error('Ошибка при загрузке данных карты');
                return response.json();
            })
            .then(data => {
                setMapName(data.name);
                setLoading(false);
            })
            .catch(err => {
                console.error(err);
                setError(err.message);
                setLoading(false);
            });
    }, [mapId]);

    const handleTitleChange = (event) => {
        setTitle(event.target.value);
    };

    const handleDescriptionChange = (event) => {
        setDescription(event.target.value);
    };

    const handleFileChange = (event) => {
        setFile(event.target.files[0]);
    };

    const handleSubmit = async (event) => {
        event.preventDefault();
        setLoading(true);
        setError(null);

        const formData = new FormData();
        formData.append('title', title);
        formData.append('description', description);
        if (file) {
            formData.append('file', file);
        }

        try {
            const response = await fetch(`/api/matrix/item/create/${mapId}`, { // Изменен URL
                method: 'POST',
                body: formData,
            });

            if (!response.ok) {
                const errorData = await response.json(); // Предполагаем, что API возвращает JSON с ошибкой
                throw new Error(errorData.error || 'Ошибка при добавлении элемента');
            }

            const responseData = await response.json();
            console.log('Item created:', responseData);
            navigate(`/editor/show/${mapId}`); // Перенаправляем обратно
        } catch (err) {
            console.error(err);
            setError(err.message);
        } finally {
            setLoading(false);
        }
    };

    if (loading) return <div className="text-center mt-5">Загрузка...</div>;
    if (error) return <div className="text-danger text-center mt-5">{error}</div>;

    return (
        <div className="main-content-container container-fluid">
            <div className="page-header row no-gutters py-4">
                <div className="col-12 col-sm-4 text-center text-sm-left mb-0">
                    <span className="text-uppercase page-subtitle">Структурируйте свое мышление</span>
                    <h3 className="page-title">Таблица: {mapName} - Добавить элемент</h3>
                </div>
            </div>
            <div className="row border bg-white p-2 mb-3">
                <button className="btn btn-sm btn-outline-secondary" onClick={() => navigate(`/editor/show/${mapId}`)}>
                    <i className="fas fa-angle-left"></i> Назад
                </button>
            </div>
            <div className="row border bg-white">
                <div className="col-lg-12 col-md-12 col-sm-12 mt-4 mb-4 p-4">
                    <form onSubmit={handleSubmit} encType="multipart/form-data">
                        <div className="form-group">
                            <label>Заголовок:</label>
                            <input type="text" className="form-control" name="title" value={title} onChange={handleTitleChange} />
                        </div>
                        <div className="form-group">
                            <label className="form-label">Изображение:</label>
                            <input type="file" name="file" className="form-control" onChange={handleFileChange} />
                        </div>
                        <div className="form-group">
                            <label>Описание:</label>
                            <textarea className="form-control" name="description" rows="5" value={description} onChange={handleDescriptionChange}></textarea>
                        </div>

                        <div className="text-center mt-3">
                            <button type="submit" className="btn btn-sm btn-outline-primary" disabled={loading}>
                                <i className="fa fa-save"></i> Сохранить
                            </button>
                            {loading && <span className="ml-2">Сохранение...</span>}
                            {error && <div className="text-danger mt-2">{error}</div>}
                        </div>
                    </form>
                </div>
            </div>
        </div>
    );
};

export default AddItemPage;
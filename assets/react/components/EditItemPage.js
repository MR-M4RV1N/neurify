import React, { useState, useEffect } from 'react';
import { useParams, useNavigate } from 'react-router-dom';

const EditItemPage = () => {
    const { id: itemId } = useParams();
    const { mapId } = useParams(); // Возможно, вам понадобится mapId
    const navigate = useNavigate();
    const [item, setItem] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [title, setTitle] = useState('');
    const [description, setDescription] = useState('');
    const [file, setFile] = useState(null);
    const [previewImage, setPreviewImage] = useState('');

    useEffect(() => {
        fetch(`/api/matrix/item/${itemId}`) // Создадим этот API-endpoint
            .then(response => {
                if (!response.ok) throw new Error('Ошибка при загрузке данных элемента');
                return response.json();
            })
            .then(data => {
                setItem(data);
                setTitle(data.title);
                setDescription(data.description);
                setPreviewImage(`/cpanel/images/items/${data.filename}`);
                setLoading(false);
            })
            .catch(err => {
                console.error(err);
                setError(err.message);
                setLoading(false);
            });
    }, [itemId]);

    const handleTitleChange = (event) => {
        setTitle(event.target.value);
    };

    const handleDescriptionChange = (event) => {
        setDescription(event.target.value);
    };

    const handleFileChange = (event) => {
        const selectedFile = event.target.files[0];
        setFile(selectedFile);
        if (selectedFile) {
            const reader = new FileReader();
            reader.onloadend = () => {
                setPreviewImage(reader.result);
            };
            reader.readAsDataURL(selectedFile);
        } else {
            setPreviewImage(item?.filename ? `/cpanel/images/items/${item.filename}` : '');
        }
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
            const response = await fetch(`/api/matrix/item/edit/${itemId}`, { // Создадим этот API-endpoint
                method: 'POST',
                body: formData,
            });

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.error || 'Ошибка при обновлении элемента');
            }

            const responseData = await response.json();
            console.log('Item updated:', responseData);
            navigate(`/editor/show/${item.matrixMapId}`); // Перенаправляем на страницу матрицы
        } catch (err) {
            console.error(err);
            setError(err.message);
        } finally {
            setLoading(false);
        }
    };

    if (loading) return <div className="text-center mt-5">Загрузка...</div>;
    if (error) return <div className="text-danger text-center mt-5">{error}</div>;
    if (!item) return <div>Элемент не найден</div>;

    return (
        <div className="main-content-container container-fluid">
            <div className="page-header row no-gutters py-4">
                <div className="col-12 col-sm-4 text-center text-sm-left mb-0">
                    <span className="text-uppercase page-subtitle">Редактирование элемента</span>
                    <h3 className="page-title">Редактировать: {item.title}</h3>
                </div>
            </div>
            <div className="row border bg-white p-2 mb-3">
                <button className="btn btn-sm btn-outline-secondary" onClick={() => navigate(`/editor/show/${item.matrixMapId}`)}>
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
                            {previewImage && (
                                <img src={previewImage} alt="Preview" className="img-fluid mb-2" style={{ maxWidth: '200px' }} />
                            )}
                            <input type="file" name="file" className="form-control" onChange={handleFileChange} />
                        </div>
                        <div className="form-group">
                            <label>Описание:</label>
                            <textarea className="form-control" name="description" rows="5" value={description} onChange={handleDescriptionChange}></textarea>
                        </div>

                        <div className="text-center mt-3">
                            <button type="submit" className="btn btn-sm btn-outline-primary" disabled={loading}>
                                <i className="fa fa-save"></i> Сохранить изменения
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

export default EditItemPage;
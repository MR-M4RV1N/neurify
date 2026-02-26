import React, { useEffect, useState } from 'react';
import MatrixGrid from './MatrixGrid';
import { Link, useParams } from 'react-router-dom';

const MatrixPage = () => {
    const { mapId } = useParams(); // Получаем mapId из URL
    const [map, setMap] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        fetch(`/api/matrix/${mapId}`)
            .then(response => {
                if (!response.ok) throw new Error('Ошибка при загрузке карты');
                return response.json();
            })
            .then(data => {
                setMap(data);
                setLoading(false);
            })
            .catch(err => {
                console.error(err);
                setError(err.message);
                setLoading(false);
            });
    }, [mapId]);

    if (loading) return <div className="text-center mt-5">Загрузка карты...</div>;
    if (error) return <div className="text-danger text-center mt-5">{error}</div>;
    if (!map) return null;

    const { name, description, filename, items = [] } = map;

    return (
        <div className="row border bg-white p-4">
            <div className="col-xl-6 col-lg-8 col-md-12 col-sm-12 mx-auto">
                <div className="ratio-16x9 mb-3">
                    <img
                        src={filename ? `/cpanel/images/maps/${filename}` : `/cpanel/images/maps/default.jpg`}
                        className="img-fluid rounded"
                        alt="Map preview"
                    />
                </div>
                <h6 className="text-center" style={{fontFamily: 'Georgia'}}>
                    <strong>{name}</strong>
                </h6>
                <p className="text-center" style={{fontSize: 'small', fontFamily: 'Georgia'}}>
                    {description}
                </p>

                <MatrixGrid items={items}/>

                <div className="text-center mt-3">
                    <Link to={`/editor/show/create/${mapId}`} className="btn btn-sm btn-outline-primary">
                        <i className="fa fa-plus"></i> Добавить
                    </Link>
                </div>
            </div>
        </div>
    );
};

export default MatrixPage;

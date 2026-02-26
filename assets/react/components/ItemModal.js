import React from 'react';
import { useNavigate } from 'react-router-dom';

const ItemModal = ({ item, onClose }) => {
    if (!item) {
        return null;
    }
    const navigate = useNavigate();
    const handleEditClick = () => {
        navigate(`/editor/item/edit/${item[0]}`); // Предполагаемый маршрут редактирования
        onClose(); // Закрываем модальное окно после нажатия "Редактировать"
    };

    return (
        <div className="modal fade show" style={{ display: 'block' }} aria-modal="true" role="dialog">
            <div className="modal-dialog">
                <div className="modal-content">
                    <div className="modal-header">
                        <h5 className="modal-title">Информация об элементе</h5>
                        <button type="button" className="close" onClick={onClose} aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div className="modal-body">
                        {item[1] && <div style={{ textAlign: 'justify' }}>{item[1]}</div>}
                        {!item[1] && <div className="text-muted">Нет дополнительной информации.</div>}
                    </div>
                    <div className="modal-footer">
                        <div className="d-flex justify-content-center">
                            <button type="button" className="btn btn-primary mr-2" onClick={handleEditClick}>
                                Редактировать
                            </button>
                            <button type="button" className="btn btn-secondary" onClick={onClose}>
                                Закрыть
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default ItemModal;
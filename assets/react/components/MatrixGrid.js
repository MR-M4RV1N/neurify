import React, { useState } from 'react';
import ItemModal from './ItemModal';
import styles from '../../styles/MatrixGrid.module.css';

// Функция для получения еврейских букв по индексу (1-based)
const getSanskritLetter = (index) => {
    const letters = ['א', 'ב', 'ג', 'ד', 'ה', 'ו', 'ז', 'ח', 'ט'];
    return letters[index - 1] || '';
};

const MatrixGrid = ({ items = [] }) => {
    const [selectedItem, setSelectedItem] = useState(null);

    const handleCellClick = (item) => {
        setSelectedItem(item);
    };

    const handleCloseModal = () => {
        setSelectedItem(null);
    };

    return (
        <>
            <table className="table table-bordered matrix-grid">
                <tbody>
                {[0, 1, 2].map(row => (
                    <tr key={row}>
                        {[0, 1, 2].map(col => {
                            const index = row * 3 + col;
                            const item = items[index];
                            const isEven = (index + 1) % 2 === 0;
                            const defaultColor = isEven ? '#f7f7f7' : 'transparent';

                            return (
                                <td
                                    key={index}
                                    className="td"
                                    style={{
                                        cursor: 'pointer',
                                        transition: 'background-color 0.3s ease',
                                        backgroundColor: defaultColor
                                    }}
                                    onClick={() => handleCellClick(item)}
                                    onMouseEnter={(e) => e.currentTarget.style.backgroundColor = '#e6e6e6'}
                                    onMouseLeave={(e) => e.currentTarget.style.backgroundColor = defaultColor}
                                >
                                    {item && (
                                        <>
                                            <div className="devstyle-sanskrit-letter">
                                                {getSanskritLetter(index + 1)}
                                            </div>
                                            {item[1]}
                                        </>
                                    )}
                                </td>
                            );
                        })}
                    </tr>
                ))}
                </tbody>
            </table>

            {selectedItem && (
                <ItemModal item={selectedItem} onClose={handleCloseModal} />
            )}
        </>
    );
};

export default MatrixGrid;
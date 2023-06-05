
export default function Pagination({ total, currentPage, reSetCurrentPage }) {
    const PER_PAGE = 20;
    const totalPage = Math.ceil(total / PER_PAGE)
    return (
        <div className="">
            {total == 0 && <p>Không có dữ liệu</p>}
            {totalPage > 1 &&
                <div className="pagination">
                    {currentPage > 1 && <button
                        disabled={currentPage === 1}
                        onClick={() => reSetCurrentPage(1)}
                    >
                        <i className='fas fa-angle-double-left'></i>
                    </button>}
                    {currentPage > 1 && <button
                        disabled={currentPage === 1}
                        onClick={() => reSetCurrentPage(currentPage - 1)}
                    >
                        <i className='fas fa-angle-left'></i>
                    </button>}

                    {Array.from({ length: totalPage }, (_, index) => (
                        (Math.abs(currentPage - index) < 5) &&
                        <button
                            key={index + 1}
                            onClick={() => reSetCurrentPage(index + 1)}
                            disabled={currentPage === index + 1}
                        >
                            {index + 1}
                        </button>
                    ))}

                    {currentPage < totalPage && <button
                        disabled={currentPage === totalPage}
                        onClick={() => reSetCurrentPage(currentPage + 1)}
                    >
                        <i className='fas fa-angle-right'></i>
                    </button>}

                    {currentPage < totalPage && <button
                        disabled={currentPage === totalPage}
                        onClick={() => reSetCurrentPage(totalPage)}
                    >
                        <i className='fas fa-angle-double-right'></i>
                    </button>}
                    <span className="pt-2 pl-4 des_pagination"> Hiển thị từ {(currentPage - 1) * PER_PAGE + 1} ~ {Math.min(total, currentPage * PER_PAGE)} trong tổng số  {total}</span>
                </div>
            }
        </div>
    );
}

const borrowedCheck = document.getElementById('borrowing_borrowed');

if (borrowedCheck) {
    const borrowForm = document.getElementById('borrowForm');

    borrowedCheck.addEventListener('click', (e) => {
        if (!borrowedCheck.checked) {
            borrowForm.hidden = true;
        } else {
            borrowForm.hidden = false;
        }
    });
}

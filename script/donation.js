// Handle "Add More" functionality for the donation section
const addMoreButton = document.getElementById('add-more');
const donationItemsContainer = document.getElementById('donation-items');

addMoreButton.addEventListener('click', () => {
    const newDonationItem = document.createElement('div');
    newDonationItem.className = 'donation-item';
    newDonationItem.innerHTML = `
        <input type="text" name="donationType" placeholder="Donation Type (e.g., Books)">
        <input type="number" name="donationCount" placeholder="Number of Items" min="1">
        <button type="button" class="remove-item">Remove</button>
    `;
    donationItemsContainer.appendChild(newDonationItem);
});

// Handle "Remove" functionality for donation sections
donationItemsContainer.addEventListener('click', (e) => {
    if (e.target.classList.contains('remove-item')) {
        const donationItem = e.target.closest('.donation-item');
        donationItemsContainer.removeChild(donationItem);
    }
});



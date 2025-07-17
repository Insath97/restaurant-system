document.addEventListener('DOMContentLoaded', function () {
    // Sidebar toggle functionality
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content');

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function () {
            sidebar.classList.toggle('active');
            mainContent.classList.toggle('active');
        });
    }

    // Active menu item highlighting
    const menuItems = document.querySelectorAll('.sidebar-menu li');

    menuItems.forEach(item => {
        item.addEventListener('click', function () {
            menuItems.forEach(i => i.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // Simulate loading data (in a real app, this would be AJAX calls)
    console.log('Admin dashboard loaded');

    // You can add more interactive functionality here
    // For example: form validation, modal handling, etc.

    // Sample chart initialization (would require Chart.js library)
    /*
    const ctx = document.getElementById('reservationsChart').getContext('2d');
    const reservationsChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Reservations',
                data: [12, 19, 15, 22, 18, 24],
                backgroundColor: 'rgba(211, 84, 0, 0.2)',
                borderColor: 'rgba(211, 84, 0, 1)',
                borderWidth: 2,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    */
});

// Add this to your existing script.js
// It will handle the sidebar toggle and active menu items for the new page

// Menu item form handling (optional - can be kept in the HTML file)
function handleMenuItemForm() {
    const itemImage = document.getElementById('itemImage');
    const imagePreview = document.getElementById('imagePreview');

    if (itemImage && imagePreview) {
        itemImage.addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    imagePreview.src = e.target.result;
                    imagePreview.classList.remove('d-none');
                }
                reader.readAsDataURL(file);
            }
        });
    }

    const saveMenuItemBtn = document.getElementById('saveMenuItem');
    if (saveMenuItemBtn) {
        saveMenuItemBtn.addEventListener('click', function () {
            const form = document.getElementById('menuItemForm');
            if (form.checkValidity()) {
                alert('Menu item saved successfully!');
                // In a real app, you would submit the form here
                const modal = bootstrap.Modal.getInstance(document.getElementById('addMenuItemModal'));
                modal.hide();
                form.reset();
                if (imagePreview) imagePreview.classList.add('d-none');
            } else {
                form.reportValidity();
            }
        });
    }
}

// Call this function when the DOM is loaded
document.addEventListener('DOMContentLoaded', function () {
    // Existing code...

    // Add this to handle menu item form if it exists on the page
    handleMenuItemForm();
});

// Add this to your existing script.js
function handleCategoryForm() {
    // Image preview functionality for category image
    const categoryImage = document.getElementById('categoryImage');
    const categoryImagePreview = document.getElementById('categoryImagePreview');

    if (categoryImage && categoryImagePreview) {
        categoryImage.addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    categoryImagePreview.src = e.target.result;
                    categoryImagePreview.classList.remove('d-none');
                }
                reader.readAsDataURL(file);
            }
        });
    }

    // Save category functionality
    const saveCategoryBtn = document.getElementById('saveCategory');
    if (saveCategoryBtn) {
        saveCategoryBtn.addEventListener('click', function () {
            const form = document.getElementById('categoryForm');
            if (form.checkValidity()) {
                alert('Category saved successfully!');
                // In a real app, you would submit the form here
                const modal = bootstrap.Modal.getInstance(document.getElementById('addCategoryModal'));
                modal.hide();
                form.reset();
                if (categoryImagePreview) categoryImagePreview.classList.add('d-none');
            } else {
                form.reportValidity();
            }
        });
    }
}

// Call this function when the DOM is loaded
document.addEventListener('DOMContentLoaded', function () {
    // Existing code...

    // Add this to handle category form if it exists on the page
    handleCategoryForm();
});
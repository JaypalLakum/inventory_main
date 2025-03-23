let currentModelId = null;
let modelDetailsModal = null;
let currentModels = [];
let currentIndex = 0;
let modelCarousel = null;

function loadInventory() {
    $.get('api/model.php', function(response) {
        if (response.success) {
            const models = response.models;
            const modelGroups = {};
            
            // Group models by manufacturer and model name
            models.forEach(model => {
                const key = `${model.manufacturer_name}-${model.name}`;
                if (!modelGroups[key]) {
                    modelGroups[key] = {
                        manufacturer_name: model.manufacturer_name,
                        name: model.name,
                        available_count: 0,
                        models: []
                    };
                }
                if (!model.is_sold) {
                    modelGroups[key].available_count++;
                }
                modelGroups[key].models.push(model);
            });

            let html = '';
            let serialNumber = 1;
            Object.values(modelGroups).forEach(group => {
                html += `
                    <tr>
                        <td>${serialNumber++}</td>
                        <td>${group.manufacturer_name}</td>
                        <td>${group.name}</td>
                        <td><span class="badge bg-primary">${group.available_count}</span></td>
                        <td>
                            <button class="btn btn-sm btn-primary" onclick="showModelDetails('${group.name}', '${group.manufacturer_name}')">
                                <i class="fas fa-eye me-1"></i>View Details
                            </button>
                        </td>
                    </tr>
                `;
            });

            $('#inventoryTableBody').html(html || '<tr><td colspan="5" class="text-center">No vehicles found</td></tr>');
        }
    });
}

function showModelDetails(modelName, manufacturerName) {
    $.get('api/model.php', function(response) {
        if (response.success) {
            // Filter all instances of the selected model
            currentModels = response.models.filter(m => 
                m.name === modelName && 
                m.manufacturer_name === manufacturerName
            );
            currentIndex = 0;
            updateModal();
            modelDetailsModal.show();
        }
    });
}

function updateModal() {
    if (currentModels.length === 0) return;

    let modelsHtml = '';
    let indicatorsHtml = '';
    
    currentModels.forEach((model, index) => {
        let activeClass = index === 0 ? 'active' : '';
        
        // Add carousel item
        modelsHtml += `
            <div class="carousel-item ${activeClass}">
                <div class="vehicle-details-container">
                    <!-- Left Column - Vehicle Information -->
                    <div class="vehicle-info">
                        <div class="info-header">
                            <h4 class="mb-0">${model.manufacturer_name} ${model.name}</h4>
                            <button class="btn ${model.is_sold ? 'btn-success' : 'btn-warning'} btn-sm" onclick="toggleSoldStatus(${model.id}, event)">
                                <i class="fas ${model.is_sold ? 'fa-undo' : 'fa-check-circle'} me-1"></i>
                                ${model.is_sold ? 'Purchase' : 'Mark as Sold'}
                            </button>
                        </div>
                        
                        <div class="info-section">
                            <div class="info-group">
                                <label>Color</label>
                                <span>${model.color}</span>
                            </div>
                            <div class="info-group">
                                <label>Manufacturing Year</label>
                                <span>${model.manufacturing_year}</span>
                            </div>
                            <div class="info-group">
                                <label>Registration Number</label>
                                <span>${model.registration_number}</span>
                            </div>
                            <div class="info-group">
                                <label>Status</label>
                                <span class="status-badge ${model.is_sold ? 'sold' : 'available'}">
                                    ${model.is_sold ? 'Sold' : 'Available'}
                                </span>
                                
                            </div>
                        </div>

                        ${model.note ? `
                            <div class="info-section">
                                <label>Additional Notes</label>
                                <p class="note-text">${model.note}</p>
                            </div>
                        ` : ''}
                    </div>

                    <!-- Right Column - Vehicle Images -->
                    <div class="vehicle-images">
                        <div class="image-container">
                            ${model.image1 ? `
                                <div class="main-image">
                                    <img src="uploads/${model.image1}" class="img-fluid rounded" alt="Vehicle Image 1">
                                </div>
                            ` : ''}
                            ${model.image2 ? `
                                <div class="secondary-image">
                                    <img src="uploads/${model.image2}" class="img-fluid rounded" alt="Vehicle Image 2">
                                </div>
                            ` : ''}
                            ${!model.image1 && !model.image2 ? `
                                <div class="no-image">
                                    <i class="fas fa-car"></i>
                                    <p>No images available</p>
                                </div>
                            ` : ''}
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Add indicator
        indicatorsHtml += `
            <button type="button" data-bs-target="#modelCarousel" data-bs-slide-to="${index}" 
                    class="${activeClass}" aria-current="${activeClass ? 'true' : 'false'}"
                    aria-label="Slide ${index + 1}"></button>
        `;
    });

    // Update carousel content
    $('#carouselModelInner').html(modelsHtml);
    $('.carousel-indicators').html(indicatorsHtml);

    // Destroy existing carousel if it exists
    if (modelCarousel) {
        modelCarousel.dispose();
        modelCarousel = null;
    }

    // Initialize new carousel
    modelCarousel = new bootstrap.Carousel(document.getElementById('modelCarousel'), {
        interval: false,
        wrap: false,
        keyboard: true,
        
    });

    // Force the first slide to be active
    modelCarousel.to(0);
}

function toggleSoldStatus(modelId, event) {
    const button = event.currentTarget;
    const carouselItem = button.closest('.carousel-item');
    const currentStatus = carouselItem.querySelector('.status-badge').classList.contains('sold');
    const newStatus = !currentStatus;
    
    // Disable button and show loading state
    button.disabled = true;
    button.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Updating...';

    $.ajax({
        url: 'api/model.php',
        type: 'PUT',
        data: {
            id: modelId,
            is_sold: newStatus ? 1 : 0
        },
        success: function(response) {
            if (response.success) {
                // Update the model data in currentModels array
                const modelIndex = currentModels.findIndex(m => m.id === modelId);
                if (modelIndex !== -1) {
                    currentModels[modelIndex].is_sold = newStatus;
                }

                // Update the status badge
                const statusBadge = carouselItem.querySelector('.status-badge');
                statusBadge.className = `status-badge ${newStatus ? 'sold' : 'available'}`;
                statusBadge.textContent = newStatus ? 'Sold' : 'Available';
                
                // Update button text and class
                button.className = `btn ${newStatus ? 'btn-success' : 'btn-warning'} btn-sm`;
                button.innerHTML = `<i class="fas ${newStatus ? 'fa-undo' : 'fa-check-circle'} me-1"></i>${newStatus ? 'Purchase' : 'Mark as Sold'}`;
                
                // Update the available count in the inventory table
                const modelName = carouselItem.querySelector('h4').textContent.split(' ').slice(1).join(' ');
                const row = $(`#inventoryTableBody tr:contains('${modelName}')`);
                const countBadge = row.find('.badge');
                const currentCount = parseInt(countBadge.text());
                countBadge.text(newStatus ? currentCount - 1 : currentCount + 1);

                // Update the carousel display without re-rendering the current slide
                const currentSlide = carouselItem;
                const currentSlideIndex = Array.from(carouselItem.parentElement.children).indexOf(carouselItem);
                
                // Update only the indicators
                $('.carousel-indicators').html('');
                currentModels.forEach((_, index) => {
                    const activeClass = index === currentSlideIndex ? 'active' : '';
                    $('.carousel-indicators').append(`
                        <button type="button" data-bs-target="#modelCarousel" data-bs-slide-to="${index}" 
                                class="${activeClass}" aria-current="${activeClass ? 'true' : 'false'}"
                                aria-label="Slide ${index + 1}"></button>
                    `);
                });
            } else {
                alert('Failed to update status. Please try again.');
            }
        },
        error: function() {
            alert('An error occurred. Please try again.');
        },
        complete: function() {
            // Re-enable button
            button.disabled = false;
        }
    });
}

function toggleModelStatus() {
    if(currentModelId) {
        const currentStatus = $('#modalStatus').text();
        const newStatus = currentStatus === 'Available' ? 1 : 0;
            
        $('#toggleStatusBtn').prop('disabled', true).html(
            '<span class="spinner-border spinner-border-sm me-1"></span>Updating...'
        );

        $.ajax({
            url: 'api/model.php',
            type: 'PUT',
            data: {
                id: currentModelId,
                is_sold: newStatus
            },
            success: function(response) {
                if(response.success) {
                    modelDetailsModal.hide();
                    loadInventory();
                }
            },
            complete: function() {
                $('#toggleStatusBtn').prop('disabled', false);
            }
        });
    }
 }

$(document).ready(function() {
    
    modelDetailsModal = new bootstrap.Modal(document.getElementById('modelDetailsModal')); 
    loadInventory();

    // Toggle status button handler
    $('#toggleStatusBtn').click(toggleModelStatus);

    // Set up polling for real-time updates
    //setInterval(loadInventory, 5000);

});
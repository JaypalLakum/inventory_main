<?php require_once 'includes/header.php'; ?>

<div class="container main-container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4><i class="fas fa-clipboard-list me-2"></i>Vehicle Inventory</h4>
            <div class="header-actions">
                <button class="btn btn-primary btn-sm" onclick="loadInventory()">
                    <i class="fas fa-sync-alt me-1"></i>Refresh
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Serial Number</th>
                            <th>Manufacturer Name</th>
                            <th>Model Name</th>
                            <th>Available Count</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="inventoryTableBody">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modelDetailsModal" tabindex="-1" aria-labelledby="modelDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modelDetailsModalLabel">
                    <i class="fas fa-car-side me-2"></i>Vehicle Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="modelCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner" id="carouselModelInner">
                        <!-- Carousel items will be dynamically added here -->
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#modelCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#modelCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                    <div class="carousel-indicators">
                        <!-- Indicators will be dynamically added here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?> 
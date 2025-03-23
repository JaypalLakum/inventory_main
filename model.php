<?php require_once 'includes/header.php'; ?>

<div class="container main-container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-car me-2"></i>Add New Model</h4>
                </div>
                <div class="card-body">
                    <form id="modelForm" novalidate>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="manufacturer_id" class="form-label">Manufacturer</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-industry" data-default-icon="fa-industry"></i>
                                    </span>
                                    <select class="form-select" id="manufacturer_id" name="manufacturer_id" required>
                                        <option value="">Select Manufacturer</option>
                                    </select>
                                </div>
                                <div class="form-text">
                                    <span class="validation-message"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="name" class="form-label">Model Name</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-car-side" data-default-icon="fa-car-side"></i>
                                    </span>
                                    <input type="text" class="form-control" id="name" name="name" required
                                           placeholder="Enter model name"/>
                                </div>
                                <div class="form-text">
                                    <span class="validation-message"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="color" class="form-label">Color</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-palette" data-default-icon="fa-palette"></i>
                                    </span>
                                    <input type="text" class="form-control" id="color" name="color" required
                                           placeholder="Enter color">
                                </div>
                                <div class="form-text">
                                    <span class="validation-message"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="manufacturing_year" class="form-label">Manufacturing Year</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-calendar-alt" data-default-icon="fa-calendar-alt"></i>
                                    </span>
                                    <input type="number" class="form-control" id="manufacturing_year" name="manufacturing_year" 
                                           min="1900" max="2099" required placeholder="Enter year">
                                </div>
                                <div class="form-text">
                                    <span class="validation-message"></span>
                                </div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="registration_number" class="form-label">Registration Number</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-id-card" data-default-icon="fa-id-card"></i>
                                </span>
                                <input type="text" class="form-control" id="registration_number" name="registration_number" required
                                       placeholder="Enter registration number">
                            </div>
                            <div class="form-text">
                                <span class="validation-message"></span>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="note" class="form-label">Note</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-sticky-note" data-default-icon="fa-sticky-note"></i>
                                </span>
                                <textarea class="form-control" id="note" name="note" rows="3"
                                         placeholder="Enter additional notes (optional)"></textarea>
                            </div>
                            <div class="form-text">
                                <span class="validation-message"></span>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Images (Max 2)</label>
                            <div id="imageUpload" class="dropzone">
                                <div class="dz-message">
                                    <i class="fas fa-cloud-upload-alt fa-3x mb-3"></i><br>
                                    Drop files here or click to upload
                                </div>
                            </div>
                            <small class="text-muted">Drag and drop images here or click to upload (Max file size: 5MB)</small>
                        </div>
                        <div class="alert alert-danger" id="errorMessage" style="display: none;"></div>
                        <div class="alert alert-success" id="successMessage" style="display: none;"></div>
                        <button type="submit" class="btn btn-primary" id="submitButton">
                            <i class="fas fa-plus me-2"></i>Add Model
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?> 
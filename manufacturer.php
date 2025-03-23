<?php require_once 'includes/header.php'; ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Add New Manufacturer</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-success" id="successAlert" style="display: none;">
                        <i class="fas fa-check-circle me-2"></i>Manufacturer added successfully!
                    </div>
                    <div class="alert alert-danger" id="errorAlert" style="display: none;">
                        <i class="fas fa-exclamation-circle me-2"></i>Error adding manufacturer.
                    </div>
                    <form id="manufacturerForm">
                        <div class="mb-3">
                            <label for="name" class="form-label">Manufacturer Name</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-industry"></i>
                                </span>
                                <input type="text" class="form-control" id="name" name="name" required
                                       minlength="2" maxlength="50" pattern="[A-Za-z0-9\s\-&']+"
                                       placeholder="Enter Here">
                            </div>
                            <div class="form-text">
                                <span id="validationMessage" class="text-muted"></span>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary" id="submitButton">
                            <i class="fas fa-plus me-2"></i>Add Manufacturer
                        </button>
                    </form>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h4>Existing Manufacturers</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="manufacturersTable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Manufacturers will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?> 
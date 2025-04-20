<?php
require_once 'db.php';

// File upload directory
$uploadDir = 'uploads/pastpapers/';

// Create directory if it doesn't exist
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_paper'])) {
    $courseName = trim($_POST['course_name']);
    $unitName = trim($_POST['unit_name']);
    $description = trim($_POST['description']);
    $uploadedBy = "Anonymous"; // You can replace with logged-in user if you have auth

    if (!empty($courseName) && !empty($unitName) && !empty($_FILES['paper_file']['name'])) {
        // File info
        $fileName = basename($_FILES['paper_file']['name']);
        $fileTmp = $_FILES['paper_file']['tmp_name'];
        $fileSize = $_FILES['paper_file']['size'];
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        // Allowed file types
        $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array($fileExt, $allowedExtensions)) {
            // Generate unique filename to prevent overwrites
            $newFileName = uniqid() . '_' . $fileName;
            $uploadPath = $uploadDir . $newFileName;
            
            if (move_uploaded_file($fileTmp, $uploadPath)) {
                // Insert into database
                $stmt = $pdo->prepare("INSERT INTO past_papers 
                    (course_name, unit_name, description, file_name, file_path, uploaded_by, uploaded_at) 
                    VALUES (?, ?, ?, ?, ?, ?, NOW())");
                $stmt->execute([
                    $courseName, 
                    $unitName, 
                    $description, 
                    $fileName, 
                    $uploadPath, 
                    $uploadedBy
                ]);
                
                $success = "Past paper uploaded successfully!";
            } else {
                $error = "File upload failed. Please try again.";
            }
        } else {
            $error = "Only PDF, JPG, JPEG, PNG & GIF files are allowed.";
        }
    } else {
        $error = "Please fill all required fields and select a file.";
    }
}

// Get all past papers
$stmt = $pdo->query("SELECT * FROM past_papers ORDER BY uploaded_at DESC");
$papers = $stmt->fetchAll();
?>

<?php include 'header.php'; ?>

<div class="container mt-4">
    <h2 class="mb-4">Past Papers</h2>
    
    <!-- Upload Form -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Upload Past Paper</h4>
        </div>
        <div class="card-body">
            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php elseif (isset($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="course_name" class="form-label">Course Name</label>
                        <input type="text" class="form-control" id="course_name" name="course_name" required>
                    </div>
                    <div class="col-md-6">
                        <label for="unit_name" class="form-label">Unit Name</label>
                        <input type="text" class="form-control" id="unit_name" name="unit_name" required>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Description (Optional)</label>
                    <textarea class="form-control" id="description" name="description" rows="2"></textarea>
                </div>
                
                <div class="mb-3">
                    <label for="paper_file" class="form-label">Select File (PDF or Image)</label>
                    <input class="form-control" type="file" id="paper_file" name="paper_file" accept=".pdf,.jpg,.jpeg,.png,.gif" required>
                    <div class="form-text">Max file size: 5MB</div>
                </div>
                
                <button type="submit" name="submit_paper" class="btn btn-primary">Upload</button>
            </form>
        </div>
    </div>
    
    <!-- Past Papers List -->
    <div class="card">
        <div class="card-header bg-light">
            <h4 class="mb-0">Available Past Papers</h4>
        </div>
        <div class="card-body">
            <?php if (empty($papers)): ?>
                <p class="text-muted">No past papers available yet.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Course</th>
                                <th>Unit</th>
                                <th>Description</th>
                                <th>File</th>
                                <th>Uploaded By</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($papers as $paper): ?>
                                <tr>
                                    <td><?= htmlspecialchars($paper['course_name']) ?></td>
                                    <td><?= htmlspecialchars($paper['unit_name']) ?></td>
                                    <td><?= htmlspecialchars($paper['description']) ?></td>
                                    <td>
                                        <a href="<?= htmlspecialchars($paper['file_path']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <?= htmlspecialchars($paper['file_name']) ?>
                                        </a>
                                    </td>
                                    <td><?= htmlspecialchars($paper['uploaded_by']) ?></td>
                                    <td><?= date('M j, Y', strtotime($paper['uploaded_at'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
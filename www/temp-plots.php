<?php 
include 'header.php'; 

// ---------------------------------------------------------
// 1. Configuration & Security
// ---------------------------------------------------------
$base_dir = 'temp-plots';
$base_path = realpath(__DIR__ . '/' . $base_dir);

// CRITICAL: Check if the temp-plots/ directory exists before proceeding
if (!$base_path || !is_dir($base_path)) {
    echo '<main class="container"><div class="alert alert-warning mt-5">';
    echo '<i class="bi bi-exclamation-triangle-fill me-2"></i>';
    echo '<strong>Directory Not Found:</strong> The <code>' . $base_dir . '/</code> directory is missing from the server.';
    echo '<br><a href="index.php" class="alert-link">Return to Home</a>';
    echo '</div></main>';
    include 'footer.php';
    exit; // Stop execution
}

// Sanitize and resolve the requested sub-directory
$req_dir = isset($_GET['dir']) ? trim($_GET['dir'], '/') : '';
$target_path = realpath($base_path . '/' . $req_dir);

// Security Check: Prevent directory traversal
if ($target_path === false || strpos($target_path, $base_path) !== 0 || !is_dir($target_path)) {
    $target_path = $base_path;
    $req_dir = '';
}

// ---------------------------------------------------------
// 2. Scan and Categorize Directory Contents
// ---------------------------------------------------------
$directories = [];
$pdfs = [];
$images = [];

$items = scandir($target_path);
foreach ($items as $item) {
    if ($item === '.' || $item === '..') continue;
    
    $item_path = $target_path . '/' . $item;
    $rel_path = $req_dir ? $req_dir . '/' . $item : $item;
    
    if (is_dir($item_path)) {
        // Store as associative array with name as key for easier sorting
        $directories[$item] = [
            'name' => $item,
            'url' => 'temp-plots.php?dir=' . urlencode($rel_path)
        ];
    } else {
        $ext = strtolower(pathinfo($item, PATHINFO_EXTENSION));
        $file_url = $base_dir . '/' . $rel_path;
        
        if ($ext === 'pdf') {
            $pdfs[$item] = [
                'name' => $item,
                'url' => $file_url
            ];
        } elseif (in_array($ext, ['png', 'jpg', 'jpeg'])) {
            $images[$item] = [
                'name' => $item,
                'url' => $file_url
            ];
        }
    }
}

// --- APPLY NATURAL SORTING ---
// ksort with SORT_NATURAL | SORT_FLAG_CASE ensures 100.png < 1000.png 
// and ignores uppercase/lowercase discrepancies.
uksort($directories, 'strnatcasecmp');
uksort($pdfs, 'strnatcasecmp');
uksort($images, 'strnatcasecmp');
?>

<main class="container">
    
    <!-- Breadcrumb Navigation -->
    <div class="row mb-5">
        <div class="col-12 border-bottom pb-3">
            <a href="index.php" class="text-decoration-none fw-bold"><i class="bi bi-house-door-fill me-1"></i> Home</a>
            <span class="mx-2 text-muted">/</span>
            <a href="temp-plots.php" class="text-decoration-none fw-bold"><?php echo $base_dir; ?></a>
            
            <?php
            // Dynamically build breadcrumbs for deep directories
            if ($req_dir) {
                $parts = explode('/', $req_dir);
                $accumulated_path = '';
                foreach ($parts as $part) {
                    $accumulated_path .= $accumulated_path ? '/' . $part : $part;
                    echo '<span class="mx-2 text-muted">/</span>';
                    echo '<a href="temp-plots.php?dir=' . urlencode($accumulated_path) . '" class="text-decoration-none fw-bold">' . htmlspecialchars($part) . '</a>';
                }
            }
            ?>
        </div>
    </div>

    <!-- Section: Subdirectories -->
    <?php if (!empty($directories)): ?>
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="h5 mb-3 text-muted">Directories</h2>
                <!-- Removed column-count to make it a single vertical list -->
                <ul class="list-unstyled directory-list">
                    <?php foreach ($directories as $dir): ?>
                        <li>
                            <a href="<?php echo $dir['url']; ?>">
                                <i class="bi bi-folder-fill text-secondary me-2"></i><?php echo htmlspecialchars($dir['name']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    <?php endif; ?>

    <!-- Section: PDF Documents -->
    <?php if (!empty($pdfs)): ?>
        <div class="row mb-5">
            <div class="col-12">
                <h2 class="h5 mb-3 text-muted">Documents (PDF)</h2>
                <ul class="list-unstyled directory-list">
                    <?php foreach ($pdfs as $pdf): ?>
                        <li>
                            <a href="<?php echo htmlspecialchars($pdf['url']); ?>" target="_blank">
                                <i class="bi bi-file-earmark-pdf-fill text-danger me-2"></i><?php echo htmlspecialchars($pdf['name']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    <?php endif; ?>

    <!-- Section: Images (Side-by-side auto-wrapping grid) -->
    <?php if (!empty($images)): ?>
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="h5 mb-3 text-muted border-bottom pb-2">Figures</h2>
                <!-- Flexbox wrap logic: items sit side-by-side and wrap to the next line automatically -->
                <div class="d-flex flex-wrap gap-4">
                    <?php foreach ($images as $img): ?>
                        <!-- Increased width to 350px for larger images -->
                        <div style="width: 350px;">
                            <div class="text-truncate mb-1 small text-muted font-monospace" title="<?php echo htmlspecialchars($img['name']); ?>">
                                <?php echo htmlspecialchars($img['name']); ?>
                            </div>
                            <a href="<?php echo htmlspecialchars($img['url']); ?>" target="_blank">
                                <!-- Removed border class and background color, increased height -->
                                <img src="<?php echo htmlspecialchars($img['url']); ?>" alt="<?php echo htmlspecialchars($img['name']); ?>" class="img-fluid" style="height: 260px; width: 100%; object-fit: contain;">
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Empty State -->
    <?php if (empty($directories) && empty($pdfs) && empty($images)): ?>
        <div class="text-center py-5 text-muted">
            <i class="bi bi-inbox fs-1"></i>
            <p class="mt-2">This directory is empty.</p>
        </div>
    <?php endif; ?>

</main>

<?php include 'footer.php'; ?>
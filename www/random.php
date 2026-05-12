<?php
include 'header.php';

// ---------------------------------------------------------
// 1. Configuration & Security
// ---------------------------------------------------------
$base_dir = 'random'; // The folder where you drop everything
$base_path = realpath(__DIR__ . '/' . $base_dir);

// CRITICAL: Check if the directory exists
if (!$base_path || !is_dir($base_path)) {
    echo '<main class="container"><div class="alert alert-warning mt-5">';
    echo '<i class="bi bi-exclamation-triangle-fill me-2"></i>';
    echo '<strong>Directory Not Found:</strong> The <code>' . $base_dir . '/</code> directory is missing.';
    echo '<br><a href="index.php" class="alert-link">Return to Home</a>';
    echo '</div></main>';
    include 'footer.php';
    exit;
}

// Sanitize and resolve requested sub-directory
$req_dir = isset($_GET['dir']) ? trim($_GET['dir'], '/') : '';
$target_path = realpath($base_path . '/' . $req_dir);

// Security Check: Prevent directory traversal 
// NOTE: If you use symlinks pointing outside the web root, this check will fail and block access.
if ($target_path === false || strpos($target_path, $base_path) !== 0 || !is_dir($target_path)) {
    $target_path = $base_path;
    $req_dir = '';
}

// ---------------------------------------------------------
// 2. Scan and Categorize Directory Contents
// ---------------------------------------------------------
$directories = [];
$images = [];
$files = [];

$items = scandir($target_path);
foreach ($items as $item) {
    if ($item === '.' || $item === '..') continue;

    $item_path = $target_path . '/' . $item;
    $rel_path = $req_dir ? $req_dir . '/' . $item : $item;

    // Check if it's a directory (or a symlink pointing to a directory)
    if (is_dir($item_path)) {
        $directories[$item] = [
            'name' => $item,
            'url' => 'random.php?dir=' . urlencode($rel_path)
        ];
    } else {
        $ext = strtolower(pathinfo($item, PATHINFO_EXTENSION));
        // Handle .tar.gz edge case
        if (str_ends_with(strtolower($item), '.tar.gz')) $ext = 'tar.gz';

        $file_url = $base_dir . '/' . $rel_path;

        if (in_array($ext, ['png', 'jpg', 'jpeg', 'gif', 'svg'])) {
            $images[$item] = [
                'name' => $item,
                'url' => $file_url
            ];
        } else {
            // Determine icon and color based on file type
            $icon = 'bi-file-earmark'; // Default
            $color = 'text-secondary';

            switch ($ext) {
                case 'pdf':
                    $icon = 'bi-file-earmark-pdf-fill';
                    $color = 'text-danger';
                    break;
                case 'json':
                case 'xml':
                    $icon = 'bi-file-earmark-code';
                    $color = 'text-warning';
                    break;
                case 'root':
                case 'dat':
                case 'csv':
                    $icon = 'bi-file-earmark-bar-graph-fill';
                    $color = 'text-success';
                    break;
                case 'tar.gz':
                case 'zip':
                case 'tar':
                    $icon = 'bi-file-earmark-zip-fill';
                    $color = 'text-secondary';
                    break;
                case 'py':
                case 'sh':
                case 'cpp':
                case 'c':
                case 'h':
                case 'php':
                    $icon = 'bi-file-earmark-text';
                    $color = 'text-primary';
                    break;
                case 'txt':
                case 'md':
                    $icon = 'bi-file-earmark-text-fill';
                    $color = 'text-dark';
                    break;
            }

            $files[$item] = [
                'name' => $item,
                'url' => $file_url,
                'icon' => $icon,
                'color' => $color
            ];
        }
    }
}

// --- APPLY NATURAL SORTING ---
uksort($directories, 'strnatcasecmp');
uksort($images, 'strnatcasecmp');
uksort($files, 'strnatcasecmp');
?>

<main class="container">

    <!-- Breadcrumb Navigation -->
    <div class="row mb-5">
        <div class="col-12 border-bottom pb-3">
            <a href="index.php" class="text-decoration-none fw-bold"><i class="bi bi-house-door-fill me-1"></i> Home</a>
            <span class="mx-2 text-muted">/</span>
            <a href="random.php" class="text-decoration-none fw-bold"><?php echo $base_dir; ?></a>

            <?php
            if ($req_dir) {
                $parts = explode('/', $req_dir);
                $accumulated_path = '';
                foreach ($parts as $part) {
                    $accumulated_path .= $accumulated_path ? '/' . $part : $part;
                    echo '<span class="mx-2 text-muted">/</span>';
                    echo '<a href="random.php?dir=' . urlencode($accumulated_path) . '" class="text-decoration-none fw-bold">' . htmlspecialchars($part) . '</a>';
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

    <!-- Section: General Files (Code, Data, Archives) -->
    <?php if (!empty($files)): ?>
        <div class="row mb-5">
            <div class="col-12">
                <h2 class="h5 mb-3 text-muted">Files & Documents</h2>
                <ul class="list-unstyled directory-list">
                    <?php foreach ($files as $file): ?>
                        <li>
                            <a href="<?php echo htmlspecialchars($file['url']); ?>" target="_blank">
                                <i class="bi <?php echo $file['icon']; ?> <?php echo $file['color']; ?> me-2"></i><?php echo htmlspecialchars($file['name']); ?>
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
                <h2 class="h5 mb-3 text-muted border-bottom pb-2">Images & Figures</h2>
                <div class="d-flex flex-wrap gap-4">
                    <?php foreach ($images as $img): ?>
                        <div style="width: 350px;">
                            <div class="text-truncate mb-1 small text-muted font-monospace" title="<?php echo htmlspecialchars($img['name']); ?>">
                                <?php echo htmlspecialchars($img['name']); ?>
                            </div>
                            <a href="<?php echo htmlspecialchars($img['url']); ?>" target="_blank">
                                <img src="<?php echo htmlspecialchars($img['url']); ?>" alt="<?php echo htmlspecialchars($img['name']); ?>" class="img-fluid" style="height: 260px; width: 100%; object-fit: contain;">
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Empty State -->
    <?php if (empty($directories) && empty($files) && empty($images)): ?>
        <div class="text-center py-5 text-muted">
            <i class="bi bi-inbox fs-1"></i>
            <p class="mt-2">This directory is empty.</p>
        </div>
    <?php endif; ?>

</main>

<?php include 'footer.php'; ?>
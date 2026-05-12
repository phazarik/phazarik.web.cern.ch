<?php
include 'header.php';

// ---------------------------------------------------------
// 1. Configuration & Security
// ---------------------------------------------------------
$base_dir = 'condor-dump';
$base_path = realpath(__DIR__ . '/' . $base_dir);

// Check if the condor-dump/ directory exists
if (!$base_path || !is_dir($base_path)) {
    echo '<main class="container"><div class="alert alert-warning mt-5">';
    echo '<i class="bi bi-exclamation-triangle-fill me-2"></i>';
    echo '<strong>Directory Not Found:</strong> The <code>' . $base_dir . '/</code> directory is missing.';
    echo '<br><a href="index.php" class="alert-link">Return to Home</a>';
    echo '</div></main>';
    include 'footer.php';
    exit;
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
$data_files = []; // For JSON and ROOT
$text_files = []; // For .txt and .log

$items = scandir($target_path);
foreach ($items as $item) {
    if ($item === '.' || $item === '..') continue;

    $item_path = $target_path . '/' . $item;
    $rel_path = $req_dir ? $req_dir . '/' . $item : $item;
    $file_url = $base_dir . '/' . $rel_path;

    if (is_dir($item_path)) {
        $directories[$item] = [
            'name' => $item,
            'url' => 'condor_dump.php?dir=' . urlencode($rel_path)
        ];
    } else {
        $ext = strtolower(pathinfo($item, PATHINFO_EXTENSION));

        if ($ext === 'pdf') {
            $pdfs[$item] = ['name' => $item, 'url' => $file_url];
        } elseif (in_array($ext, ['png', 'jpg', 'jpeg'])) {
            $images[$item] = ['name' => $item, 'url' => $file_url];
        } elseif (in_array($ext, ['json', 'root'])) {
            $data_files[$item] = ['name' => $item, 'url' => $file_url, 'ext' => $ext];
        } elseif (in_array($ext, ['txt', 'log', 'out', 'err'])) {
            $text_files[$item] = ['name' => $item, 'url' => $file_url];
        }
    }
}

// Apply natural sorting
uksort($directories, 'strnatcasecmp');
uksort($pdfs, 'strnatcasecmp');
uksort($images, 'strnatcasecmp');
uksort($data_files, 'strnatcasecmp');
uksort($text_files, 'strnatcasecmp');
?>

<main class="container">

    <!-- Breadcrumb Navigation -->
    <div class="row mb-5">
        <div class="col-12 border-bottom pb-3">
            <a href="index.php" class="text-decoration-none fw-bold"><i class="bi bi-house-door-fill me-1"></i> Home</a>
            <span class="mx-2 text-muted">/</span>
            <a href="condor_dump.php" class="text-decoration-none fw-bold"><?php echo $base_dir; ?></a>
            <?php
            if ($req_dir) {
                $parts = explode('/', $req_dir);
                $accumulated_path = '';
                foreach ($parts as $part) {
                    $accumulated_path .= $accumulated_path ? '/' . $part : $part;
                    echo '<span class="mx-2 text-muted">/</span>';
                    echo '<a href="condor_dump.php?dir=' . urlencode($accumulated_path) . '" class="text-decoration-none fw-bold">' . htmlspecialchars($part) . '</a>';
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
                        <li><a href="<?php echo $dir['url']; ?>"><i class="bi bi-folder-fill text-secondary me-2"></i><?php echo htmlspecialchars($dir['name']); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    <?php endif; ?>

    <!-- Section: Text/Logs -->
    <?php if (!empty($text_files)): ?>
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="h5 mb-3 text-muted">Logs & text</h2>
                <ul class="list-unstyled directory-list">
                    <?php foreach ($text_files as $txt): ?>
                        <li><a href="<?php echo htmlspecialchars($txt['url']); ?>" target="_blank"><i class="bi bi-file-earmark-text me-2"></i><?php echo htmlspecialchars($txt['name']); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    <?php endif; ?>

    <!-- Section: Analysis Data (JSON/ROOT) -->
    <?php if (!empty($data_files)): ?>
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="h5 mb-3 text-muted">Data (JSON/ROOT)</h2>
                <ul class="list-unstyled directory-list">
                    <?php foreach ($data_files as $data): ?>
                        <li>
                            <a href="<?php echo htmlspecialchars($data['url']); ?>" download>
                                <i class="bi <?php echo ($data['ext'] === 'json') ? 'bi-braces' : 'bi-database-fill'; ?> text-info me-2"></i><?php echo htmlspecialchars($data['name']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    <?php endif; ?>

    <!-- Section: PDFs -->
    <?php if (!empty($pdfs)): ?>
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="h5 mb-3 text-muted">Documents (PDF)</h2>
                <ul class="list-unstyled directory-list">
                    <?php foreach ($pdfs as $pdf): ?>
                        <li><a href="<?php echo htmlspecialchars($pdf['url']); ?>" target="_blank"><i class="bi bi-file-earmark-pdf-fill text-danger me-2"></i><?php echo htmlspecialchars($pdf['name']); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    <?php endif; ?>

    <!-- Section: Figures -->
    <?php if (!empty($images)): ?>
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="h5 mb-3 text-muted border-bottom pb-2">Figures</h2>
                <div class="d-flex flex-wrap gap-4">
                    <?php foreach ($images as $img): ?>
                        <div style="width: 350px;">
                            <div class="text-truncate mb-1 small text-muted font-monospace" title="<?php echo htmlspecialchars($img['name']); ?>">
                                <?php echo htmlspecialchars($img['name']); ?>
                            </div>
                            <a href="<?php echo htmlspecialchars($img['url']); ?>" target="_blank">
                                <img src="<?php echo htmlspecialchars($img['url']); ?>" class="img-fluid" style="height: 260px; width: 100%; object-fit: contain;">
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Empty State -->
    <?php if (empty($directories) && empty($pdfs) && empty($images) && empty($data_files) && empty($text_files)): ?>
        <div class="text-center py-5 text-muted">
            <i class="bi bi-inbox fs-1"></i>
            <p class="mt-2">This directory is empty.</p>
        </div>
    <?php endif; ?>

</main>
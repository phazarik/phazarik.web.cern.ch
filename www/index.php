<?php include 'header.php'; ?>

<main class="container">
    <!-- Minimal Header -->
    <div class="row mb-5">
        <div class="col-12">
            <h1 class="h2 mb-1">Prachu's personal CERN webpage</h1>
            <p class="text-muted">Welcome to my CMS internal webpage. Hosted on CERN EOS and powered by PHP and Bootstrap, this site acts as a centralized hub for my ongoing research. It currently features restricted data, plots, and documentation for the NPS-25-011 analysis (search for vector-like leptons in multilepton final states).</p>
        </div>
    </div>

    <!-- Directory Links -->
    <div class="row">

        <!-- Analysis Links -->
        <div class="col-md-6 mb-5">
            <h2 class="h5 border-bottom pb-2 mb-3">Analysis & reviews for NPS-25-011</h2>
            <ul class="list-unstyled directory-list">
                <li class="mb-2">
                    <a href="https://cms-pub-talk.web.cern.ch/c/nps/nps-25-011/818" target="_blank" class="text-decoration-none">
                        <i class="bi bi-chat-left-text-fill me-2 text-primary"></i>CMS-NPS-25-011 Analysis Review
                    </a>
                </li>
                <li class="mb-2">
                    <a href="https://twiki.cern.ch/twiki/bin/view/CMS/ReviewOfNPS25011" target="_blank" class="text-decoration-none">
                        <i class="bi bi-journal-text me-2 text-primary"></i>CMS-NPS-25-011 TWiki
                    </a>
                </li>
            </ul>
        </div>

        <!-- External Links -->
        <div class="col-md-6 mb-5">
            <h2 class="h5 border-bottom pb-2 mb-3">Useful external links</h2>
            <ul class="list-unstyled directory-list">
                <li class="mb-2">
                    <a href="https://phazarik.github.io/pages/mc-generation.html" target="_blank" class="text-decoration-none">
                        <i class="bi bi-box-arrow-up-right me-2 text-success"></i>MC Generation tutorial
                    </a>
                    <span class="text-muted ms-1">- Replaced by setup in <a href="https://github.com/phazarik/VLL-gridpack-generation" target="_blank" class="link-secondary inline-link">GitHub</a>.</span>
                </li>
                <li class="mb-2">
                    <a href="https://phazarik.github.io/pages/mc-contacts.html" target="_blank" class="text-decoration-none">
                        <i class="bi bi-box-arrow-up-right me-2 text-success"></i>MC Contacts tutorial
                    </a>
                </li>
            </ul>
        </div>

        <!-- Data & Plots -->
        <div class="col-md-12 mb-5">
            <h2 class="h5 border-bottom pb-2 mb-3">Navigate temporary data</h2>
            <ul class="list-unstyled directory-list">
                <li class="mb-2">
                    <a href="temp-plots.php" class="text-decoration-none">
                        <i class="bi bi-graph-up-arrow me-2 text-secondary"></i>/temp-plots
                    </a>
                    <span class="text-muted ms-1">- Temporary plots for postfit study</span>
                </li>
                <li class="mb-2">
                    <a href="condor_dump.php" class="text-decoration-none">
                        <i class="bi bi-cpu-fill me-2 text-secondary"></i>/condor-dump
                    </a>
                    <span class="text-muted ms-1">- Condor job dump directory</span>
                </li>
                <li class="mb-2">
                    <a href="random.php" class="text-decoration-none">
                        <i class="bi bi-collection-fill me-2 text-secondary"></i>/random
                    </a>
                    <span class="text-muted ms-1">- Random stuff for easy sharing</span>
                </li>
                <li class="mb-2">
                    <a href="circles/web/index.php" class="text-decoration-none">
                        <i class="bi bi-pie-chart-fill me-2 text-secondary"></i>/pie-charts
                    </a>
                    <span class="text-muted ms-1">- Displays FastTimerService like pie-charts</span>
                </li>
            </ul>
        </div>

    </div>
</main>

<?php include 'footer.php'; ?>
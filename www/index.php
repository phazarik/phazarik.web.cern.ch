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
                <h2 class="h5 border-bottom pb-2 mb-3">Analysis & Reviews</h2>
                <ul class="list-unstyled directory-list">
                    <li><a href="https://cms-pub-talk.web.cern.ch/c/nps/nps-25-011/818" target="_blank">&#8627; CMS-NPS-25-011 Analysis Review</a></li>
                    <li><a href="https://twiki.cern.ch/twiki/bin/view/CMS/ReviewOfNPS25011" target="_blank">&#8627; CMS-NPS-25-011 TWiki</a></li>
                </ul>
            </div>

            <!-- Data & Plots -->
            <div class="col-md-6 mb-5">
                <h2 class="h5 border-bottom pb-2 mb-3">Data Directories</h2>
                <ul class="list-unstyled directory-list">
                    <li><a href="temp-plots.php">&#8627; /temp-plots</a> <span class="text-muted">- Temporary plots for postfit study</span></li>
                    <li><a href="circles/web/index.php">&#8627; /pie-charts</a> <span class="text-muted">- circles tool for displaying FastTimerService like pie-charts</span></li>
                </ul>
            </div>
        </div>
    </main>

<?php include 'footer.php'; ?>
<header>
    <div class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a href="<?= base_url() ?>" class="navbar-brand d-flex align-items-center">

                <strong>Short URL</strong>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#bdNavbar" aria-controls="bdNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="bdNavbar">
                <ul class="navbar-nav flex-row flex-wrap bd-navbar-nav pt-2 py-md-0">
                    <li class="nav-item col-6 col-md-auto">
                        <a class="nav-link p-2" href="<?= base_url() ?>">Home</a>
                    </li>
                    <li class="nav-item col-6 col-md-auto">
                        <a class="nav-link p-2" href="<?= base_url('statistics') ?>">Statistics</a>
                    </li>
                </ul>
            </div>
            
        </div>
</header>
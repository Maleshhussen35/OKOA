<!-- In includes/footer.php -->
<footer class="site-footer bg-info bg-opacity-10 pt-4 mt-auto">
    <div class="container">
        <div class="row g-3 align-items-center">
            <!-- Social Links -->
            <div class="col-md-6 text-center text-md-start">
                <div class="d-flex gap-3 justify-content-center justify-content-md-start">
                    <a href="https://www.instagram.com/malesh_hussen_35" 
                       target="_blank" 
                       rel="noopener noreferrer"
                       class="text-decoration-none social-icon"
                       aria-label="Instagram Profile">
                        <i class="bi bi-instagram fs-5 text-primary"></i>
                    </a>
                    <a href="https://github.com/maleshhussen35"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="text-decoration-none social-icon"
                       aria-label="GitHub Profile">
                        <i class="bi bi-github fs-5 text-primary"></i>
                    </a>
                </div>
            </div>

            <!-- Copyright -->
            <div class="col-md-6 text-center text-md-end mt-2 mt-md-0">
                <p class="mb-0 text-secondary">
                    © <?= date('Y') ?> OKOA SEMETER
                    <span class="d-none d-md-inline">•</span><br class="d-md-none">
                    Designed by  <i><b>malesh</b></i>
                </p>
            </div>
        </div>
    </div>
</footer>

<style>
    .site-footer {
        border-top: 1px solid rgba(0, 0, 0, 0.1);
        background: linear-gradient(15deg, #f0f9ff 0%, #e0f2fe 100%);
    }

    .social-icon {
        transition: all 0.2s ease-in-out;
        padding: 0.5rem;
        border-radius: 50%;
    }

    .social-icon:hover {
        background-color: rgba(14, 165, 233, 0.1);
        transform: translateY(-2px);
    }

    .social-icon i {
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Ensure footer stays at bottom */
    body {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    main {
        flex: 1;
    }
</style>
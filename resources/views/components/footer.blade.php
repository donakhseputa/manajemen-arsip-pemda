<footer class="content-footer footer bg-footer-theme">
    <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
        <div class="mb-2 mb-md-0">
            &copy; copyright
            <script>
                document.write(new Date().getFullYear());
            </script>
            , {{ __('footer.made_by') }}
            <a href="https://github.com/donakhseputa" target="_blank" class="footer-link fw-bolder">DONAKHSEPUTA</a>
            <span class="badge bg-primary">
                v{{ config('app.version') }}
            </span>
        </div>
    </div>
</footer>

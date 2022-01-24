<section id="dashboardSystem" class="dashboardSystem">
    <article class="dashboardContent column">
        <h3>Sauvegarde</h3>
        <form method='POST' action='index.php?route=adminPanelSystemDownloadAllFiles'>
           <button class="btn-warning" type='submit' name='create'>Exporter l'intégralité des fichiers présents sur le serveur</button>
        </form>
        <form method='POST' action='index.php?route=backupDatabase'>
           <button class="btn" type='submit' name='create'>Exporter la base de données</button>
        </form>
    </article>
</section>
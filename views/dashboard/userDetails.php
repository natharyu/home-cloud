<section id="userDetails" class="userDetails">
    <div class="mainAccount">
        <article class="dashboardContent column">
            <a class="btn" href="index.php?route=adminPanelUsers"><i class="fas fa-arrow-left"></i> Retour</a>
            <h3>Nom d'utilisateur :</h3>
            <p><?= htmlspecialchars($user['username']) ?></p>
    
            <?php if (htmlspecialchars($user['email']) != null): ?>
                <h3>Adresse mail :</h3>
                <p><?= htmlspecialchars($user['email']) ?></p>
            <?php endif; ?>
    
            <h3>Inscris depuis le <?= htmlspecialchars($user['created_at']) ?></h3>
        </article>
        <article class="dashboardContent column">
            <h3>Rôle :</h3>
    
            <?php if (htmlspecialchars($user['role']) === 'admin'): ?>
                <p>Administrateur</p>
            <?php else: ?>
                <p>Utilisateur</p>
            <?php endif; ?>
    
        </article>
    </div>
    <article class="dashboardContent column">
        <h2>Interactions</h2>
        <input type="hidden" id="userId" value="<?= htmlspecialchars($user['id']) ?>"/>
        <a class="btn-warning" href="index.php?route=adminPanelUserModify&id=<?= htmlspecialchars($user['id']) ?>">Modifier cet utilisateur</a>
        <a class="btn-danger" id="dashboardDeleteOneUser">Supprimer cet utilisateur</a>
    </article>
</section>
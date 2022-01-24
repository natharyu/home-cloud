<section id="users" class="users">
    <article class="dashboardContent">
        <h2 class="column">Tous les utilisateurs</h2>
    </article>
    <article class="filesView dashboardContent">
        <table>
            <thead>
                <tr>
                    <th>Rôle</th>
                    <th>Nom d'utilisateur</th>
                    <th>E-mail</th>
                    <th>Créé le</th>
                    <th>Options</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['role']) ?></td>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= htmlspecialchars($user['created_at']) ?></td>
                    <td>
                        <input type="hidden" id="userId" value="<?= htmlspecialchars($user['id']) ?>"/>
                        <a class="btn-warning" href="index.php?route=adminPanelUserDetails&id=<?= htmlspecialchars($user['id']) ?>">Détails</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </article>
</section>
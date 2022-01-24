<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Home-Cloud</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="https://api.iconify.design/mdi/cloud.svg?color=%233282b8" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/normalize.css" type="text/css" />
    <link rel="stylesheet" href="assets/css/styles.css" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Lobster&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
</head>
<body>
    <header id="headerDashboard" class="headerDashboard">
        <button class="arrowDashboardMenu">
            <i id="arrowDashboardMenu" class="fas fa-chevron-left"></i>
        </button>
        <div class="column">
            <svg aria-hidden="true" role="img" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                <a href="index.php?route=home">
                <path d="M 18.978 10.03 C 18.316 6.523 15.394 3.998 12 4 C 9.256 4 6.874 5.64 5.687 8.03 C 2.795 8.364 0.607 10.937 0.608 14 C 0.608 17.314 3.158 20 6.304 20 L 18.646 20 C 21.267 20 23.392 17.761 23.392 15 C 23.392 12.36 21.446 10.22 18.978 10.03 Z" fill="rgba(50, 130, 184, 1)" stroke-width="0.5px" style="stroke: rgb(255, 255, 255);"/>
                </a>
            </svg>
            <h2 id="headerDashboardTitle" class="headerDashboardTitle"><a href="index.php?route=home">Home-Cloud</a></h2>
        </div>
        <nav>
            <a id="firstLink" href="index.php?route=home">
                <i class="fas fa-home"></i>
                <div id="firstMenu" class="dashboardMenuTitle">Home-Cloud</div>
            </a>
            <a id="secondLink" href="index.php?route=adminPanel">
                <i class="far fa-eye"></i>
                <div id="secondMenu" class="dashboardMenuTitle">Vue générale</div>
            </a>
            <a id="thirdLink" href="index.php?route=adminPanelUsers">
                <i class="fas fa-users"></i>
                <div id="thirdMenu" class="dashboardMenuTitle">Utilisateurs</div>
            </a>
            <a id="fourthLink" href="index.php?route=adminPanelSystem">
                <i class="fas fa-cogs"></i>
                <div id="fourthMenu" class="dashboardMenuTitle">Système</div>
            </a>
        </nav>
    </header>

        
        <main id="dashboardMain">
            <?php include htmlspecialchars($view); ?>
        </main>
        
        <footer>
        </footer>
    <script src="assets/js/dashboard.js"></script>
</body>
</html>
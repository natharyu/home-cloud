<section id="weather" class="weather">
    <article class="mainContent">
        <h3 class="column">Météo</h3>

        <form action="index.php?route=weatherSearchCity" method="POST">
            <input type="search" name="city" placeholder="Chercher une ville" required>
            <button class="btn" id="citySearch">Chercher</button>
        </form>

        <?php if( isset( $_GET['error'] ) ) : ?>
            <p class="error column"><?= htmlspecialchars($_GET['error']) ?></p>
        <?php endif; ?>
    </article>
    <article class="mainContent">
        <h3 class="column">Prévisions météo sur 7 jours pour <?= htmlspecialchars($data['city']['name']) ?> : </h3>
        <div class="weatherAllDays">
            <?php foreach ( $data['list'] as $day => $value ): ?>
                <div class="weatherPerDay">
                    <h3>
                        <?php switch(date('l',htmlspecialchars($value['dt'])))
                        {
                                case 'Monday':
                                    echo "Lundi";
                                    break;
                                case 'Tuesday':
                                    echo "Mardi";
                                    break;
                                case 'Wednesday':
                                    echo "Mercredi";
                                    break;
                                case 'Thursday':
                                    echo "Jeudi";
                                    break;
                                case 'Friday':
                                    echo "Vendredi";
                                    break;
                                case 'Saturday':
                                    echo "Samedi";
                                    break;
                                case 'Sunday':
                                    echo "Dimanche";
                                    break;
                        }
                        ?>
                      </h3>
                    <img src="http://openweathermap.org/img/wn/<?=htmlspecialchars($value['weather'][0]['icon']) ?>@2x.png"/>
                    <p><?= $value['weather'][0]['description'] ?></p>
                    <p class="weatherMaxTemp">Max : <?= htmlspecialchars(intval($value['temp']['max'])) ?>°C</p>
                    <p class="weatherMinTemp">Min : <?= htmlspecialchars(intval($value['temp']['min'])) ?>°C</p>
                </div>
            <?php endforeach; ?>
        </div>
    </article>
</section>
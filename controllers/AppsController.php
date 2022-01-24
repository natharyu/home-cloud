<?php
namespace Controllers;

class AppsController
{
    //Affiche toutes les tàches de l'utilisateur
    public function todolist()
    {
        $newUser = new \Models\User();
        $user = $newUser->getOneUserBySessionKey($_SESSION['sessionKey']);

        $newTodolist = new \Models\Todolist();
        $tasks = $newTodolist->getTasksByUserId($user['id']);
        
        $view = 'apps/todolist/todolist.php';
        include_once 'views/template.php' ;
    }

    //Affiche toutes les tàches déjà faites de l'utilisateur
    public function todolistDone()
    {
        $newUser = new \Models\User();
        $user = $newUser->getOneUserBySessionKey($_SESSION['sessionKey']);

        $newTodolist = new \Models\Todolist();
        $tasks = $newTodolist->getAllOneTwoConditions($user['id'], 1);
                
        $view = 'apps/todolist/todolist.php';
        include_once 'views/template.php' ;
    }

    //Affiche toutes les tàches pas encore faites de l'utilisateur
    public function todolistNotDone()
    {
        $newUser = new \Models\User();
        $user = $newUser->getOneUserBySessionKey($_SESSION['sessionKey']);

        $newTodolist = new \Models\Todolist();
        $tasks = $newTodolist->getAllOneTwoConditions($user['id'], 0);
                
        $view = 'apps/todolist/todolist.php';
        include_once 'views/template.php' ;
    }

    //Ajoute une tàche dans la liste
    public function todolistAddTask()
    {
        try
        {
            if( isset( $_SESSION['sessionKey'] ) && !empty( $_SESSION['sessionKey'] ) &&
                isset( $_POST['task'] ) && !empty( $_POST['task'] ) )
            {
                    $newUser = new \Models\User();
                    $user = $newUser->getOneUserBySessionKey($_SESSION['sessionKey']);
                    $newData=[
                        $_POST['task'],
                        0,
                        $user['id']
                    ];
                    $newTodolist = new \Models\Todolist();
                    $newTodolist->addOneTask($newData);

                    header( 'Location: index.php?route=todolist' );
                    exit();
            }
            else
            {
                throw new \Exception('Le nom de la tache est vide !');
            }
        }
        catch( \Exception $exeption )
        {
            header('Location: index.php?route=todolist&error=' . $exeption->getMessage() );
            exit();
        }
    }
    
    //Modifie le statut de la tàche (pas faite -> faite et inversement)
    public function modifyTaskDoneStatus() 
    {
        try
        {
            if( isset( $_SESSION['sessionKey'] ) && !empty( $_SESSION['sessionKey'] ) &&
                isset( $_POST['id'] ) && !empty( $_POST['id'] ) )
            { 
            
                $newTodolist = new \Models\Todolist();
                $newUser = new \Models\User();
                $user = $newUser->getOneUserBySessionKey($_SESSION['sessionKey']);
                $task = $newTodolist->getOneTaskById($_POST['id']);
                if ($task['done'] == 0)
                {
                    $newData=[
                        'done' => 1
                    ];        
                    $newTodolist->updateOneTask($newData, $task['id']);
                }
                else
                {
                    $newData=[
                        'done' => 0
                    ];        
                    $newTodolist->updateOneTask($newData, $task['id']);
                }
                
                if($_POST['route'] === 'todolist')
                {
                    header( 'Location: index.php?route=todolist' );
                    exit();
                }
                elseif($_POST['route'] === 'todolistDone')
                {
                    header( 'Location: index.php?route=todolistDone' );
                    exit();
                }
                elseif($_POST['route'] === 'todolistNotDone')
                {
                    header( 'Location: index.php?route=todolistNotDone' );
                    exit();
                }
            }
            else
            {
                throw new \Exception('Une erreur s\'est produite');
            }
        }
        catch( \Exception $exeption )
        {
            header('Location: index.php?route=todolist&error=' . $exeption->getMessage() );
            exit();
        }
    }

    //Supprime la tàche
    public function deleteOneTask()
    {   
        try
        {
            if(isset( $_POST['id'] ) && !empty( $_POST['id'] ) )
            { 
            
                $newTodolist = new \Models\Todolist();
                $newTodolist->deleteOneTask($_POST['id']);
                
                if($_POST['route'] === 'todolist')
                {
                    header( 'Location: index.php?route=todolist' );
                    exit();
                }
                elseif($_POST['route'] === 'todolistDone')
                {
                    header( 'Location: index.php?route=todolistDone' );
                    exit();
                }
                elseif($_POST['route'] === 'todolistNotDone')
                {
                    header( 'Location: index.php?route=todolistNotDone' );
                    exit();
                }

            }
            else
            {
                throw new \Exception('Une erreur s\'est produite');
            }
        }
        catch( \Exception $exeption )
        {
            header('Location: index.php?route=todolist&error=' . $exeption->getMessage() );
            exit();
        }
    }

    //Supprime toutes les tàches de l'utilisateur
    public function deleteAllTasksForOneUser()
    {   
        try
        {
            if(isset( $_SESSION['sessionKey'] ) && !empty( $_SESSION['sessionKey'] ) )
            { 
                $newUser = new \Models\User();
                $user = $newUser->getOneUserBySessionKey($_SESSION['sessionKey']);

                $newTodolist = new \Models\Todolist();
                $newTodolist->deleteAllTasksForOneUser($user['id']);

                header( 'Location: index.php?route=todolist' );
                exit();

            }
            else
            {
                throw new \Exception('Une erreur s\'est produite');
            }
        }
        catch( \Exception $exeption )
        {
            header('Location: index.php?route=todolist&error=' . $exeption->getMessage() );
            exit();
        }
    }

    //Affichage de la view météo avec geolocalisation via ip
    public function weather()
    {   
        $newUser = new \Models\User();
        $user = $newUser->getOneUserBySessionKey($_SESSION['sessionKey']);
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://freegeoip.app/json/",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "accept: application/json",
                "content-type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } 
        else {
            $response;
            $objResult = json_decode($response);
            $result = (array) $objResult;
            foreach ( $result as $info => $value )
            {
                $info . ' = ' . $value . '<br>';
            }
        }

        $city = $result['city'];
        $apiWeatherKey = "c0c4a4b4047b97ebc5948ac9c48c0559";
        $url = 'http://api.openweathermap.org/data/2.5/forecast/daily?q='.$city.'&units=metric&lang=fr&appid='.$apiWeatherKey;
        $json = file_get_contents( $url );
        $data = json_decode( $json, true );
        
        $view = 'apps/weather/weather.php';
        include_once 'views/template.php' ;
    }

    //Recherche une ville et renvoie la météo correspondant a cette ville
    public function weatherSearchByCity()
    {   
        $newUser = new \Models\User();
        $user = $newUser->getOneUserBySessionKey($_SESSION['sessionKey']);
        try
        {
            if (isset($_POST['city']) && !empty($_POST['city']))
            {
            
                $city = $_POST['city'];
                $apiWeatherKey = "c0c4a4b4047b97ebc5948ac9c48c0559";
                $url = 'http://api.openweathermap.org/data/2.5/forecast/daily?q='.$city.'&units=metric&lang=fr&appid='.$apiWeatherKey;
                if ($json = file_get_contents( $url ))
                {
                    $data = json_decode( $json, true );

                
                    $view = 'apps/weather/weather.php';
                    include_once 'views/template.php' ;
                }
                else
                {
                    throw new \Exception('Cette ville n\'est pas reconnue, entrez une ville proche de celle-ci');
                }
            }
        }
        catch( \Exception $exeption )
        {
            header('Location: index.php?route=weather&error=' . $exeption->getMessage() );
            exit();
        }
    }

    public function calendar()
    {
        $newUser = new \Models\User();
        $user = $newUser->getOneUserBySessionKey($_SESSION['sessionKey']);
        
        $view = 'apps/calendar/calendar.php';
        include_once 'views/template.php' ;
    }
}
?>
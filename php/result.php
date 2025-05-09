<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_player_index = ($_SESSION['current_roll'] ?? 0) % count($_SESSION['players']);
    $current_player = $_SESSION['players'][$current_player_index];
    
    $rolls = [];
    $total = 0;
    for ($i = 0; $i < $_SESSION['num_dice']; $i++) {
        $roll = rand(1, 6);
        $rolls[] = $roll;
        $total += $roll;
    }
    
    $_SESSION['results'][$_SESSION['current_game']][$current_player] = [
        'rolls' => $rolls,
        'total' => $total
    ];
    
    $_SESSION['player_rolls'][$current_player]++;
    
    $all_rolled = true;
    foreach ($_SESSION['player_rolls'] as $rolls) {
        if ($rolls < $_SESSION['current_game']) {
            $all_rolled = false;
            break;
        }
    }
    
    if ($all_rolled) {
        if ($_SESSION['current_game'] < $_SESSION['num_games']) {
            $_SESSION['current_game']++;
            foreach ($_SESSION['players'] as $player) {
                $_SESSION['player_rolls'][$player] = 0;
            }
            header("Location: game.php");
            exit();
        } else {
            header("Location: final_results.php");
            exit();
        }
    } else {
        $_SESSION['current_roll'] = ($_SESSION['current_roll'] ?? 0) + 1;
        header("Location: game.php");
        exit();
    }
}

header("Location: game.php");
exit();
?>

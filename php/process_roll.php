<?php
session_start();

$rolls = explode(',', $_POST['rolls']);
$rolls = array_map('intval', $rolls);

$current_player = $_SESSION['players'][$_SESSION['current_player']];
$_SESSION['results'][$_SESSION['current_game']][$current_player] = $rolls;

if (!isset($_SESSION['cumulative_scores'])) {
    $_SESSION['cumulative_scores'] = array_fill_keys($_SESSION['players'], 0);
}

$_SESSION['cumulative_scores'][$current_player] += array_sum($rolls);

$_SESSION['current_player']++;

if ($_SESSION['current_player'] >= count($_SESSION['players'])) {
    $_SESSION['current_player'] = 0;
    $_SESSION['current_game']++;
    
    if ($_SESSION['current_game'] > $_SESSION['num_games']) {
        header("Location: final_results.php");
        exit();
    }
}

header("Location: game.php");
exit();
?>

<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Končni rezultati</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/finalResultsStyle.css">
</head>
<body>
    <h1 id="finalTitle">END RESULTS</h1>

    <div id="main">
        <div class="results-container">
            <?php
            $totalScores = $_SESSION['cumulative_scores'] ?? [];
            arsort($totalScores);
            $topThree = array_slice($totalScores, 0, 3, true);
            ?>
            
            <div class="podium">
                <?php 
                $position = 1;
                foreach ($topThree as $player => $score): 
                    $podiumClass = 'podium-' . $position;
                ?>
                    <div class="podium-step <?php echo $podiumClass; ?>">
                        <div class="podium-position"><?php echo $position; ?>.</div>
                        <div class="podium-player"><?php echo $player; ?></div>
                        <div class="podium-score"><?php echo $score; ?></div>
                    </div>
                <?php 
                    $position++;
                endforeach; 
                ?>
            </div>
            
            <?php foreach ($_SESSION['results'] as $game => $players): ?>
                <div class="game-result">
                    <h2 class="game-title">Game <?php echo $game; ?></h2>
                    
                    <?php 
                    $playerTotals = [];
                    foreach ($players as $player => $rolls) {
                        $playerTotals[$player] = array_sum($rolls);
                    }
                    arsort($playerTotals);
                    ?>
                    
                    <table class="leaderboard">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Player</th>
                                <th>Throws</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $position = 1; ?>
                            <?php foreach ($playerTotals as $player => $total): ?>
                                <tr>
                                    <td class="position">
                                        <?php 
                                        if ($position === 1) echo '🥇';
                                        elseif ($position === 2) echo '🥈';
                                        elseif ($position === 3) echo '🥉';
                                        else echo $position;
                                        ?>
                                    </td>
                                    <td class="player-name"><?php echo $player; ?></td>
                                    <td class="dice-rolls">
                                        <?php foreach ($players[$player] as $roll): ?>
                                            <img src="../assets/dice<?php echo $roll; ?>.svg" class="dice" alt="Dice <?php echo $roll; ?>">
                                        <?php endforeach; ?>
                                    </td>
                                    <td class="total-score"><?php echo $total; ?></td>
                                </tr>
                                <?php $position++; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endforeach; ?>
            
            <div class="new-game-container">
                <a href="../index.html" class="new-game-btn">New game</a>
            </div>
        </div>

    </div>
</body>
</html>

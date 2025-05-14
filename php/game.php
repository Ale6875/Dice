<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['num_dice'] = (int)$_POST['dice'];
    $_SESSION['num_games'] = (int)$_POST['games'];
    $_SESSION['players'] = [
        $_POST['user1'],
        $_POST['user2'],
        $_POST['user3']
    ];
    $_SESSION['current_game'] = 1;
    $_SESSION['results'] = [];
    $_SESSION['current_player'] = 0;
    $_SESSION['cumulative_scores'] = array_fill_keys($_SESSION['players'], 0);
}

if (!isset($_SESSION['num_dice'])) {
    header("Location: ../index.html");
    exit();
}

$current_player = $_SESSION['players'][$_SESSION['current_player']];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/gameStyle.css">
</head>
<body>
    <h1 id="title">ROUND <?php echo $_SESSION['current_game']; ?> OF <?php echo $_SESSION['num_games']; ?></h1>

    <div id="main">
        <div class="game-info">
            <h2><?php echo $current_player; ?>, throw the dice!</h2>
            <p>>You have to throw! <?php echo $_SESSION['num_dice']; ?> dice</p>
        </div>

        <div class="dice-container" id="dice-container">
            <?php for ($i = 0; $i < $_SESSION['num_dice']; $i++): ?>
                <img src="../assets/dice1.svg" alt="Dice" class="dice" data-index="<?php echo $i; ?>">
            <?php endfor; ?>
        </div>

        <form action="process_roll.php" method="POST" id="roll-form">
            <input type="hidden" name="rolls" id="rolls-input">
            <button type="button" class="roll-button" id="roll-button">Throw dice</button>
        </form>

        <div class="player-status">
            <?php foreach ($_SESSION['players'] as $index => $player): ?>
                <div class="player-box <?php echo $index === $_SESSION['current_player'] ? 'current-player' : ''; ?>">
                    <h3><?php echo $player; ?></h3>
                    <p>Total: <?php echo $_SESSION['cumulative_scores'][$player] ?? 0; ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        document.getElementById('roll-button').addEventListener('click', function() {
            const diceContainer = document.getElementById('dice-container');
            const diceImages = diceContainer.querySelectorAll('.dice');
            const button = document.getElementById('roll-button');
            const form = document.getElementById('roll-form');
            const rollsInput = document.getElementById('rolls-input');
            
            button.disabled = true;
            button.textContent = '...';
            
            diceImages.forEach(dice => {
                dice.classList.add('rolling');
            });
            
            const rolls = [];
            for (let i = 0; i < <?php echo $_SESSION['num_dice']; ?>; i++) {
                rolls.push(Math.floor(Math.random() * 6) + 1);
            }
            
            const animationInterval = setInterval(() => {
                diceImages.forEach(dice => {
                    const randomFace = Math.floor(Math.random() * 6) + 1;
                    dice.src = `../assets/dice${randomFace}.svg`;
                });
            }, 100);
            
            setTimeout(() => {
                clearInterval(animationInterval);
                diceImages.forEach(dice => {
                    dice.classList.remove('rolling');
                });
                
                diceImages.forEach((dice, index) => {
                    setTimeout(() => {
                        dice.src = `../assets/dice${rolls[index]}.svg`;
                        dice.style.transform = 'scale(1.1)';
                        setTimeout(() => {
                            dice.style.transform = 'scale(1)';
                        }, 200);
                    }, index * 100);
                });
                
                setTimeout(() => {
                    rollsInput.value = rolls.join(',');
                    form.submit();
                }, <?php echo $_SESSION['num_dice']; ?> * 100 + 1000);
            }, 2000);
        });
    </script>
</body>
</html>

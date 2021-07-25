<?php require_once 'Controller.php'?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>blackjack</title>
    <link rel="stylesheet" href="cards.css">
    <link rel="stylesheet" href="bj.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ruslan+Display&display=swap" rel="stylesheet">
</head>
<body>

<header>
    <div class="header__bankBetDisplay">
        <div>Ваш банк - <?=$round->bank?></div>
        <div>Ваша ставка - <?=$round->bet?></div>
    </div>

    <div class="header__logo"></div>

    <form action="View.php" method="post" name="reload">
        <input type="hidden" name="reload" checked>
        <button class="playerButton playerButton_move" type="submit">Перезапустить игру</button>
    </form>

</header>

<main class="mainField">

<!-- Форма запроса банка -->
<?php if (!($round->bank)):?>
    <form class="bankForm" action="View.php" method="post">
        <p>Сколько у Вас денег?</p>
        <input type="text" name="bank" placeholder="введите целое число">
        <button class="playerButton playerButton_bankBet" type="submit">Ok</button>
    </form>
<?php endif; ?>

<!-- Форма запроса ставки -->
<?php if (!(isset($round->bet)) && (isset($round->bank))): ?>
    <form class="bankForm" action="View.php" method="post">
        <p>Сделайте ставку</p>
        <input type="text" name="bet" placeholder="введите целое число">
        <button class="playerButton playerButton_bankBet" type="submit">Ok</button>
    </form>
<?php endif; ?>

<div class="cardsField">
<!-- Вывод карт игрока -->
<?php if ((isset($round->bet)) && (isset($round->bank))): ?>
    <div class="cardsField__hand">
    <p>Ваши карты - </p>
    <div class="cardsField__hand_cards">
        <?php foreach ($round->playerHand as $value): ?>
            <div class="card <?=$value?>"></div>
        <?php endforeach; ?>
    </div>
    <p>Ваша сумма -  <?=$round->playerSum?></p>
    <?php if ($round->playerHand2): ?>
        <div class="cardsField__hand_cards">
            <?php foreach ($round->playerHand2 as $value): ?>
                <div class="card <?=$value?>"></div>
            <?php endforeach; ?>
        </div>
        <p>Ваша сумма -  <?=$round->playerSum2?></p>
    <?php endif; ?>
    </div>

<!-- Вывод карт дилера -->
    <div class="cardsField__hand">
    <p>Карты дилера - </p>
    <div class="cardsField__hand_cards">
        <?php if ($round->endGame): ?>
            <?php foreach ($round->dealerHand as $value): ?>
                <div class="card <?=$value?>"></div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="card backcard"></div>
            <div class="card <?=$round->dealerHand[1]?>"></div>
        <?php endif; ?>
    </div>
    <?php if ($round->endGame): ?>
        <p>Сумма дилера - <?=$round->dealerSum?></p>
    <?php endif; ?>
    </div>
</div>

<!-- Форма запроса действий игрока -->
    <?php if (!($round->endGame)): ?>
        <form class="playerMove" action="View.php" method="post">
            <button class="playerButton playerButton_move" type="submit" name="playerMove" value="addCard">Еще карту!</button>
            <button class="playerButton playerButton_move" type="submit" name="playerMove" value="stop">Хватит</button>
            <?php if ($round->firstMove && $round->bet*2 <= $round->bank): ?>
                <button class="playerButton playerButton_move" type="submit" name="playerMove" value="doubleBet">Удвоить ставку</button>
            <?php endif; ?>
            <?php if ($round->insurance && $round->firstMove): ?>
                <button class="playerButton playerButton_move" type="submit" name="playerMove" value="insurance">Взять страховку</button>
            <?php endif; ?>
            <?php if ($round->split && $round->bet*2 <= $round->bank): ?>
                <button class="playerButton playerButton_move" type="submit" name="playerMove" value="split">Сплит</button>
            <?php endif; ?>
        </form>
    <?php endif; ?>
<?php endif; ?>

<!-- Результат игры -->
<?php if ($round->winner == 'player'): ?>
    <p>Вы выиграли!</p>
<?php elseif ($round->winner == 'dealer'): ?>
    <p>Вы проиграли</p>
<?php elseif ($round->winner == 'nobody'): ?>
    <p>Ничья!</p>
<?php endif; ?>

<?php if($round->endGame): ?>
<form action="View.php" method="post" name="newRound">
    <input type="hidden" name="newRound" checked>
    <button class="playerButton playerButton_move" type="submit">Еще раз!</button>
</form>
<?php endif; ?>
</main>
<footer>
    <div class="technology">
        <div class="tecnology__html5Logo"></div>
        <div class="technology__cssLogo"></div>
        <div class="technology__phpLogo"></div>
        <p>were used</p>
    </div>
    <a href="https://github.com/EgorBabenko/PHP_Blackjack">ИСХОДНИК НА GITHUB</a>
</footer>
</body>
</html>
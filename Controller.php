<?php
require_once 'model.php';

//анализ первоначального состояния
if ((isset($_POST['reload']))) {
    setcookie('round',0,time()-1000,'/');
    $round = new blackjack();
    setcookie('round',serialize($round),0,'/');
} elseif (!(isset($_COOKIE['round']))) {
    $round = new blackjack();
    setcookie('round',serialize($round),0,'/');
} elseif (isset($_COOKIE['bank'])) {
    setcookie('round',0,time()-1000,'/');
    $round = new blackjack();
    $round->bank = $_COOKIE['bank'];
    setcookie('bank',0,time()-1000,'/');
    setcookie('round',serialize($round),0,'/');
}
else {
    $round = unserialize($_COOKIE['round']);
}

//анализ действий пользователя

if (isset($_POST['bank'])) {
    if (ctype_digit($_POST['bank'])) {
        $round->bank = $_POST['bank'];
        $round->bank *= 1;
        setcookie('round', serialize($round), 0, '/');
    }
}
if (isset($_POST['bet'])) {
    if (ctype_digit($_POST['bet']) && $_POST['bet'] <= $round->bank) {
        $round->bet = $_POST['bet'];
        $round->bet *= 1;
        setcookie('round', serialize($round), 0, '/');
    }
}
if (isset($_POST['playerMove']))
{
    switch ($_POST['playerMove'])
    {
        case 'addCard':
            $round->addCard($round->playerHand,$round->playerSum);
            if ($round->playerHand2)
            {
                $round->addCard($round->playerHand2,$round->playerSum2);
            }
            break;
        case 'stop':
            $round->endGame = true;
            break;
        case 'split':
            $round->playerHand2[] = array_pop($round->playerHand);
            $round->addCard($round->playerHand,$round->playerSum);
            $round->addCard($round->playerHand2,$round->playerSum2);
            $round->splitAgree = true;
            $round->bet *= 2;
            break;
        case 'insurance':
            $round->insuranceAgree = true;
            break;
        case 'doubleBet':
            $round->bet *= 2;
            $round->addCard($round->playerHand,$round->playerSum);
            $round->endGame = true;

    }
    $round->firstMove = false;
    setcookie('round',serialize($round),0,'/');
}

//проверка достижения блекджека || перебора
if (!($round->splitAgree))
{
    if ($round->playerSum == 21)
    {
        $round->endGame = true;
    }
    if ($round->playerSum > 21)
    {
        $round->endGame = true;
        $round->overflow = true;
    }
} else
{
    if (($round->playerSum == 21 || $round->playerSum2 == 21))
    {
        $round->endGame = true;
    }
    if ($round->playerSum > 21 && $round->playerSum2 > 21)
    {
        $round->overflow = true;
        $round->endGame = true;
    }
}

//результат игры
if ($round->endGame)
{
    if ($round->overflow)
    {
        $round->winner = 'dealer';
        $round->moneyCount($round->winner);
        setcookie('bank', $round->bank, 0, '/');
    } else
    {
        $round->dealerMove();
        if ($round->dealerSum > 21)
        {
            $round->winner = 'player';
            $round->moneyCount($round->winner);
            setcookie('bank', $round->bank, 0, '/');
        } else
        {
            if (max($round->playerSum,$round->playerSum2) > $round->dealerSum)
            {
                $round->winner = 'player';
                $round->moneyCount($round->winner);
                setcookie('bank', $round->bank, 0, '/');
            } elseif (max($round->playerSum,$round->playerSum2) == $round->dealerSum)
            {
                $round->winner = 'nobody';
                setcookie('bank', $round->bank, 0, '/');
            } else
            {
                $round->winner = 'dealer';
                $round->moneyCount($round->winner);
                setcookie('bank', $round->bank, 0, '/');
            }
        }
    }

}


?>
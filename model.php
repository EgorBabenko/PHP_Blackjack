<?php
class blackjack
{
    //карты и их значение ->
    public const cards = ['two' => 2, 'three' => 3, 'four' => 4, 'five' => 5, 'six' => 6, 'seven' => 7, 'eight' => 8, 'nine' => 9,
        'ten' => 10, 'valet' => 10, 'dama' => 10, 'king' => 10, 'tuz' => 11];


    public $bank;
    public $bet;


    public $firstMove = true;
    public $insurance = false;
    public $insuranceAgree;
    public $endGame = false;
    public $split;
    public $splitAgree;

    public $playerHand = [];
    public $playerHand2;
    public $playerSum;
    public $playerSum2;

    public $dealerHand = [];
    public $dealerSum;
    public $winner;
    public $overflow;

    function __construct()
    {
        //наполняем руку игрока
        self::addCard($this->playerHand,$this->playerSum);
        self::addCard($this->playerHand,$this->playerSum);

        //наполняем руку дилера
        self::addCard($this->dealerHand,$this->dealerSum);
        self::addCard($this->dealerHand,$this->dealerSum);

        //проверка возможности страховки
        if (strpos($this->dealerHand[1],'tuz') !== false)
        {
            $this->insurance = true;
        }

        //проверка возможности split
        if ((substr($this->playerHand[0],0,-1)) ===
            (substr($this->playerHand[1],0,-1)))
        {
            $this->split = true;
        }
    }

    function addCard(&$hand,&$sum)
    {
        array_push
        (
            $hand,
            (array_keys(self::cards))[random_int(0,12)].random_int(1,4)
        );
        self::cardsCount($hand,$sum);
    }

    function cardsCount(&$hand,&$sum)
    {
        $sum = 0;
        foreach ($hand as $value)
        {
            foreach (self::cards as $card => $point)
            {
                if (strpos($value,$card) !== false)
                {
                    if (($card == 'tuz') && ($sum+11 > 21))
                    {
                        $sum += 1;
                    } else
                    {
                        $sum += $point;
                    }
                }
            }
        }
    }

    public function dealerMove()
    {
        while ($this->dealerSum < 16) {
            self::addCard($this->dealerHand,$this->dealerSum);
        }
    }

    public function moneyCount($winner)
    {
        if ($winner == 'dealer' && $this->insuranceAgree)
        {
            $this->bank -= (int)($this->bet/2);
        } elseif ($winner == 'dealer')
        {
            $this->bank -= $this->bet;
        } elseif ($winner == 'player')
        {
            $this->bank += $this->bet;
        }
    }

}
?>
<?php namespace Acme\Flows;

use Carbon\Carbon;

use Geomagilles\FlowGraph\Builder\GraphBuilder;

class Test
{
    public function init(&$j, &$increment, &$target)
    {
        $j = 1;
        $increment = 2;
        $target = 7;
    }

    public function decision($j, $target, $_)
    {
        ($j < $target) ? $_->follow('continue') : $_->follow('reached');
    }

    public function noWait(&$uid, $_)
    {
        // Add listener on firing job
        if ($uid == $_->event->getUserId()) {
            $_->follow('updated');
        };
    }

    public function wait($_)
    {
        //$_->wait(Carbon::now()->addSeconds(20));
        $_->wait(4);
    }

    public function increment(&$j, $increment)
    {
        $j+= $increment;
    }

    public static function build()
    {
        $builder = new GraphBuilder('test');

        $begin     = $builder->addBegin();
        $init      = $builder->addTask(__CLASS__.'@init');
        $decision  = $builder->addTask(__CLASS__.'@decision', ['continue', 'reached']);
        $wait      = $builder->addTask(__CLASS__.'@wait');
        //$wait->addListener(UserEvent::EMAIL_UPDATED, __CLASS__.'@noWait');

        $increment = $builder->addTask(__CLASS__.'@increment');
        $end       = $builder->addEnd();

        $builder->addArc($begin, $init);
        $builder->addArc($init, $decision);
        $builder->addArc($decision, $increment, 'continue');
        $builder->addArc($decision, $wait, 'reached');
        $builder->addArc($wait, $end);
        $builder->addArc($increment, $decision);
        $graph = $builder->getGraph();

        return $builder->getGraph();
    }
}

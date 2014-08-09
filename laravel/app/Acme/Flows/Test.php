<?php namespace Acme\Flows;

use Carbon\Carbon;

use Geomagilles\FlowGraph\Builder\GraphBuilder;

class Test
{
    public function init(&$j, &$increment, &$target)
    {
        $j = 1;
        $increment = 4;
        $target = 7;
    }

    public function decision($j, $target)
    {
        if ($j >= $target) {
            return 'reached';
        }
    }

    public function noWait(&$uid, UserEvent $event)
    {
        // Add listener on firing job
        if ($uid == $event->getUserId()) {
            return 'updated';
        };
    }

    public function wait()
    {
        return Carbon::now()->addSeconds(20);
        //return 4;
    }

    public function increment(&$j, $increment)
    {
        $j+= $increment;
    }

    public static function build()
    {
        //$begin     = $new->begin();
        //$init      = $new->task(__CLASS__.'@init');
        //$decision  = $new->task(__CLASS__.'@decision');
        //$increment = $new->task(__CLASS__.'@increment');
        //$wait      = $new->wait(__CLASS__.'@waitTime'); // UserEvent::EMAIL_UPDATED, __CLASS__.'@waitEvent')
        //$end       = $new->end();
        //
        //$new->connect($begin, $init)
        //    ->connect($init, $decision)
        //    ->connect($decision->continue, $increment)
        //    ->connect($decision->reached, $wait)
        //    ->connect($increment, $decision);
        //    ->connect($wait, $end);

        $new = new GraphBuilder('test');

        $begin     = $new->begin();
        $init      = $new->task(__CLASS__.'@init');
        $decision  = $new->task(__CLASS__.'@decision')->withOutput('reached');
        $wait      = $new->wait(__CLASS__.'@wait');//->withTrigger('trig1', UserEvent::EMAIL_UPDATED, __CLASS__.'@waitEvent');
        $increment = $new->task(__CLASS__.'@increment');
        $end       = $new->end();

        $new->connect($begin, $init)
            ->connect($init, $decision)
            ->connect($decision, $increment)
            ->connect($decision->output('reached'), $wait)
            ->connect($wait, $end)
            //->connect($wait->trigger('trig1'), $decision)
            ->connect($increment, $decision);

        return $new->getGraph();
    }
}

<?php

use Acme\Flows\Test;
use Geomagilles\FlowGraph\Factory\GraphFactory;
use Geomagilles\FlowManager\Models\Box\BoxFacade as Graph;
use Geomagilles\FlowManager\Decider\DeciderFacade as Decider;

class FlowController extends BaseController
{
    public function start($name)
    {
        $graph = Graph::getFirstBy('name', $name);

        if (is_null($graph)) {
            if ($name == 'test') {
                $graph = Test::build();
            } else {
                throw new Exception('Unknown flow "$name"');
            }
            $graph = Graph::store($graph);
        }
        
        $instance = $graph->instantiate();
        
        Decider::startInstance($instance->getId(), []);
        
        echo "Flow ".$graph->getId()." started on instance ". $instance->getId();
    }
}

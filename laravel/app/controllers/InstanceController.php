<?php

use Geomagilles\FlowManager\Decider\DeciderFacade as Decider;
use Geomagilles\FlowManager\Models\Instance\InstanceFacade as Instance;

class InstanceController extends BaseController
{
    public function start($id)
    {
        Decider::startInstance($id, []);
        
        echo "Instance ".$id." started";
    }

    public function pause($id)
    {
        Decider::pauseInstance($id, []);
        
        echo "Instance ".$id." paused";
    }

    public function resume($id)
    {
        Decider::resumeInstance($id, []);
        
        echo "Instance ".$id." resumed";
    }

    public function kill($id)
    {
        Decider::killInstance($id, []);
        
        echo "Instance ".$id." killed";
    }

    public function dump($id)
    {
        Instance::getById($id)->dump();

        echo "Instance ".$id." dumped";
    }
}

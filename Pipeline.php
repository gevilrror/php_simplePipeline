<?php

class Pipeline
{

    public function run($pipes, PipelineHandle $destination)
    {
        $pipeline = array_reduce(
        	array_reverse($pipes), 
        	function ($stack, Closure $pipe) {
	            return function (PipelineHandle $passable) use ($stack, $pipe) {
	                return $pipe($passable, $stack);
	            };
	        }, 
	        function (PipelineHandle $passable) use ($destination) {
	            return ($destination->func)();
	        }
        );

        return $pipeline($destination);
    }
}

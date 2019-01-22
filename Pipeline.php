<?php


class PipelineHandle
{
    public $func;
}


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


$a = array(function (PipelineHandle $handle, $next){
    var_dump('before 1', $handle->info);
    $r = $next($handle);
    var_dump('after 1', $handle->info);
    return $r;
}, function (PipelineHandle $handle, $next){
    var_dump('before 2', $handle->info);
    $r = $next($handle);
    var_dump('after 2', $handle->info);
    return $r;
}, function (PipelineHandle $handle, $next){
    var_dump('before 3', $handle->info);
    $r = $next($handle);
    var_dump('after 3', $handle->info);
    return $r;
}, function (PipelineHandle $handle, $next){
    var_dump('before 4', $handle->info);
    $r = $next($handle);
    var_dump('after 4', $handle->info);
    return $r;
}, function (PipelineHandle $handle, $next){
    var_dump('before 5', $handle->info);
    $r = $next($handle);
    var_dump('after 5', $handle->info);
    return $r;
});

$a2 = new PipelineHandle();
$a2->info = 333;
$a2->func = function (){
    return 1;
};

var_dump(memory_get_usage(), memory_get_peak_usage());

var_dump((new Pipeline())
            ->run($a, $a2));


var_dump(memory_get_usage(), memory_get_peak_usage());

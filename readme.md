#Hydra
Multiplatform PHP multiprocess script

##Why
PHP was never meant to do multiprocessing, right. Well true, and even though I always tell myself to learn how to do this properly in Java or whatever else is more logical,
it never actually happens. So I made this.

##How it works
Summary: The Factory adds Tasks to the Medium (Memcache), starts as many Workers and waits.
The Workers fetch the Task from the Medium, run it and add the output to the Task in the Medium.
The Factory retrieves the now resolved tasks from the Medium and returns them.

(Also check out start.php and script.php)

    $factory = new Factory();

    $task = new Task('path/to/somethingthatneedsrunning.php');

    $task->addOption('--some' => 'option');

    $factory->addTask($task);

    $results = $factory->execute();


##Future
The idea is to flesh out this system so that it supports not only Memcache and SQLite3 but also MySQL, SQLite, MongoDB, and perhaps even flat file.
I also want to in use of PCNTL (pcntl_fork() to be precise) whenever it is available. I expect it to be more efficient because it would cut out one
It needs more debug options and the worker needs more ways to crash non-silently.
It needs to check whether all requirements are met, as in, check whether php-cli is available and whether php-cli has the necessary modules.
Tt needs testing of course.
And it needs some way to config it.

##Symfony2
I want to make a Symfony2 Bundle out of this.

##Credit
It's basically a PHP 5.3+ continuation of Jamie Estep's https://github.com/jestep/PHP-Multi-process/tree without the pid-stuff. I wouldn't be surprised if his version is faster.
But this is more extendable.
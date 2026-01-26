<?php
declare(strict_types=1);

namespace eurokeep\Command;

use eurokeep\Model\Table\UsersTable;
use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\ORM\TableRegistry;

/**
 * UserAdd command.
 */
class UserAddCommand extends Command
{
    /**
     * Hook method for defining this command's option parser.
     *
     * @see https://book.cakephp.org/4/en/console-commands/commands.html#defining-arguments-and-options
     * @param \Cake\Console\ConsoleOptionParser $parser The parser to be defined
     * @return \Cake\Console\ConsoleOptionParser The built parser.
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser = parent::buildOptionParser($parser);

        return $parser;
    }

    /**
     * Implement this method with your command's logic.
     *
     * @param \Cake\Console\Arguments $args The command arguments.
     * @param \Cake\Console\ConsoleIo $io The console io
     * @return null|void|int The exit code or null for success
     */
    public function execute(Arguments $args, ConsoleIo $io)
    {
        $io->out('Add a new user.');

        $email = $io->ask('Please enter email.');
        $email = trim($email);

        $name = $io->ask('Please enter name.');
        $name = trim($name);

        $password = $io->ask('Please enter the password.');
        $password = trim($password);

        /** @var UsersTable $UserTable */
        $UserTable = TableRegistry::getTableLocator()->get('Users');
        $User = $UserTable->create($email, $name, $password);

        $io->out("User #{$User['id']}, {$User['email']} was created.");

        // @todo fetch host name from environment / config.
        $io->out("When logging in to https://eurokeep.nox.kiwi/ the user will have to reset the password.");
        $io->out("That's all, folks.");
    }
}

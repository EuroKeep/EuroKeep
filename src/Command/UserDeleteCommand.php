<?php
declare(strict_types=1);

namespace eurokeep\Command;

use eurokeep\Model\Table\UsersTable;
use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

/**
 * UserDelete command.
 */
class UserDeleteCommand extends Command
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
        /** @var UsersTable $UserTable */
        $UserTable = TableRegistry::getTableLocator()->get('Users');
        $Users = $UserTable->find()->toArray();
        $io->out("Select the user you want to remove from your EuroKeep instance");

        $mapping = [];
        foreach($Users as $User) {
            $io->out(sprintf(
                    '%s: %s (%s)',
                    $User['id'],
                    $User['name'],
                    $User['email']
                )
            );
            $mapping[$User['id']] = $User;
        }

        $userIds = Hash::extract($Users, '{n}.id');

        $selection = $io->askChoice('Please select a user ID', $userIds);


        $email = $io->ask('Please enter the user\' email address for sake of security.');
        $email = trim($email);

        $User = $mapping[$selection];

        if ($email !== $User['email']) {
            $io->out("Seems you were wrong with the email address.");
            $io->out("That's all, folks.");
            exit(1);
        }
        $UserTable->delete($User);

        $io->out("The user #{$User['id']}, {$User['email']} has been deleted, including all accounts, movements, etc.");
        $io->out("That's all, folks.");
    }
}

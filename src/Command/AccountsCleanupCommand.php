<?php
declare(strict_types=1);

namespace eurokeep\Command;

use eurokeep\Model\Table\AccountTable;
use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\ORM\TableRegistry;
use eurokeep\Model\Table\MovementTable;

/**
 * AccountsCleanup command.
 */
class AccountsCleanupCommand extends Command
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
        /** @var AccountTable $accountTable */
        $accountTable = TableRegistry::getTableLocator()->get('Account');
        $accounts = $accountTable->find();

        /** @var MovementTable $movementTable */
        $movementTable = TableRegistry::getTableLocator()->get('Movement');
        foreach ($accounts as $account) {
            $value = $movementTable
                ->find()
                ->where([
                    'account_id' => $account->get('id')
                ])
                ->all()
                ->sumOf('balance_value');

            $account->set('balance_value', $value);

            $account->save($account);
        }
    }
}

<?php
declare(strict_types=1);

namespace eurokeep\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\ORM\TableRegistry;
use eurokeep\Model\Table\AccountTable;
use eurokeep\Model\Table\TransactionsTable;
use Override;

/**
 * AccountsCleanup command.
 */
class AccountsCleanupCommand extends Command
{
    /**
     * Hook method for defining this command's option parser.
     *
     * @see https://book.cakephp.org/4/en/console-commands/commands.html#defining-arguments-and-options
     * @param ConsoleOptionParser $parser The parser to be defined
     * @return ConsoleOptionParser The built parser.
     */
    #[Override]
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser = parent::buildOptionParser($parser);

        return $parser;
    }

    /**
     * Implement this method with your command's logic.
     *
     * @param Arguments $args The command arguments.
     * @param ConsoleIo $io The console io
     * @return null|void|int The exit code or null for success
     */
    #[Override]
    public function execute(Arguments $args, ConsoleIo $io)
    {
        /** @var AccountTable $accountTable */
        $accountTable = TableRegistry::getTableLocator()->get('Account');
        $accounts = $accountTable->find()->all();

        /** @var TransactionsTable $transactionTable */
        $transactionTable = TableRegistry::getTableLocator()->get('Transactions');
        foreach ($accounts as $account) {
            $value = $transactionTable
                ->find()
                ->where([
                    'account_id' => $account->get('id')
                ])
                ->all()
                ->sumOf('balance_value');

            $account->balance_value = $value;
            $accountTable->save($account);
        }
    }
}

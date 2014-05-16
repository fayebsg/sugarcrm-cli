<?php

namespace Fbsg\SugarCLI\Commands;

use DBManagerFactory;
use RepairAndClear;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use User;

/**
 * Class QuickRepairRebuild
 *
 * @package Fbsg\SugarCLI\Commands
 */
class QuickRepairRebuild extends Command
{
    protected function configure()
    {
        $this
            ->setName('admin:repair')
            ->setDescription('Run quick repair and rebuild in your SugarCRM instance directly from the command line')
            ->addArgument(
                'path',
                InputArgument::OPTIONAL,
                'Path to the root sugarcrm instance'
            )
            ->addOption(
                'rebuild-database',
                null,
                InputOption::VALUE_NONE,
                'If set, we will automatically run the database sync/repair'
            );
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->checkDirectory($input->getArgument('path'), $output);
        $this->initSugar();
        $this->repair($output);
        $this->tearDown();
    }

    /**
     * @param string          $path
     * @param OutputInterface $output
     *
     * @throws \RuntimeException
     */
    protected function checkDirectory($path, OutputInterface $output)
    {
        if ($path) {
            if (file_exists($path)) {
                $output->writeln(sprintf('Changing directory to %s', $path));
                set_include_path(get_include_path() . PATH_SEPARATOR . $path);
                chdir($path);
                if (!file_exists('include/entryPoint.php')) {
                    throw new \RuntimeException(sprintf('Cannot find %s/include/entryPoint.php', $path));
                }

            } else {
                throw new \RuntimeException(sprintf('The folder %s does not exist', $path));
            }
        }
    }

    /**
     * @param OutputInterface $output
     */
    protected function repair(OutputInterface $output)
    {
        $repair = new RepairAndClear();
        $repair->repairAndClearAll(array('clearAll'), array(translate('LBL_ALL_MODULES')), true, false);
    }

    protected function initSugar()
    {
        if (!defined('sugarEntry')) {
            define('sugarEntry', true);
        }

        require_once('config.php');
        if (!empty($sugar_config)) {
            $GLOBALS['sugar_config'] = $sugar_config;
        }
        require_once('include/entryPoint.php');
        require_once('modules/Administration/QuickRepairAndRebuild.php');
        require_once('include/MVC/SugarApplication.php');

        // Scope is messed up due to requiring files within a function
        // We need to explicitly assign these variables to $GLOBALS
        foreach (get_defined_vars() as $key => $val) {
            $GLOBALS[$key] = $val;
        }

        if (empty($current_language)) {
            $current_language = $sugar_config['default_language'];
        }

        return_app_list_strings_language($current_language);
        return_application_language($current_language);

        global $current_user;
        $current_user = new User();
        $current_user->getSystemUser();
    }

    protected function tearDown()
    {
        sugar_cleanup(false);

        if (class_exists('DBManagerFactory')) {
            $db = DBManagerFactory::getInstance();
            $db->disconnect();
        }
    }
}
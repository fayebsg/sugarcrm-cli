# Usage

    $ php bin/sugarcli list
    Console Tool

    Usage:
      [options] command [arguments]

    Options:
      --help           -h Display this help message.
      --quiet          -q Do not output any message.
      --verbose        -v|vv|vvv Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
      --version        -V Display this application version.
      --ansi              Force ANSI output.
      --no-ansi           Disable ANSI output.
      --no-interaction -n Do not ask any interactive question.

    Available commands:
      help           Displays help for a command
      list           Lists commands
    admin
      admin:repair   Run quick repair and rebuild in your SugarCRM instance directly from the command line

## Commands

### Quick Repair and Rebuild

    $ php bin/sugarcli admin:repair --help
    Usage:
     admin:repair [--rebuild-database] [path]

    Arguments:
     path                  Path to the root sugarcrm instance

    Options:
     --rebuild-database    If set, we will automatically run the database sync/repair
     --help (-h)           Display this help message.
     --quiet (-q)          Do not output any message.
     --verbose (-v|vv|vvv) Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
     --version (-V)        Display this application version.
     --ansi                Force ANSI output.
     --no-ansi             Disable ANSI output.
     --no-interaction (-n) Do not ask any interactive question.


<?php namespace Mia\Installer\Command;

use Mia\Database\Command\BaseCommand;
use Mia\Installer\Generate\Mezzio\AbmHandler;

/**
 * Description of BaseCommand
 *
 * @author matiascamiletti
 */
class AbmMezzioCommand extends BaseCommand
{
    /**
     * @var string
     */
    protected $name = '';
    /**
     * @var string
     */
    protected $schema = '';
    

    public function __construct($name, $schema)
    {
        parent::__construct();
        $this->name = $name;
        $this->schema = $schema;
    }

    public function run()
    {
        $service = new AbmHandler();
        $service->schema = $this->schema;
        $service->name = $this->name;
        $service->run();
    }
}
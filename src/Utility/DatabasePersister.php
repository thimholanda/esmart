<?php

namespace App\Utility;

use AuditStash\Action\ElasticLogsIndexAction;

class DatabasePersister extends ElasticLogsIndexAction {

    public function logEvents()
    {
        parent::_handle();
    }

}

<?php
namespace app\models\uuid;

use app\models\HModel;

/**
 * PGModel
 * 
 * PGModel is short for Postgres Model and is designed to have features explicity for Postgresql.
 */
class PGModel extends HModel {
	
	public function fromHStore($data, $type = null) {
        if ($data === 'NULL') return null;

        @eval(sprintf("\$hstore = array(%s);", $data));

        if (!(isset($hstore) and is_array($hstore)))
        {
            throw new PommException(sprintf("Could not parse hstore string '%s' to array.", $data));
        }

        return $hstore;
    }
	
}

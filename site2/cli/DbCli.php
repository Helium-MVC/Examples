<?php
/**
 * DbCli
 * 
 * This class is used to make modification to the schema and database values. When new models
 * or fields in a model's schema are added, they can be updated to the database automatically.
 * 
 * php main/helium.php DbCli [functionanme]
 * 
 * Example
 * 
 * php main/helium.php DbCli schemacheck
 */
class DbCli {
	
	/**
	 * This function will iterate through the models and do a schemacheck. Schemachecks is when
	 * it checks the schema defined in the model, and attempts to replicate inside the database.
	 */
	public function schemacheck() {
		foreach(PVFileManager::getFilesInDirectory(PV_ROOT. DS. 'app/models/uuid'.DS) as $key => $value) {
				
			if($value !== 'PGModel.php' && $value !== 'ContactSubmissions.php') {
				$class_name = "app\models\uuid\\".str_replace('.php', '', $value);
				
				echo $class_name. "\n";
				$object = new $class_name();
				$object -> checkSchema(true);
			}
			
		}
	}
	
	/**
     * Activate required exentsions for working db
     */
    public function activateExtensions() {

        PVDatabase::query('CREATE EXTENSION hstore;');
        PVDatabase::query('CREATE EXTENSION "uuid-ossp";');
		
		$query = '
			create schema shard_1;
			create sequence shard_1.global_id_sequence;
			
			CREATE OR REPLACE FUNCTION shard_1.id_generator(OUT result bigint) AS $$
			DECLARE
			    our_epoch bigint := 1314220021721;
			    seq_id bigint;
			    now_millis bigint;
			    -- the id of this DB shard, must be set for each
			    -- schema shard you have - you could pass this as a parameter too
			    shard_id int := 1;
			BEGIN
			    SELECT nextval(\'shard_1.global_id_sequence\') % 1024 INTO seq_id;
			
			    SELECT FLOOR(EXTRACT(EPOCH FROM clock_timestamp()) * 1000) INTO now_millis;
			    result := (now_millis - our_epoch) << 23;
			    result := result | (shard_id << 10);
			    result := result | (seq_id);
			END;
			$$ LANGUAGE PLPGSQL;
			
			select shard_1.id_generator();
		';
		
		PVDatabase::query($query);
    }
	

	
}
